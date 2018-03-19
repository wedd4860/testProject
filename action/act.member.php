<?
//initSet에서 _INCLUDE_ROOT 를 정의
include "../include/init.php";
//DB 사용시에만 include
include _INCLUDE_ROOT."init.dbPDO.php";
$database = new Database(_DB_NAME);
include _INCLUDE_ROOT."init.utils.php";

getRequestVar("page_type");
if($page_type=="admin"){
	/** 로그인정보 include/init.php*/
	$loginTF = $LOGIN_INFO->isLogin();
	if(!$loginTF){
		pageRedirect('../login.php');
	}
	$loginMemberSeq = $LOGIN_INFO->getMemberSeq();
	$loginId = $LOGIN_INFO->getMemberId();
	$loginName = $LOGIN_INFO->getMemberName();
	$loginNickname = $LOGIN_INFO->getMemberNick();
	$loginLevel = $LOGIN_INFO->getMemberLevel();
}else{
	$database = null;//db connection close
	pageRedirect('../memberDetail.php','잘못된 접근입니다.');
}

$redirectUrl = "../memberDetail.php";
$resultMsg = "";

/*post data*/
getRequestVar('mode', 'mod');


if($mode == 'fair'){
	getRequestVar('setFairSeq');

	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "
		INSERT INTO at_setting_info (setting_fair_seq, setting_regdt) 
		VALUES (
			:setting_fair_seq
			, NOW()
		)
	";
	$database->prepare($sql);
	$database->bind(':setting_fair_seq', $setFairSeq);
	$database->execute();

	$redirectUrl = '../memberDetail.php';
	$resultMsg = '현재 페어정보를 설정 하였습니다.';

}else if($mode == 'member'){
	getRequestVar('memberSeq');
	getRequestVar('memberNick');
	getRequestVar('memberPwd');
	if (!empty($memberNick)) $memberNick = htmlspecialchars(str_replace('\"', '"', $memberNick), ENT_QUOTES);

	$sql = "
		UPDATE at_member_info set 
			member_nick = :member_nick
			, member_moddt = now() 
	";
	(!empty($memberPwd))?$sql.='	, member_pwd = password(:member_pwd)':'';//패스워드 유무에 따라
	$sql.='		where member_seq = :member_seq';
	$database->prepare($sql);
	$database->bind(':member_seq', $memberSeq);
	$database->bind(':member_nick', $memberNick);
	(!empty($memberPwd))?$database->bind(':member_pwd', $memberPwd):'';//패스워드 유무에 따라
	
	$database->execute();
	$redirectUrl = '../memberDetail.php';
	$resultMsg = '개인정보를 수정하였습니다.';

	$cookie = new COOKIE("MEMBER_LOGIN_COOKIE");
	$cookie_msg = "member_seq=".$loginMemberSeq;
	$cookie_msg.= "&member_id=".$loginId;
	$cookie_msg.= "&member_name=".$loginName;
	$cookie_msg.= "&member_nick=".$memberNick;
	$cookie_msg.= "&member_level=".$loginLevel;
	$cookie->setLoginCookie( $loginMemberSeq, $cookie_msg );
}

$database = null;//db connection close
pageRedirect($redirectUrl, $resultMsg);

?>