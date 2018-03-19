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
	pageRedirect('../goodsList.php','잘못된 접근입니다.');
}

$redirectUrl = "../goodsList.php";
$resultMsg="";
$result = array();

/*post data*/
getRequestVar('optionBbsName', 'goodsOption');
getRequestVar('optionMode', 'mod');
getRequestVar("optionBbsSeq");
getRequestVar("optionGoodsSeq");
getRequestVar("optionColorTitle");
getRequestVar("optionColorCode");

if($optionMode == 'add'){
	if(empty($optionGoodsSeq) || empty($optionColorTitle)){
		$result['success']=false;
		echo json_encode($result);
		return false;
	}


	if (!empty($optionColorTitle)) $optionColorTitle = htmlspecialchars(str_replace('\"', '"', $optionColorTitle), ENT_QUOTES);
	if (!empty($optionColorCode)) $optionColorCode = htmlspecialchars(str_replace('\"', '"', $optionColorCode), ENT_QUOTES);

	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "
		INSERT INTO at_goods_option_info (option_goods_seq, option_color_title, option_color_code, option_status) 
		VALUES (:option_goods_seq, :option_color_title, :option_color_code, 1)
	";
	$database->prepare($sql);
	$database->bind(':option_goods_seq', $optionGoodsSeq);
	$database->bind(':option_color_title', $optionColorTitle);
	$database->bind(':option_color_code', $optionColorCode);
	$database->execute();
	
	$sql = "select last_insert_id() as option_goods_seq ";
	$database->prepare($sql);
	if ($row = $database->dataFetch()) $bbsSeq = $row['option_goods_seq'];

	$result['optionColorTitle']=$optionColorTitle;
	$result['optionColorCode']=$optionColorCode;
	$result['colorseq']=$bbsSeq;

}elseif($optionMode == 'del'){
	if(empty($optionBbsSeq)){
		$result['success']=false;
		echo json_encode($result);
		return false;
	}

	$sql = "
		UPDATE at_goods_option_info set
			option_status = 9
		where option_seq = :option_seq
	";
	$database->prepare($sql);
	$database->bind(':option_seq', $optionBbsSeq);
	$database->execute();

	$result['colorseq']=$optionBbsSeq;
}

$database = null;//db connection close

$result['success']=true;
echo json_encode($result);

?>