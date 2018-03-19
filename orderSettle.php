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
getRequestVar('pageInfo', 'mod');
getRequestVar('bbsSeq');
getRequestVar('mode', 'mod');

if($mode == 'mod'){
	//게시글 정보
	if(!empty($bbsSeq)){
		/*
			SELECT orderTrans.order_code,orderTrans.order_customer,orderTrans.order_mobile,orderTrans.order_address,orderTrans.order_memo,orderTrans.order_admin_memo,orderTrans.order_moddt,orderTrans.order_regdt
			,orderTrans.order_canceldt,orderTrans.order_tot_orgprice,orderTrans.order_tot_goodsprice,orderTrans.order_tot_settleprice
			,CASE WHEN (COALESCE(orderTrans.order_payment_type,0)=1) THEN '현금' WHEN (COALESCE(orderTrans.order_payment_type,0)=2) THEN '카드' WHEN (COALESCE(orderTrans.order_payment_type,0)=3) THEN '현금+카드' ELSE '-' END AS order_payment_type
			,orderTrans.order_pgAppNo
			,CASE WHEN (COALESCE(orderTrans.order_status,0)=1) THEN '주문' WHEN (COALESCE(orderTrans.order_status,0)=2) THEN '취소' ELSE '-' END AS order_status
			,member.member_nick,fair.fair_title,fair.fair_seq,orderTrans.order_status
				FROM at_order_trans orderTrans
					LEFT JOIN at_fair_info fair ON orderTrans.order_fair_seq = fair.fair_seq
					LEFT JOIN at_member_info member ON orderTrans.order_member_seq = member.member_seq
				WHERE orderTrans.order_seq=:order_seq
		*/
		$sql = "
			SELECT orderTrans.order_code,orderTrans.order_customer,orderTrans.order_mobile,orderTrans.order_address,orderTrans.order_memo,orderTrans.order_admin_memo,orderTrans.order_moddt,orderTrans.order_regdt,orderTrans.order_canceldt,orderTrans.order_tot_orgprice,orderTrans.order_tot_goodsprice,orderTrans.order_tot_settleprice,orderTrans.order_payment_type,orderTrans.order_pgAppNo,orderTrans.order_status,member.member_nick,fair.fair_title,fair.fair_seq,orderTrans.order_status
				FROM at_order_trans orderTrans
					LEFT JOIN at_fair_info fair ON orderTrans.order_fair_seq = fair.fair_seq
					LEFT JOIN at_member_info member ON orderTrans.order_member_seq = member.member_seq
				WHERE orderTrans.order_seq=:order_seq
		";
		$database->prepare($sql);
		$database->bind(':order_seq', $bbsSeq);
		$bbs = $database->dataFetch();
	}else{
		pageRedirect('orderList.php');
	}

	//단품리스트
	$sqlOne = "
		SELECT COUNT(item_seq) AS bbs_one_count 
		FROM at_order_item 
		WHERE item_order_seq=:item_order_seq AND item_gubun=1
	";
	$database->prepare($sqlOne);
	$database->bind(':item_order_seq', $bbsSeq);
	if($row = $database->dataFetch()){
		$totalOneCount = $row['bbs_one_count'];
	}
	$bbsOneList = array();
	if($totalOneCount > 0){
		/*
			SELECT cate.category_title, goods.goods_title, orderItem.item_goods_price, orderItem.item_goods_consumer, orderItem.item_ea
			, CASE WHEN (COALESCE(orderItem.item_order_type,0)=1) THEN '현장수령' WHEN (COALESCE(orderItem.item_order_type,0)=2) THEN '예약배송' ELSE '-' END AS item_order_type
				FROM at_order_item orderItem
				LEFT JOIN at_goods_info goods ON orderItem.item_goods_seq = goods.goods_seq
				LEFT JOIN at_goods_link_info link ON goods.goods_seq = link.link_goods_seq
				LEFT JOIN at_category_info cate ON link.link_category_seq = cate.category_seq
				WHERE orderItem.item_order_seq = :item_order_seq AND orderItem.item_gubun = 1
		*/
		$sql = "
			SELECT orderItem.item_seq, cate.category_title, goods.goods_seq, goods.goods_title, orderItem.item_goods_price, orderItem.item_goods_consumer, orderItem.item_ea
			,orderItem.item_order_type
				FROM at_order_item orderItem
				LEFT JOIN at_goods_info goods ON orderItem.item_goods_seq = goods.goods_seq
				LEFT JOIN at_goods_link_info link ON goods.goods_seq = link.link_goods_seq
				LEFT JOIN at_category_info cate ON link.link_category_seq = cate.category_seq
				WHERE orderItem.item_order_seq = :item_order_seq AND orderItem.item_gubun = 1
		";
		$database->prepare($sql);
		$database->bind(':item_order_seq', $bbsSeq);
		$bbsOneList = $database->dataAllFetch();
	}

	//세트리스트
	$sqlSet = "
		SELECT COUNT(item_seq) AS bbs_set_count 
		FROM at_order_item 
		WHERE item_order_seq=:item_order_seq AND item_gubun=2
	";
	$database->prepare($sqlSet);
	$database->bind(':item_order_seq', $bbsSeq);
	if($row = $database->dataFetch()){
		$totalSetCount = $row['bbs_set_count'];
	}
	$bbsSetList = array();
	if($totalSetCount > 0){
		/*
			SELECT cate.category_title, goods.goods_title, orderItem.item_goods_price, orderItem.item_goods_consumer, orderItem.item_ea, orderItem.item_set_seq
			, CASE WHEN (COALESCE(orderItem.item_order_type,0)=1) THEN '현장수령' WHEN (COALESCE(orderItem.item_order_type,0)=2) THEN '예약배송' ELSE '-' END AS item_order_type
				FROM at_order_item orderItem
				LEFT JOIN at_goods_info goods ON orderItem.item_goods_seq = goods.goods_seq
				LEFT JOIN at_goods_link_info link ON goods.goods_seq = link.link_goods_seq
				LEFT JOIN at_category_info cate ON link.link_category_seq = cate.category_seq
				WHERE orderItem.item_order_seq = :item_order_seq AND orderItem.item_gubun = 2
		*/
		$sql = "
			SELECT orderItem.item_seq, cate.category_title, goods.goods_seq, goods.goods_title, orderItem.item_goods_price, orderItem.item_goods_consumer, orderItem.item_ea, orderItem.item_set_seq
			, orderItem.item_order_type
				FROM at_order_item orderItem
				LEFT JOIN at_goods_info goods ON orderItem.item_goods_seq = goods.goods_seq
				LEFT JOIN at_goods_link_info link ON goods.goods_seq = link.link_goods_seq
				LEFT JOIN at_category_info cate ON link.link_category_seq = cate.category_seq
				WHERE orderItem.item_order_seq = :item_order_seq AND orderItem.item_gubun = 2
		";
		$database->prepare($sql);
		$database->bind(':item_order_seq', $bbsSeq);
		$bbsSetList = $database->dataAllFetch();
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
$template->assign('bbs',$bbs);

$template->assign('bbsSeq',$bbsSeq);
$template->assign('mode',$mode);
$template->assign('totalOneCount',$totalOneCount);
$template->assign('bbsOneList',$bbsOneList);
$template->assign('totalSetCount',$totalSetCount);
$bbsSetListArray=[];
foreach($bbsSetList as $key=>$val){
	$bbsSetListArray[$val["item_set_seq"]][$key] = $val;
}
$template->assign('bbsSetList',$bbsSetListArray);
$template->d();
?>