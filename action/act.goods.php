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
/*goodsdata*/
getRequestVar('bbsName', 'goods');
getRequestVar('mode', 'mod');
getRequestVar("categorySeq");
getRequestVar("goodsTitle");
getRequestVar('goodsNameSub');
getRequestVar('goodsCode');
getRequestVar('goodsDesc');
getRequestVar('goodsModel');
getRequestVar('goodsLaunchdt');
getRequestVar('goodsOrigin');

if($mode=="mod" || $mode=="del") {
	getRequestVar("bbsSeq");
}

$remoteAddr = null;
if(!empty($_SERVER['REMOTE_ADDR'])) $remoteAddr = $_SERVER['REMOTE_ADDR'];
$httpUserAgent = null;
if(!empty($_SERVER['HTTP_USER_AGENT'])) $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];

if($mode == 'add'){
	if (!empty($goodsTitle)) $goodsTitle = htmlspecialchars(str_replace('\"', '"', $goodsTitle), ENT_QUOTES);
	if (!empty($goodsNameSub)) $goodsNameSub = htmlspecialchars(str_replace('\"', '"', $goodsNameSub), ENT_QUOTES);
	if (!empty($goodsDesc)) $goodsDesc = htmlspecialchars(str_replace('\"', '"', $goodsDesc), ENT_QUOTES);
	if (!empty($goodsModel)) $goodsModel = htmlspecialchars(str_replace('\"', '"', $goodsModel), ENT_QUOTES);
	if (!empty($goodsOrigin)) $goodsOrigin = htmlspecialchars(str_replace('\"', '"', $goodsOrigin), ENT_QUOTES);

	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "
		INSERT INTO at_goods_info (goods_title, goods_name_sub, goods_code, goods_desc, goods_model, goods_launchdt, goods_origin, goods_regdt, goods_user_agent, goods_ip, goods_status) 
		VALUES (:goods_title, :goods_name_sub, :goods_code, :goods_desc, :goods_model, :goods_launchdt, :goods_origin, NOW(), :httpUserAgent, :remoteAddr, 1)
	";
	$database->prepare($sql);
	$database->bind(':goods_title', $goodsTitle);
	$database->bind(':goods_name_sub', $goodsNameSub);
	$database->bind(':goods_code', $goodsCode);
	$database->bind(':goods_desc', $goodsDesc);
	$database->bind(':goods_model', $goodsModel);
	$database->bind(':goods_launchdt', $goodsLaunchdt);
	$database->bind(':goods_origin', $goodsOrigin);
	$database->bind(':remoteAddr', $remoteAddr);
	$database->bind(':httpUserAgent', $httpUserAgent);
	$database->execute();

	$sql = "select last_insert_id() as goods_seq ";
	$database->prepare($sql);
	if ($row = $database->dataFetch()) $bbsSeq = $row['goods_seq'];

	//속성 링크 테이블
	$sql = "
		INSERT INTO at_goods_link_info (link_category_seq, link_goods_seq) 
		VALUES (:link_category_seq, :link_goods_seq);
	";
	$database->prepare($sql);
	$database->bind(':link_category_seq', $categorySeq);
	$database->bind(':link_goods_seq', $bbsSeq);
	$database->execute();


	$redirectUrl = '../goodsDetail.php?bbsSeq='.$bbsSeq;
	$resultMsg = '게시글을 등록하였습니다.';

}elseif($mode == 'mod'){
	if (!empty($goodsTitle)) $goodsTitle = htmlspecialchars(str_replace('\"', '"', $goodsTitle), ENT_QUOTES);
	if (!empty($goodsNameSub)) $goodsNameSub = htmlspecialchars(str_replace('\"', '"', $goodsNameSub), ENT_QUOTES);
	if (!empty($goodsDesc)) $goodsDesc = htmlspecialchars(str_replace('\"', '"', $goodsDesc), ENT_QUOTES);
	if (!empty($goodsModel)) $goodsModel = htmlspecialchars(str_replace('\"', '"', $goodsModel), ENT_QUOTES);
	if (!empty($goodsOrigin)) $goodsOrigin = htmlspecialchars(str_replace('\"', '"', $goodsOrigin), ENT_QUOTES);

	$sql = "
		UPDATE at_goods_info goods 
			LEFT JOIN at_goods_link_info link on goods.goods_seq = link.link_goods_seq
		set
			goods.goods_title = :goods_title
			, goods.goods_name_sub = :goods_name_sub
			, goods.goods_code = :goods_code
			, goods.goods_desc = :goods_desc
			, goods.goods_model = :goods_model
			, goods.goods_launchdt = :goods_launchdt
			, goods.goods_origin = :goods_origin
			, goods.goods_moddt = now()
			, link.link_category_seq = :link_category_seq
	";
	$sql.= "		where goods.goods_seq = :bbsSeq ";
	$database->prepare($sql);
	$database->bind(':goods_title', $goodsTitle);
	$database->bind(':goods_name_sub', $goodsNameSub);
	$database->bind(':goods_code', $goodsCode);
	$database->bind(':goods_desc', $goodsDesc);
	$database->bind(':goods_model', $goodsModel);
	$database->bind(':goods_launchdt', $goodsLaunchdt);
	$database->bind(':goods_origin', $goodsOrigin);
	$database->bind(':link_category_seq', $categorySeq);
	$database->bind(':bbsSeq', $bbsSeq);
	$database->execute();

	$redirectUrl = '../goodsDetail.php?bbsSeq='.$bbsSeq;
	$resultMsg = '게시글을 수정하였습니다.';

}else if($mode == "del"){
	$sql = "
		SELECT COUNT(*) AS ea_count FROM at_goods_info goods
			LEFT JOIN at_goods_price_history goodsPrice ON goods.goods_seq = goodsPrice.price_goods_seq
			LEFT JOIN at_goods_set_option_info goodsSet ON goods.goods_seq = goodsSet.set_option_goods_seq
			LEFT JOIN at_order_item orderItem ON goods.goods_seq = orderItem.item_goods_seq
			LEFT JOIN at_order_trans orderTrans ON orderItem.item_seq = orderTrans.order_seq
	";
	$sqlWhere="		WHERE goods.goods_seq=:goods_seq AND (goodsPrice.price_status = 1 OR goodsSet.set_option_status = 1 OR orderTrans.order_status = 1)";
	$sql.= $sqlWhere;

	$database->prepare($sql);
	$database->bind(':goods_seq', $bbsSeq);
	if($row = $database->dataFetch()){
		$eaCount = $row['ea_count'];
	}
	if($eaCount > 0){
		$redirectUrl = '../goodsDetail.php?bbsSeq='.$bbsSeq;
		$resultMsg = '삭제가 불가능 합니다. 사용중인 상품 정보입니다.';
	}else{
		$sql = "
			UPDATE at_goods_info set
				goods_status = 9
				, goods_moddt = now()
			where goods_seq = :goods_seq
		";
		$database->prepare($sql);
		$database->bind(':goods_seq', $bbsSeq);
		$database->execute();

		$resultMsg = '게시글을 삭제하였습니다.';
	}
}

$database = null;//db connection close
pageRedirect($redirectUrl, $resultMsg);

?>