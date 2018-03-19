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

getRequestVar('setSeq');
getRequestVar('setFairSeq');
getRequestVar('mode', 'mod');

if($mode == 'mod'){
	/*nav, title and subTitle*/
	getRequestVar('pageInfo', 'optionMod');

	getRequestVar('bbsSeq');
	//게시글 정보
	if(!empty($bbsSeq)){
		$sql = "
			SELECT goods.goods_seq, goods.goods_title, setOption.set_option_percent, setOption.set_option_set_seq
			FROM at_goods_info goods
			LEFT JOIN at_goods_set_option_info setOption ON goods.goods_seq = setOption.set_option_goods_seq
			WHERE setOption.set_option_seq=:set_option_seq
		";
		$database->prepare($sql);
		$database->bind(':set_option_seq', $bbsSeq);
		$bbs = $database->dataFetch();
		getRequestVar('setSeq',$bbs['set_option_set_seq']);
	}else{
		pageRedirect('goodsSetList.php');
	}
	
}else if($mode == 'add'){
	/*nav, title and subTitle*/
	getRequestVar('pageInfo', 'optionAdd');
	$sql = "
		SELECT goods.goods_seq, goods.goods_title
			FROM at_goods_info goods
			LEFT OUTER JOIN (	
				SELECT goods.goods_seq, goods.goods_title, setOption.set_option_percent, price.price_goods_consumer, fair.fair_title, setOption.set_option_status
				FROM at_goods_info goods
				LEFT JOIN at_goods_set_option_info setOption ON goods.goods_seq = setOption.set_option_goods_seq
				LEFT JOIN at_goods_price_history price ON goods.goods_seq = price.price_goods_seq
				LEFT JOIN at_fair_info fair ON price.price_fair_seq = fair.fair_seq
				WHERE goods.goods_status = 1 AND price.price_status = 1 AND fair.fair_seq =:fair_seq AND setOption.set_option_set_seq = :set_option_set_seq AND setOption.set_option_status = 1	
			) customOne ON customOne.goods_seq=goods.goods_seq		
		LEFT JOIN at_goods_price_history price ON goods.goods_seq = price.price_goods_seq
		LEFT JOIN at_fair_info fair ON price.price_fair_seq = fair.fair_seq
	";
	$sql.= "	WHERE goods.goods_status = 1 AND IFNULL(customOne.set_option_status,'A')='A' AND fair.fair_seq=:fair_seq AND price.price_status = 1";
	$database->prepare($sql);
	$database->bind(':set_option_set_seq', $setSeq);
	$database->bind(':fair_seq', $setFairSeq);
	$bbsGoods = $database->dataAllFetch();

	if(!$setSeq){
		pageRedirect('goodsSetList.php','잘못된 접근 입니다.');
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
$template->assign('setSeq',$setSeq);
$template->assign('setFairSeq',$setFairSeq);
if($mode=="mod"){
	$template->assign('bbsSeq',$bbsSeq);
	$template->assign('pageInfo',$pageInfo);
	$template->assign('bbs',$bbs);
}else if($mode=="add"){
	$template->assign('pageInfo',$pageInfo);
	$template->assign('bbsGoods',$bbsGoods);
}

$template->d();

?>