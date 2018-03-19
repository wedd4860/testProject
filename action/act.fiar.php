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
	pageRedirect('../fairList.php','잘못된 접근입니다.');
}

$redirectUrl = "../fairList.php";
$resultMsg = "";

/*post data*/
getRequestVar('bbsName', 'fiar');
getRequestVar('mode', 'mod');
getRequestVar("fairTitle");
getRequestVar("dateRange");
if($mode=="mod" || $mode=="del") {
	getRequestVar("bbsSeq");
}

$dateStr=explode(" ~ ",$dateRange);
$std_date=str_replace('-','',$dateStr[0]);
$end_date=str_replace('-','',$dateStr[1]);
/* date YYYY-mm-dd 형태로 복구
$date = date("Y-m-d", strtotime($std_date));
*/

$remoteAddr = null;
if(!empty($_SERVER['REMOTE_ADDR'])) $remoteAddr = $_SERVER['REMOTE_ADDR'];
$httpUserAgent = null;
if(!empty($_SERVER['HTTP_USER_AGENT'])) $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];

if($mode == 'add'){
	if (!empty($fairTitle)) $fairTitle = htmlspecialchars(str_replace('\"', '"', $fairTitle), ENT_QUOTES);
	if (!empty($std_date)) $std_date = htmlspecialchars(str_replace('\"', '"', $std_date), ENT_QUOTES);
	if (!empty($end_date)) $end_date = htmlspecialchars(str_replace('\"', '"', $end_date), ENT_QUOTES);

	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "
		INSERT INTO at_fair_info (fair_title, fair_sdt, fair_edt, fair_regdt, fair_user_agent, fair_ip, fair_status) 
		VALUES (:fair_title, :std_date, :end_date, NOW(), :remoteAddr, :httpUserAgent, 1)
	";
	$database->prepare($sql);
	$database->bind(':fair_title', $fairTitle);
	$database->bind(':std_date', $std_date);
	$database->bind(':end_date', $end_date);
	$database->bind(':remoteAddr', $remoteAddr);
	$database->bind(':httpUserAgent', $httpUserAgent);
	$database->execute();

	$sql = "select last_insert_id() as fiar_seq ";
	$database->prepare($sql);
	if ($row = $database->dataFetch()) $bbsSeq = $row['fiar_seq'];

	$redirectUrl = '../fairDetail.php?bbsSeq='.$bbsSeq;
	$resultMsg = '게시글을 등록하였습니다.';

}else if($mode == 'mod'){
	if (!empty($fairTitle)) $fairTitle = htmlspecialchars(str_replace('\"', '"', $fairTitle), ENT_QUOTES);
	if (!empty($std_date)) $std_date = htmlspecialchars(str_replace('\"', '"', $std_date), ENT_QUOTES);
	if (!empty($end_date)) $end_date = htmlspecialchars(str_replace('\"', '"', $end_date), ENT_QUOTES);

	$sql = "
		UPDATE at_fair_info set
			fair_title = :fair_title
			, fair_sdt = :std_date
			, fair_edt = :end_date
			, fair_moddt = now()
	";
	$sql.= "		where fair_seq = :fair_seq ";
	$database->prepare($sql);
	$database->bind(':fair_title', $fairTitle);
	$database->bind(':std_date', $std_date);
	$database->bind(':end_date', $end_date);
	$database->bind(':fair_seq', $bbsSeq);
	$database->execute();

	$redirectUrl = '../fairDetail.php?bbsSeq='.$bbsSeq;
	$resultMsg = '게시글을 수정하였습니다.';

}else if($mode == 'del'){
	$sql = "
		SELECT COUNT(*) AS ea_count FROM at_fair_info fair
			LEFT JOIN at_order_trans orderTrans ON fair.fair_seq = orderTrans.order_fair_seq
			LEFT JOIN at_goods_set_info setInfo ON fair.fair_seq = setInfo.set_fair_seq
			LEFT JOIN at_goods_price_history priceHistory ON fair.fair_seq = priceHistory.price_fair_seq
	";
	$sqlWhere="		WHERE (orderTrans.order_status = 1 OR setInfo.set_status = 1 OR priceHistory.price_status = 1) AND fair.fair_seq=:fair_seq";
	$sql.= $sqlWhere;

	$database->prepare($sql);
	$database->bind(':fair_seq', $bbsSeq);
	if($row = $database->dataFetch()){
		$eaCount = $row['ea_count'];
	}
	if($eaCount > 0){
		$redirectUrl = '../fairDetail.php?bbsSeq='.$bbsSeq;
		$resultMsg = '삭제가 불가능 합니다. 사용중인 전시회 정보입니다.';
	}else{
		$sql = "
			UPDATE at_fair_info set
				fair_status = 9
				, fair_moddt = now()
			where fair_seq = :fair_seq
		";
		$database->prepare($sql);
		$database->bind(':fair_seq', $bbsSeq);
		$database->execute();

		$resultMsg = '게시글을 삭제하였습니다.';
	}
}

$database = null;//db connection close
pageRedirect($redirectUrl, $resultMsg);

?>