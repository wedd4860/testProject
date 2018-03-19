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
$resultMsg = "";

/*post data*/
getRequestVar('bbsName', 'goodsPriceHistory');
getRequestVar('mode', 'mod');
getRequestVar("priceGoodsSeq");
getRequestVar("priceFairSeq");
getRequestVar("priceGoodsConsumer");
getRequestVar("priceGoodsPrice");
getRequestVar("priceGoodsSupply");

if($mode=="mod" || $mode=="del") {
	getRequestVar("bbsSeq");
}

$remoteAddr = null;
if(!empty($_SERVER['REMOTE_ADDR'])) $remoteAddr = $_SERVER['REMOTE_ADDR'];
$httpUserAgent = null;
if(!empty($_SERVER['HTTP_USER_AGENT'])) $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];

if($mode == 'add'){
	//if (!empty($fairTitle)) $fairTitle = htmlspecialchars(str_replace('\"', '"', $fairTitle), ENT_QUOTES);

	/* 2018-01-05 결제가 삭제 요청 :: 요청자 : 박진경
	$sql = "
		INSERT INTO at_goods_price_history (price_fair_seq, price_goods_seq, price_goods_consumer, price_goods_price, price_goods_supply, price_regdt, price_user_agent, price_ip, price_status) 
		VALUES (:price_fair_seq, :price_goods_seq, :price_goods_consumer, :price_goods_price, :price_goods_supply, NOW(), :httpUserAgent, :remoteAddr, 1)
	";
	*/
	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "
		INSERT INTO at_goods_price_history (price_fair_seq, price_goods_seq, price_goods_consumer, price_goods_supply, price_regdt, price_user_agent, price_ip, price_status) 
		VALUES (:price_fair_seq, :price_goods_seq, :price_goods_consumer, :price_goods_supply, NOW(), :httpUserAgent, :remoteAddr, 1)
	";
	$database->prepare($sql);
	$database->bind(':price_fair_seq', $priceFairSeq);
	$database->bind(':price_goods_seq', $priceGoodsSeq);
	$database->bind(':price_goods_consumer', $priceGoodsConsumer);
	$database->bind(':price_goods_supply', $priceGoodsSupply);
	$database->bind(':remoteAddr', $remoteAddr);
	$database->bind(':httpUserAgent', $httpUserAgent);
	$database->execute();

	$sql = "select last_insert_id() as price_seq ";
	$database->prepare($sql);
	if ($row = $database->dataFetch()) $bbsSeq = $row['price_seq'];

	$redirectUrl = '../goodsDetail.php?bbsSeq='.$priceGoodsSeq;
	$resultMsg = '상품가격을 등록하였습니다.';

}else if($mode == 'mod'){
	//if (!empty($fairTitle)) $fairTitle = htmlspecialchars(str_replace('\"', '"', $fairTitle), ENT_QUOTES);
	
	/* 2018-01-05 결제가 삭제 요청 :: 요청자 : 박진경
	$sql = "
		UPDATE at_goods_price_history set
			price_goods_consumer = :price_goods_consumer
			, price_goods_price = :price_goods_price
			, price_goods_supply = :price_goods_supply
			, price_moddt = now()
	";
	*/
	$sql = "
		UPDATE at_goods_price_history set
			price_goods_consumer = :price_goods_consumer
			, price_goods_supply = :price_goods_supply
			, price_moddt = now()
	";
	$sql.= "		where price_seq = :price_seq ";
	$database->prepare($sql);
	$database->bind(':price_goods_consumer', $priceGoodsConsumer);
	$database->bind(':price_goods_supply', $priceGoodsSupply);
	$database->bind(':price_seq', $bbsSeq);
	$database->execute();

	$redirectUrl = '../goodsDetail.php?bbsSeq='.$priceGoodsSeq;
	$resultMsg = '상품가격을 수정하였습니다.';

}elseif($mode == 'del'){
	$sql = "
		UPDATE at_goods_price_history set
			price_status = 9
			, price_moddt = now()
		where price_seq = :price_seq
	";
	$database->prepare($sql);
	$database->bind(':price_seq', $bbsSeq);
	$database->execute();

	$redirectUrl = '../goodsDetail.php?bbsSeq='.$priceGoodsSeq;
	$resultMsg = '상품가격을 삭제하였습니다.';
}

$database = null;//db connection close
pageRedirect($redirectUrl, $resultMsg);

?>