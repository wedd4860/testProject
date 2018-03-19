<?
//initSet에서 _INCLUDE_ROOT 를 정의
include "include/init.php";
include _INCLUDE_ROOT."init.utils.php";

$cookie = new COOKIE("MEMBER_LOGIN_COOKIE");
$setcookie_check = $cookie->deleteCookie();

pageRedirect('login.php');
?>
