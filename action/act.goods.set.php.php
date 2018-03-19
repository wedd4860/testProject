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
}else{
	$database = null;//db connection close
	pageRedirect('../goodsSetList.php','잘못된 접근입니다.');
}

$redirectUrl = "../goodsSetList.php";
$resultMsg = "";

/*post data*/
getRequestVar('bbsName', 'set');
getRequestVar('mode', 'mod');
getRequestVar("setFairSeq");
getRequestVar("setTitle");
getRequestVar('setGoodsCnt');

if($mode=="mod" || $mode=="del") {
	getRequestVar("bbsSeq");
}

$remoteAddr = null;
if(!empty($_SERVER['REMOTE_ADDR'])) $remoteAddr = $_SERVER['REMOTE_ADDR'];
$httpUserAgent = null;
if(!empty($_SERVER['HTTP_USER_AGENT'])) $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];

if($mode == 'add'){
	if (!empty($setTitle)) $setTitle = htmlspecialchars(str_replace('\"', '"', $setTitle), ENT_QUOTES);

	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "

		INSERT INTO at_goods_set_info (set_fair_seq, set_title, set_goods_cnt, set_regdt, set_user_agent, set_ip, set_status) VALUES (:set_fair_seq, :set_title, :set_goods_cnt, NOW(), :httpUserAgent, :remoteAddr, 1);
	";

	$database->prepare($sql);
	$database->bind(':set_fair_seq', $setFairSeq);
	$database->bind(':set_title', $setTitle);
	$database->bind(':set_goods_cnt', $setGoodsCnt);
	$database->bind(':remoteAddr', $remoteAddr);
	$database->bind(':httpUserAgent', $httpUserAgent);
	$database->execute();

	$sql = "select last_insert_id() as set_seq ";
	$database->prepare($sql);
	if ($row = $database->dataFetch()) $bbsSeq = $row['set_seq'];

	$redirectUrl = '../goodsSetDetail.php?bbsSeq='.$bbsSeq;
	$resultMsg = '게시글을 등록하였습니다.';

}elseif($mode == 'mod'){
	if (!empty($setTitle)) $setTitle = htmlspecialchars(str_replace('\"', '"', $setTitle), ENT_QUOTES);
	if (!empty($goodsNameSub)) $goodsNameSub = htmlspecialchars(str_replace('\"', '"', $goodsNameSub), ENT_QUOTES);
	if (!empty($goodsDesc)) $goodsDesc = htmlspecialchars(str_replace('\"', '"', $goodsDesc), ENT_QUOTES);
	if (!empty($goodsModel)) $goodsModel = htmlspecialchars(str_replace('\"', '"', $goodsModel), ENT_QUOTES);
	if (!empty($goodsOrigin)) $goodsOrigin = htmlspecialchars(str_replace('\"', '"', $goodsOrigin), ENT_QUOTES);

	$sql = "
		UPDATE at_goods_set_info
		set
			set_title = :set_title
			, set_goods_cnt = :set_goods_cnt
			, set_moddt = now()
	";
	$sql.= "		where set_seq = :bbsSeq ";
	$database->prepare($sql);
	$database->bind(':bbsSeq', $bbsSeq);
	$database->bind(':set_title', $setTitle);
	$database->bind(':set_goods_cnt', $setGoodsCnt);
	$database->execute();

	$redirectUrl = '../goodsSetDetail.php?bbsSeq='.$bbsSeq;
	$resultMsg = '게시글을 수정하였습니다.';

}else if($mode == "del"){
	$countSql = "
		SELECT COUNT(*) as bbs_count FROM at_goods_set_option_info
			WHERE set_option_set_seq = :set_seq AND set_option_status = 1
	";
	$database->prepare($countSql);
	$database->bind(':set_seq', $bbsSeq);
	if($row = $database->dataFetch()){
		$totalCount = $row['bbs_count'];
	}
	if($totalCount > 0){
		$sql = "
		UPDATE at_goods_set_info setInfo
			LEFT JOIN at_goods_set_option_info setOption ON setInfo.set_seq = setOption.set_option_set_seq
		SET
			setInfo.set_status = 9
			, setInfo.set_moddt = now()
			, setOption.set_option_status = 9
		";
		$sql.= "		where setInfo.set_seq = :set_seq ";

		$database->prepare($sql);
		$database->bind(':set_seq', $bbsSeq);
		$database->execute();
	}else{
		$sql = "
		UPDATE at_goods_set_info setInfo SET
			setInfo.set_status = 9
			, setInfo.set_moddt = now()
		";
		$sql.= "		where setInfo.set_seq = :set_seq ";

		$database->prepare($sql);
		$database->bind(':set_seq', $bbsSeq);
		$database->execute();
	}

	$resultMsg = '게시글을 삭제하였습니다.';





	
}

$database = null;//db connection close
pageRedirect($redirectUrl, $resultMsg);

?>