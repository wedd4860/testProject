<?
//initSet에서 _INCLUDE_ROOT 를 정의
include "include/init.php";
//template 사용시에만 include
include _CLASS_ROOT."class.smarty.php";
//DB 사용시에만 include
include _INCLUDE_ROOT."init.dbPDO.php";
$database = new Database(_DB_NAME);
include _INCLUDE_ROOT."init.utils.php";
/** 로그인정보 include/init.php*/
$loginTF = $LOGIN_INFO->isLogin();
if($loginTF){
	pageRedirect('president.php');
}
$loginMemberSeq = $LOGIN_INFO->getMemberSeq();
$loginId = $LOGIN_INFO->getMemberId();
$loginNickname = $LOGIN_INFO->getMemberName();
$loginEmail = $LOGIN_INFO->getMemberNick();

/*template 변수설정*/
$template = new MySmarty();
$template->assign('siteUrl',_SITE_URL);
$template->d();

?>
