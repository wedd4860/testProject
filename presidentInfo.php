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

getRequestVar('date');

if(empty($date)){
	pageRedirect('./president.php','잘못된 접근입니다.');
	pageRedirect($redirectUrl, $resultMsg);
}

$bbsList = array();
/*
$sql = "
	SELECT DATE_FORMAT(orderInfo.order_regdt,'%Y-%m-%d') AS order_date, goods.goods_title, SUM(orderItem.item_ea) as ea, SUM(orderItem.item_goods_price) AS price
	FROM at_order_trans orderInfo
	LEFT JOIN at_order_item orderItem ON orderInfo.order_seq = orderItem.item_order_seq
	LEFT JOIN at_goods_info goods ON orderItem.item_goods_seq = goods.goods_seq
	WHERE orderInfo.order_status = 1 AND (orderInfo.order_regdt >= :std_date AND orderInfo.order_regdt <= :end_date) AND orderInfo.order_status = 1	
	GROUP BY goods.goods_title
";
*/
$sql = "
	SELECT cate.category_title, DATE_FORMAT(orderInfo.order_regdt,'%Y-%m-%d') AS order_date, goods.goods_title, SUM(orderItem.item_ea) AS ea, SUM(orderItem.item_goods_price) AS price
		FROM at_order_trans orderInfo
		LEFT JOIN at_order_item orderItem ON orderInfo.order_seq = orderItem.item_order_seq
		LEFT JOIN at_goods_info goods ON orderItem.item_goods_seq = goods.goods_seq
		LEFT JOIN at_goods_link_info link ON link.link_goods_seq = goods.goods_seq
		LEFT JOIN at_category_info cate ON link.link_category_seq = cate.category_seq
		WHERE orderInfo.order_status = 1 AND (orderInfo.order_regdt >= :std_date AND orderInfo.order_regdt <= :end_date) AND orderInfo.order_status = 1	
		GROUP BY cate.category_title	
";

$database->prepare($sql);
$database->bind(':std_date', $date." 00:00:00");
$database->bind(':end_date', $date." 23:59:59");
$bbsList = $database->dataAllFetch();


/*template 변수설정*/
$template = new MySmarty();
$template->assign('date',$date);
$template->assign('bbsList',$bbsList);
$template->d();
?>
