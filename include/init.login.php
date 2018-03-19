<?php
/*로그인 정보 세팅*/
$loginTF = $LOGIN_INFO->isLogin();
if(!$loginTF && _PHP_SELF == "president.php"){
	pageRedirect('loginPresident.php');
}else if(!$loginTF){
	pageRedirect('login.php');
}

/*arrayLogin 변수생성*/
$ArrayLogin=array(
	'seq'=>$LOGIN_INFO->getMemberSeq()
	,'id'=>$LOGIN_INFO->getMemberId()
	,'name'=>$LOGIN_INFO->getMemberName()
	,'nickName'=>$LOGIN_INFO->getMemberNick()
	,'level'=>$LOGIN_INFO->getMemberLevel()
);
?>