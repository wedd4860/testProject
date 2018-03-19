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

getRequestVar('setSeq');
getRequestVar('setFairSeq');
getRequestVar('mode', 'mod');

if($mode == 'mod'){

	//전시회 셀렉정보
	$sqlFiar = "
		SELECT fair_seq, fair_title
		FROM at_fair_info
		WHERE fair_status=1
	";
	$database->prepare($sqlFiar);
	$bbsFair = $database->dataAllFetch();

	
	$sqlSetting = "
		SELECT setting_fair_seq
		FROM at_setting_info
		ORDER BY setting_seq DESC limit 1
	";
	$database->prepare($sqlSetting);
	$bbsSetting = $database->dataFetch();
}else{
	//에러
	exit();
}

include _INCLUDE_ROOT."init.nav.php";

$template=new MySmarty();

/*기본 템플릿(기본, nav, login) 변수 설정*/
include _INCLUDE_ROOT."init.smarty.default.php";

//template 변수
$template->assign('pageTitle',$pageTitle);
$template->assign('pageSubTitle',$pageSubTitle);
$template->assign('currentFairTitle',$currentFairTitle);
$template->assign('bbsFair',$bbsFair);
$template->assign('bbsSetting',$bbsSetting);

$template->d();

?>