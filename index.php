<?
//initSet에서 _DOCUMENT_ROOT 를 정의
include "include/init.php";
//DB 사용시에만 include
include _INCLUDE_ROOT."init.dbPDO.php";
$database = new Database(_DB_NAME);
include _INCLUDE_ROOT."init.utils.php";
/** 로그인정보 */
$loginTF = $LOGIN_INFO->isLogin();
if(!$loginTF){
	pageRedirect('./login.php');
}
$loginMemberSeq = $LOGIN_INFO->getMemberSeq();
$loginNickname = $LOGIN_INFO->getMemberName();
$loginEmail = $LOGIN_INFO->getMemberNick();
pageRedirect('./orderList.php');

$database = null;//db connection close
?>