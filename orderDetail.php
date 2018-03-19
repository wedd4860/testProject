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

/*nav, title and subTitle*/
getRequestVar('pageInfo', 'add');
getRequestVar('mode','add');

$sqlCurrentFair = "
	SELECT setting_fair_seq
	FROM at_setting_info
	ORDER BY setting_seq DESC limit 1
";
$database->prepare($sqlCurrentFair);
$currentFair = $database->dataFetch();
$currentFairSeq= $currentFair['setting_fair_seq'];

getRequestVar('currentFairSeq',$currentFairSeq);

if($mode == 'add'){
	//단품 정보
	$sqlGoods = "
		SELECT fair.fair_title,goods.goods_seq,goods.goods_title,cate.category_title,price.price_goods_consumer,price.price_goods_price,price.price_goods_supply
		,CASE WHEN COALESCE(goods.goods_code,'-')='-' OR goods.goods_code='' THEN '-' ELSE goods.goods_code END AS goods_code
		,CASE WHEN COALESCE(goods.goods_model,'-')='-' OR goods.goods_model='' THEN '-' ELSE goods.goods_model END AS goods_model
		,DATE_FORMAT(goods.goods_regdt,'%Y-%m-%d') AS date_goods_regdt
			FROM at_goods_info goods
				LEFT JOIN at_goods_link_info link ON goods.goods_seq=link.link_goods_seq
				LEFT JOIN at_category_info cate ON link.link_category_seq=cate.category_seq
				LEFT JOIN at_goods_price_history price ON link.link_goods_seq=price.price_goods_seq
				LEFT JOIN at_fair_info fair ON price.price_fair_seq=fair.fair_seq
		WHERE goods.goods_status = 1 and price.price_fair_seq=:price_fair_seq AND price.price_status=1
	";
	$database->prepare($sqlGoods);
	$database->bind('price_fair_seq',$currentFairSeq);
	$bbsGoods = $database->dataAllFetch();

	//세트 정보
	$sqlSet = "
		SELECT set_seq, set_title FROM at_goods_set_info WHERE set_fair_seq = :price_fair_seq AND set_status=1
	";
	$database->prepare($sqlSet);
	$database->bind('price_fair_seq',$currentFairSeq);
	$bbsSet = $database->dataAllFetch();
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

$template->assign('pageInfo',$pageInfo);
$template->assign('mode',$mode);
$template->assign('currentFairSeq',$currentFairSeq);
$template->assign('bbsGoods',$bbsGoods);
$template->assign('bbsSet',$bbsSet);

$template->d();

?>