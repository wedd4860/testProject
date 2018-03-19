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
		pageRedirect('login.php');
	}
	$loginMemberSeq = $LOGIN_INFO->getMemberSeq();
	$loginId = $LOGIN_INFO->getMemberId();
	$loginName = $LOGIN_INFO->getMemberName();
	$loginNickname = $LOGIN_INFO->getMemberNick();
}else{
	$database = null;//db connection close
	pageRedirect('../goodsSetList.php','잘못된 접근입니다.');
}

$redirectUrl = "../goodsSetList.php";
$resultMsg = "";

/*post data*/
getRequestVar('bbsName', 'setOption');
getRequestVar('mode', 'mod');
getRequestVar("setOptionPercent");
getRequestVar("setSeq");

if($mode=="mod" || $mode=="del") {
	getRequestVar("bbsSeq");
}

if($mode == 'add'){
	getRequestVar("setOptionGoodsSeq");
	//if (!empty($setTitle)) $setTitle = htmlspecialchars(str_replace('\"', '"', $setTitle), ENT_QUOTES);
	$sql = "
		INSERT INTO at_goods_set_option_info (set_option_set_seq, set_option_goods_seq, set_option_percent, set_option_status) 
		VALUES (:set_option_set_seq, :set_option_goods_seq, :set_option_percent, 1);
	";

	$database->prepare($sql);
	$database->bind(':set_option_set_seq', $setSeq);
	$database->bind(':set_option_goods_seq', $setOptionGoodsSeq);
	$database->bind(':set_option_percent', $setOptionPercent);
	$database->bind(':set_option_set_seq', $setSeq);

	$database->execute();
	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "select last_insert_id() as set_option_seq ";
	$database->prepare($sql);
	if ($row = $database->dataFetch()) $bbsSeq = $row['set_option_seq'];

	$redirectUrl = '../goodsSetDetail.php?bbsSeq='.$setSeq;
	$resultMsg = '게시글을 등록하였습니다.';

}else if($mode == 'mod'){
	//if (!empty($goodsTitle)) $goodsTitle = htmlspecialchars(str_replace('\"', '"', $goodsTitle), ENT_QUOTES);

	$sql = "
		UPDATE at_goods_set_option_info set
			set_option_percent = :set_option_percent
	";
	$sql.= "		where set_option_seq = :set_option_seq ";
	$database->prepare($sql);
	$database->bind(':set_option_percent', $setOptionPercent);
	$database->bind(':set_option_seq', $bbsSeq);
	$database->execute();

	$redirectUrl = '../goodsSetDetail.php?bbsSeq='.$setSeq;
	$resultMsg = '게시글을 수정하였습니다.';

}else if($mode == 'del'){
	$sql = "
		UPDATE at_goods_set_option_info set
			set_option_status = 9
		where set_option_seq = :set_option_seq
	";
	$database->prepare($sql);
	$database->bind(':set_option_seq', $bbsSeq);
	$database->execute();

	$resultMsg = '게시글을 삭제하였습니다.';
}

$database = null;//db connection close
pageRedirect($redirectUrl, $resultMsg);

?>