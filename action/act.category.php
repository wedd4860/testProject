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
	pageRedirect('../categoryList.php','잘못된 접근입니다.');
}

$redirectUrl = "../categoryList.php";
$resultMsg = "";

/*post data*/
getRequestVar('bbsName', 'category');
getRequestVar('mode', 'mod');
getRequestVar("categoryTitle");

if($mode=="mod" || $mode=="del") {
	getRequestVar("bbsSeq");
}

$remoteAddr = null;
if(!empty($_SERVER['REMOTE_ADDR'])) $remoteAddr = $_SERVER['REMOTE_ADDR'];
$httpUserAgent = null;
if(!empty($_SERVER['HTTP_USER_AGENT'])) $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];

if($mode == 'add'){
	if (!empty($categoryTitle)) $categoryTitle = htmlspecialchars(str_replace('\"', '"', $categoryTitle), ENT_QUOTES);

	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "
		INSERT INTO at_category_info (category_title, category_regdt, category_user_agent, category_ip, category_status) 
		VALUES (:category_title, NOW(), :remoteAddr, :httpUserAgent, 1)
	";
	$database->prepare($sql);
	$database->bind(':category_title', $categoryTitle);
	$database->bind(':remoteAddr', $remoteAddr);
	$database->bind(':httpUserAgent', $httpUserAgent);
	$database->execute();

	$sql = "select last_insert_id() as category_seq ";
	$database->prepare($sql);
	if ($row = $database->dataFetch()) $bbsSeq = $row['category_seq'];

//sprintf("%03d",$bbsSeq); 3자리수로 변환
	$sql = "
			UPDATE at_category_info set
				category_code = :category_code
			where category_seq = :category_seq
	";
	$database->prepare($sql);
	$database->bind(':category_code', sprintf("%03d",$bbsSeq));
	$database->bind(':category_seq', $bbsSeq);
	$database->execute();

	$redirectUrl = '../categoryDetail.php?bbsSeq='.$bbsSeq;
	$resultMsg = '게시글을 등록하였습니다.';

}else if($mode == 'sub'){
	getRequestVar("categoryCode");
	if (!empty($categoryTitle)) $categoryTitle = htmlspecialchars(str_replace('\"', '"', $categoryTitle), ENT_QUOTES);
	if (!empty($categoryCode)) $categoryCode = htmlspecialchars(str_replace('\"', '"', $categoryCode), ENT_QUOTES);

	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "
		INSERT INTO at_category_info (category_title, category_code, category_regdt, category_user_agent, category_ip, category_status) 
		VALUES (:category_title, :category_code, NOW(), :remoteAddr, :httpUserAgent, 1)
	";
	$database->prepare($sql);
	$database->bind(':category_title', $categoryTitle);
	$database->bind(':category_code', $categoryCode);
	$database->bind(':remoteAddr', $remoteAddr);
	$database->bind(':httpUserAgent', $httpUserAgent);
	$database->execute();

	$sql = "select last_insert_id() as category_seq ";
	$database->prepare($sql);

	$redirectUrl = '../categoryDetail.php?bbsSeq='.$bbsSeq;
	$resultMsg = '게시글을 등록하였습니다.';

}else if($mode == 'mod'){	
	if (!empty($categoryTitle)) $categoryTitle = htmlspecialchars(str_replace('\"', '"', $categoryTitle), ENT_QUOTES);

	$sql = "
		UPDATE at_category_info set
			category_title = :category_title
			, category_moddt = now()
	";
	$sql.= "		where category_seq = :category_seq ";
	$database->prepare($sql);
	$database->bind(':category_title', $categoryTitle);
	$database->bind(':category_seq', $bbsSeq);

	$database->execute();
	$redirectUrl = '../categoryDetail.php?bbsSeq='.$bbsSeq;
	$resultMsg = '게시글을 수정하였습니다.';
}else if($mode == 'del'){
	$sql = "
		SELECT COUNT(*) AS ea_count FROM at_category_info cate
			LEFT JOIN at_goods_link_info link ON cate.category_seq = link.link_category_seq
			LEFT JOIN at_goods_info goods ON link.link_goods_seq = goods.goods_seq
	";
	$sqlWhere="		WHERE cate.category_seq=:category_seq AND goods.goods_status = 1";
	$sql.= $sqlWhere;

	$database->prepare($sql);
	$database->bind(':category_seq', $bbsSeq);
	if($row = $database->dataFetch()){
		$eaCount = $row['ea_count'];
	}
	if($eaCount > 0){
		$redirectUrl = '../categoryDetail.php?bbsSeq='.$bbsSeq;
		$resultMsg = '삭제가 불가능 합니다. 사용중인 카테고리 정보입니다.';
	}else{
		$sql = "
			UPDATE at_category_info set
				category_status = 9
				, category_moddt = now()
			where category_seq = :category_seq
		";
		$database->prepare($sql);
		$database->bind(':category_seq', $bbsSeq);
		$database->execute();

		$resultMsg = '게시글을 삭제하였습니다.';
	}

}

$database = null;//db connection close
pageRedirect($redirectUrl, $resultMsg);

?>