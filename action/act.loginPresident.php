<?
//initSet에서 _INCLUDE_ROOT 를 정의
include "../include/init.php";
//DB 사용시에만 include
include _INCLUDE_ROOT."init.dbPDO.php";
$database = new Database(_DB_NAME);
include _INCLUDE_ROOT."init.utils.php";

getRequestVar("password");

if(empty($password)){
	$database = null;//db connection close
	pageRedirect('../loginPresident.php','잘못된 접근입니다.');
}

$redirectUrl = '';
$resultMsg = '';

$memberRow = array();
$sql = "
	select member_seq, member_id, member_name, member_nick, member_level
	from at_member_info
	where member_status = 0 and member_id = :id and member_pwd = password(:pass)
";
$database->prepare($sql);
$database->bind(':id', "president");
$database->bind(':pass', $password);

//1줄
$memberRow = $database->dataFetch();
$database = null;//db connection close


if(empty($memberRow)){	
	$redirectUrl = '../loginPresident.php';
	$resultMsg = '비밀번호를 확인해주세요.';
}else{
	$cookie_msg = "member_seq=".$memberRow["member_seq"];
	$cookie_msg.= "&member_id=".$memberRow["member_id"];
	$cookie_msg.= "&member_name=".$memberRow["member_name"];
	$cookie_msg.= "&member_nick=".$memberRow["member_nick"];
	$cookie_msg.= "&member_level=".$memberRow["member_level"];

	$cookie = new COOKIE("MEMBER_LOGIN_COOKIE");
	$setcookie_check = $cookie->setLoginCookie( $memberRow["member_seq"], $cookie_msg );
	$redirectUrl = '../president.php';
	//	$resultMsg = '로그인성공!!';
}
pageRedirect($redirectUrl, $resultMsg);
?>