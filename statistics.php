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
getRequestVar('pageInfo', 'list');

/*옵션 및 상품명*/
getRequestVar('mode');
if($mode=="search"){
	getRequestVar('dateRange');

	$dateStr=explode(" ~ ",$dateRange);
	$std_date=$dateStr[0]." 00:00:00";
	$end_date=$dateStr[1]." 23:59:59";
	$cateList = array();
	$cateCountList = array();
	$cateDetailList = array();
	$cateDetailCountList = array();
	$goodsList = array();
	$orderGoodsList = array();
	$orderCateList = array();
	$dateList=array();

	//날짜별 출력
	$forDateStr=$dateStr[0];
	$forDateEnd=$dateStr[1];
	for($forDateStr;$forDateStr<=$forDateEnd;$forDateStr=date("Y-m-d", strtotime($forDateStr."+1 day"))){
		array_push($dateList,$forDateStr);
	}

	//카테고리 정보
	$sqlCate = "
		SELECT category_code, category_title FROM at_category_info
		WHERE category_status = 1 AND LENGTH(category_code)=3
	";

	$database->prepare($sqlCate);
	$cateList = $database->dataAllFetch();

	//카테고리 수량
	foreach($cateList as $key => $val){
		$sqlCateCount = "
			SELECT COUNT(*) as cateCount
				FROM at_goods_info goods	
				LEFT JOIN at_goods_link_info goodsLink ON goods.goods_seq = goodsLink.link_goods_seq
				LEFT JOIN at_category_info cate ON goodsLink.link_category_seq = cate.category_seq
			WHERE goods.goods_status = 1 AND cate.category_code LIKE :category_code
		";

		$database->prepare($sqlCateCount);
		$database->bind(':category_code', $val['category_code']."%");
		$cateCountList[$val['category_code']] = $database->dataFetch();
	}

	//하위카테고리 정보
	foreach($cateList as $key => $val){
		$sqlCateDetailseq = "
			SELECT category_code, category_title FROM at_category_info
			WHERE category_status = 1 AND LEFT(category_code,3) = :category_code AND LENGTH(category_code) > 3
		";

		$database->prepare($sqlCateDetailseq);
		$database->bind(':category_code', $val['category_code']);
		$cateDetailList[$val['category_code']] = $database->dataAllFetch();
		
		//하위카테고리 수량
		foreach($cateDetailList[$val['category_code']] as $cateDetailKey => $cateDetailVal){
			$sqlCateDetailCount = "
				SELECT COUNT(*) as cateCount
					FROM at_goods_info goods	
					LEFT JOIN at_goods_link_info goodsLink ON goods.goods_seq = goodsLink.link_goods_seq
					LEFT JOIN at_category_info cate ON goodsLink.link_category_seq = cate.category_seq
				WHERE goods.goods_status = 1 AND cate.category_code = :category_code
			";
			$database->prepare($sqlCateDetailCount);
			$database->bind(':category_code', $cateDetailVal['category_code']);
			$cateDetailCountList[$cateDetailVal['category_code']]=$database->dataFetch();
		}
		//상품정보
		foreach($cateDetailList[$val['category_code']] as $cateDetailKey => $cateDetailVal){
			$sqlGoods = "
				SELECT goods.goods_title, goods.goods_seq, category_code, category_title
					FROM at_goods_info goods	
					LEFT JOIN at_goods_link_info goodsLink ON goods.goods_seq = goodsLink.link_goods_seq
					LEFT JOIN at_category_info cate ON goodsLink.link_category_seq = cate.category_seq
				
			";
			$sqlGoods.= "		WHERE goods.goods_status = 1 AND category_code = :category_code";
			$database->prepare($sqlGoods);
			$database->bind(':category_code', $cateDetailVal['category_code']);
			$goodsList[$cateDetailVal['category_code']]=$database->dataAllFetch();
		}
	}


	//상품개별 정보 조회
	//상품개별
	$sqlTempG = "
		SELECT goods_seq FROM at_goods_info
		WHERE goods_status = 1
	";

	$database->prepare($sqlTempG);
	$tempGoodsList = $database->dataAllFetch();

	//날짜별 상품정보
	foreach($dateList as $dateKey => $dateVal){
		foreach($tempGoodsList as $key => $val){
			$sqlTemp = "
					SELECT DATE_FORMAT(orderInfo.order_regdt,'%Y-%m-%d') AS customRegdt, goods.goods_title, goods.goods_seq AS goodsSeq, SUM(orderItem.item_ea) AS ea, SUM(orderItem.item_goods_price) AS price
			FROM at_order_trans orderInfo
			LEFT JOIN at_order_item orderItem ON orderInfo.order_seq = orderItem.item_order_seq
			LEFT JOIN at_goods_info goods ON orderItem.item_goods_seq = goods.goods_seq
			WHERE orderInfo.order_status = 1 AND goods.goods_seq=:goods_seq
			AND(orderInfo.order_regdt >= :std_date AND orderInfo.order_regdt <= :end_date) AND order_status = 1
			";
			$stdDateVal=$dateVal." 00:00:00";
			$endDateVal=$dateVal." 23:59:59";
			$database->prepare($sqlTemp);
			$database->bind(':goods_seq', $val["goods_seq"]);
			$database->bind(':std_date', $stdDateVal);
			$database->bind(':end_date', $endDateVal);
			$orderGoodsList[$dateVal][$val["goods_seq"]]=$database->dataFetch();
		}
		
		foreach($cateDetailCountList as $key => $val){
			$sqlTemp = "
					SELECT cate.category_title,cate.category_code, DATE_FORMAT(orderInfo.order_regdt,'%Y-%m-%d') AS customRegdt, cate.category_code, goods.goods_title, goods.goods_seq AS goodsSeq, SUM(orderItem.item_ea) AS ea, SUM(orderItem.item_goods_price) AS price
				FROM at_order_trans orderInfo
				
				LEFT JOIN at_order_item orderItem ON orderInfo.order_seq = orderItem.item_order_seq 
				LEFT JOIN at_goods_info goods ON orderItem.item_goods_seq = goods.goods_seq
				LEFT JOIN at_goods_link_info goodsLink ON goods.goods_seq = goodsLink.link_goods_seq
				LEFT JOIN at_category_info cate ON goodsLink.link_category_seq = cate.category_seq
				
				WHERE orderInfo.order_status = 1  AND cate.category_code=:category_code
				AND(orderInfo.order_regdt >= :std_date AND orderInfo.order_regdt <= :end_date) AND order_status = 1
			";
			$stdDateVal=$dateVal." 00:00:00";
			$endDateVal=$dateVal." 23:59:59";
			$database->prepare($sqlTemp);
			$database->bind(':category_code', $key);
			$database->bind(':std_date', $stdDateVal);
			$database->bind(':end_date', $endDateVal);
			$orderCateList[$dateVal][$key]=$database->dataFetch();
		}

	}
}//$mode=="search"

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

if($mode=="search"){
	$template->assign('cateList',$cateList);
	$template->assign('cateCountList',$cateCountList);
	$template->assign('cateDetailList',$cateDetailList);
	$template->assign('cateDetailCountList',$cateDetailCountList);
	$template->assign('goodsList',$goodsList);
	$template->assign('dateList',$dateList);
	$template->assign('orderCateList',$orderCateList);
	$template->assign('orderGoodsList',$orderGoodsList);
	$template->assign('dateRange',$dateRange);
}
$template->d();
?>