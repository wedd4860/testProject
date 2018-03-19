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

$currentList = array();
$dateCurrent = array();
$currentOrderList = array();
$beforeList = array();
$dateBefore = array();
$beforeOrderList = array();
$sqlCurrent = "
	SELECT fair.fair_title,fair.fair_seq,DATE_FORMAT(fair.fair_sdt,'%Y-%m-%d') AS curent_sdt,DATE_FORMAT(fair.fair_edt,'%Y-%m-%d') AS curent_edt
	FROM at_setting_info setting
	LEFT JOIN at_fair_info fair ON setting.setting_fair_seq = fair.fair_seq
	ORDER BY setting.setting_seq DESC LIMIT 1
";
$database->prepare($sqlCurrent);
$currentList = $database->dataFetch();

$current_std=$currentList['curent_sdt'];
$current_end=$currentList['curent_edt'];
for($current_std;$current_std<=$current_end;$current_std=date("Y-m-d", strtotime($current_std."+1 day"))){
	array_push($dateCurrent,$current_std);
}

foreach($dateCurrent as $key => $val){
	$sqlCurrentOrder = "
		SELECT DATE_FORMAT(orderInfo.order_regdt,'%Y-%m-%d') AS order_date, SUM(order_tot_settleprice) AS tot_price
		FROM at_order_trans orderInfo
		WHERE orderInfo.order_status = 1 AND (orderInfo.order_regdt >= :std_date AND orderInfo.order_regdt <= :end_date) AND orderInfo.order_status = 1	
		GROUP BY order_date
	";
	$database->prepare($sqlCurrentOrder);
	$database->bind(':std_date', $val." 00:00:00");
	$database->bind(':end_date', $val." 23:59:59");
	$currentOrderList[$val] = $database->dataFetch();
}


$sqlBefore = "
	SELECT fair.fair_title,fair.fair_seq,DATE_FORMAT(fair.fair_sdt,'%Y-%m-%d') AS curent_sdt,DATE_FORMAT(fair.fair_edt,'%Y-%m-%d') AS curent_edt
	FROM at_fair_info fair
	WHERE fair_status = 1 AND fair_seq < 8
	LIMIT 1
";
$database->prepare($sqlBefore);
$beforeList = $database->dataFetch();

$before_std=$beforeList['curent_sdt'];
$before_end=$beforeList['curent_edt'];
for($before_std;$before_std<=$before_end;$before_std=date("Y-m-d", strtotime($before_std."+1 day"))){
	array_push($dateBefore,$before_std);
}


foreach($dateBefore as $key => $val){
	$sqlBeforeOrder = "
		SELECT DATE_FORMAT(orderInfo.order_regdt,'%Y-%m-%d') AS order_date, SUM(order_tot_settleprice) AS tot_price
		FROM at_order_trans orderInfo
		WHERE orderInfo.order_status = 1 AND (orderInfo.order_regdt >= :std_date AND orderInfo.order_regdt <= :end_date) AND orderInfo.order_status = 1	
		GROUP BY order_date
	";
	$database->prepare($sqlBeforeOrder);
	$database->bind(':std_date', $val." 00:00:00");
	$database->bind(':end_date', $val." 23:59:59");
	$beforeOrderList[$val] = $database->dataFetch();
}

/*template 변수설정*/
$template = new MySmarty();
$template->assign('currentList',$currentList);
$template->assign('dateCurrent',$dateCurrent);
$template->assign('currentOrderList',$currentOrderList);
$template->assign('beforeList',$beforeList);
$template->assign('dateBefore',$dateBefore);
$template->assign('beforeOrderList',$beforeOrderList);

$template->d();
?>
