<?
//initSet에서 _INCLUDE_ROOT 를 정의
include "include/init.php";
//template 사용시에만 include
include _CLASS_ROOT."class.smarty.php";
//DB 사용시에만 include
include _INCLUDE_ROOT."init.dbPDO.php";
$database = new Database(_DB_NAME);
include _INCLUDE_ROOT."init.utils.php";
include _INCLUDE_ROOT."init.login.php";

getRequestVar('priceGoodsSeq');
getRequestVar('mode', 'mod');

//전시회 셀렉정보
$sqlFiar = "
	SELECT fair.fair_seq, fair.fair_title,price.price_fair_seq,price.price_goods_price
	FROM at_fair_info fair
		LEFT OUTER JOIN (SELECT * FROM at_goods_price_history WHERE price_goods_seq=:price_goods_seq) AS price ON fair.fair_seq=price.price_fair_seq
";

if($mode == 'mod'){
	/*nav, title and subTitle*/
	getRequestVar('pageInfo', 'priceMod');

	$pageTitle = '';
	getRequestVar('bbsSeq');

	$sqlFiar.= "		WHERE fair.fair_status = 1 AND fair.fair_seq = price.price_fair_seq AND price.price_status = 1";
	$database->prepare($sqlFiar);
	$database->bind(':price_goods_seq', $priceGoodsSeq);
	$bbsFair = $database->dataAllFetch();

	//게시글 정보
	if(!empty($bbsSeq)){

		$sql = "
			select price_seq, price_fair_seq, price_goods_seq, price_goods_consumer, price_goods_supply
			from at_goods_price_history
			where price_status = 1 and price_seq = :price_seq
		";
		$database->prepare($sql);
		$database->bind(':price_seq', $bbsSeq);
		$bbs = $database->dataFetch();

	}else{
		pageRedirect('goodsList.php');
	}
	
}else if($mode == 'add'){
	/*nav, title and subTitle*/
	getRequestVar('pageInfo', 'priceAdd');

	$sqlFiar.= "		WHERE fair.fair_status = 1 AND ( fair.fair_seq != IFNULL(price.price_fair_seq,'A') OR price.price_status = 9 ) ";
	$database->prepare($sqlFiar);
	$database->bind(':price_goods_seq', $priceGoodsSeq);
	$bbsFair = $database->dataAllFetch();

	//기본 가격 가져오기 추가
	$sqlPriceCount = "
		SELECT COUNT(*) as bbs_count FROM at_goods_price_history
			WHERE price_goods_seq = 1 AND price_status = 1
	";

	$database->prepare($sqlPriceCount);
	$database->bind(':price_goods_seq', $priceGoodsSeq);
	if($row = $database->dataFetch()){
		$priceCount = $row['bbs_count'];
	}
	if($priceCount > 0){
		$sqlPrice="
			SELECT price_goods_consumer,price_goods_supply FROM at_goods_price_history
				WHERE price_goods_seq = :price_goods_seq AND price_status = 1
				ORDER BY price_seq DESC
				LIMIT 1
		";
		$database->prepare($sqlPrice);
		$database->bind(':price_goods_seq', $priceGoodsSeq);
		$bbsPrice = $database->dataFetch();
	}//end 기본가격 가져오기

	if(!$priceGoodsSeq){
		pageRedirect('goodsList.php','잘못된 접근 입니다.');
	}
}else{
	//에러
	exit();
}

include _INCLUDE_ROOT."init.nav.php";

/*template 변수설정*/
$template = new MySmarty();

/*기본 템플릿(기본, nav, login) 변수 설정*/
include _INCLUDE_ROOT."init.smarty.default.php";

//template 변수
$template->assign('pageTitle',$pageTitle);
$template->assign('pageSubTitle',$pageSubTitle);
$template->assign('currentFairTitle',$currentFairTitle);

$template->assign('mode',$mode);
$template->assign('priceGoodsSeq',$priceGoodsSeq);
$template->assign('bbsFair',$bbsFair);
if($mode=="mod"){
	$template->assign('bbsSeq',$bbsSeq);
	$template->assign('pageInfo',$pageInfo);
	$template->assign('bbs',$bbs);
}else if($mode=="add"){
	$template->assign('pageInfo',$pageInfo);
	$template->assign('bbsPrice',$bbsPrice);
}

$template->d();
?>