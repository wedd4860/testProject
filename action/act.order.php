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
	pageRedirect('../orderList.php','잘못된 접근입니다.');
}

$redirectUrl = "../orderList.php";
$resultMsg = "";

/*post data*/
getRequestVar('mode');

if($mode=="mod" || $mode=="memo" || $mode=="cancel" || $mode=="del") {
	getRequestVar("bbsSeq");
}

$remoteAddr = null;
if(!empty($_SERVER['REMOTE_ADDR'])) $remoteAddr = $_SERVER['REMOTE_ADDR'];
$httpUserAgent = null;
if(!empty($_SERVER['HTTP_USER_AGENT'])) $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];

if($mode == 'add'){
	//단품 셋트 시퀀스체크
	getRequestVar('goodsGoodsSeq');
	getRequestVar('setGoodSeq');
	if(empty($goodsGoodsSeq) and empty($setGoodSeq)){
		//상품이 없을때
		$database = null;//db connection close
		pageRedirect('../orderList.php','잘못된 접근입니다.');
	}

	//주문정보
	getRequestVar('fairSeq');//전시회시퀀스
	getRequestVar('orderCustomer');//고객이름
	getRequestVar('orderMobile');//휴대폰
	$orderMobile=str_replace('_','',$orderMobile);
	getRequestVar('orderAddress');//주소
	getRequestVar('orderMemo');//고객메모
	getRequestVar('orderType');//판매구분
	getRequestVar('orderSeller');//판매직원
	getRequestVar('orderTotOrgprice');//총정가
	/*
	getRequestVar('orderTotGoodsprice');//총판매가
	getRequestVar('orderTotDiscount');//추가할인가
	*/
	getRequestVar('orderTotSettleprice');//결제가
	getRequestVar('orderPaymentType');//결제구분
	getRequestVar('orderPgAppNo');//승인번호
	getRequestVar('orderAdminMemo');//관리자메모

	$orderCode=getMillisecond();

	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "
		INSERT INTO at_order_trans (order_code, order_fair_seq, order_customer, order_mobile, order_address, order_memo, order_admin_memo, order_member_seq, order_regdt, order_tot_orgprice, order_tot_settleprice, order_payment_type, order_pgAppNo, order_status, order_user_agent, order_ip, order_seller) 
		VALUES (:order_code, :order_fair_seq, :order_customer, :order_mobile, :order_address, :order_memo, :order_admin_memo, :order_member_seq, NOW(), :order_tot_orgprice, :order_tot_settleprice, :order_payment_type,  :order_pgAppNo, 1, :order_user_agent, :order_ip, :order_seller)
	";
	$database->prepare($sql);
	$database->bind(':order_code', $orderCode);
	$database->bind(':order_fair_seq', $fairSeq);
	$database->bind(':order_customer', $orderCustomer);
	$database->bind(':order_mobile', $orderMobile);
	$database->bind(':order_address', $orderAddress);
	$database->bind(':order_memo', $orderMemo);
	$database->bind(':order_admin_memo', $orderAdminMemo);
	$database->bind(':order_member_seq', $loginMemberSeq);
	//$database->bind(':order_tot_discount', $orderTotDiscount);
	$database->bind(':order_tot_orgprice', $orderTotOrgprice);
	//$database->bind(':order_tot_goodsprice', $orderTotGoodsprice);
	$database->bind(':order_tot_settleprice', $orderTotSettleprice);
	//$database->bind(':order_type', $orderType);
	$database->bind(':order_payment_type', $orderPaymentType);
	$database->bind(':order_pgAppNo', $orderPgAppNo);
	$database->bind(':order_user_agent', $httpUserAgent);
	$database->bind(':order_ip', $remoteAddr);
	$database->bind(':order_seller', $orderSeller);
	$database->execute();

	$sql = "select last_insert_id() as order_seq ";
	$database->prepare($sql);
	if ($row = $database->dataFetch()) $bbsSeq = $row['order_seq'];

	//단품정보
	if(!empty($goodsGoodsSeq)){
		getRequestVar('goodsCategoryTitle');
		getRequestVar('goodsGoodsTitle');
		getRequestVar('goodsPricePrice');
		getRequestVar('goodsGoodsEa');
		getRequestVar('goodsPriceConsumer');
		getRequestVar("goodsOrderType");
		
		$goods_one_info=array();
		for($i=0;count($goodsGoodsSeq)>$i;$i++){
			array_push($goods_one_info,array(
				$goodsGoodsSeq[$i]
				,$goodsPricePrice[$i]
				,$goodsGoodsEa[$i]
				,$goodsPriceConsumer[$i]
				,$goodsOrderType[$goodsGoodsSeq[$i]][0]
				)
			);
		}
		for($i=0;count($goods_one_info)>$i;$i++){
			//DB에 임시로 저장하고 BBS_SEQ 취득
			$sqlOne = "
				INSERT INTO at_order_item (item_order_seq, item_goods_seq, item_goods_price, item_goods_consumer, item_ea, item_gubun, item_order_type) 
				VALUES (:item_order_seq, :item_goods_seq, :item_goods_price, :item_goods_consumer, :item_ea, 1, :item_order_type)
			";
		
			$database->prepare($sqlOne);
			$database->bind(':item_order_seq', $bbsSeq);
			$database->bind(':item_goods_seq', $goods_one_info[$i][0]);
			$database->bind(':item_goods_price', $goods_one_info[$i][1]);
			$database->bind(':item_goods_consumer', $goods_one_info[$i][3]);
			$database->bind(':item_ea', $goods_one_info[$i][2]);
			$database->bind(':item_order_type', $goods_one_info[$i][4]);
			$database->execute();
		}//end for $goods_one_info
	}//end 단품정보


	//세트정보
	if(!empty($setGoodSeq)){
		getRequestVar("setGoodsEa");
		getRequestVar("setCategoryTitle");
		getRequestVar("setGoodsTitle");
		getRequestVar("setPriceConsumer");
		getRequestVar("setPricePrice");
		getRequestVar("setOrderType");
		
		$goods_set_info=array();
		foreach($setGoodSeq as $i => $set){
			foreach($set as $key => $val){
				array_push($goods_set_info,array(
					$setGoodSeq[$i][$key]
					,$setPricePrice[$i][$key]
					,$setPriceConsumer[$i][$key]
					,$setGoodsEa[$i][$key]
					,$setOrderType[$i][$setGoodSeq[$i][$key]][0]
					,$i
				));
			}
		}
		
		for($i=0;count($goods_set_info)>$i;$i++){
			//DB에 임시로 저장하고 BBS_SEQ 취득
			$sqlSet = "
				INSERT INTO at_order_item (item_order_seq, item_goods_seq, item_goods_price, item_goods_consumer, item_ea, item_gubun, item_set_seq, item_order_type) 
				VALUES (:item_order_seq, :item_goods_seq, :item_goods_price, :item_goods_consumer, :item_ea, 2, :item_set_seq, :item_order_type)
			";
			$database->prepare($sqlSet);
			$database->bind(':item_order_seq', $bbsSeq);
			$database->bind(':item_goods_seq', $goods_set_info[$i][0]);
			$database->bind(':item_goods_price', $goods_set_info[$i][1]);
			$database->bind(':item_goods_consumer', $goods_set_info[$i][2]);
			$database->bind(':item_ea', $goods_set_info[$i][3]);
			$database->bind(':item_set_seq', $goods_set_info[$i][5]);
			$database->bind(':item_order_type', $goods_set_info[$i][4]);
			$database->execute();
		}//end for $goods_set_info
	}//end 세트정보	
	
	$redirectUrl = '../orderList.php';
	$resultMsg = '주문을 완료하였습니다.';

}else if($mode == 'mod'){
	getRequestVar('orderCustomer');
	getRequestVar('orderMobile');
	getRequestVar('orderAddress');
	getRequestVar('orderMemo');
	getRequestVar('orderPaymentType');
	getRequestVar('orderPgAppNo');
	getRequestVar('orderSeller');
	getRequestVar('orderAdminMemo');
	getRequestVar('orderTotOrgprice');
	getRequestVar('orderTotSettleprice');

	getRequestVar('goods');
	getRequestVar('set');

	if (!empty($orderCustomer)) $orderCustomer = htmlspecialchars(str_replace('\"', '"', $orderCustomer), ENT_QUOTES);
	if (!empty($orderAddress)) $orderAddress = htmlspecialchars(str_replace('\"', '"', $orderAddress), ENT_QUOTES);
	if (!empty($orderMemo)) $orderMemo = htmlspecialchars(str_replace('\"', '"', $orderMemo), ENT_QUOTES); 
	if (!empty($orderSeller)) $orderSeller = htmlspecialchars(str_replace('\"', '"', $orderSeller), ENT_QUOTES);
	if (!empty($orderAdminMemo)) $orderAdminMemo = htmlspecialchars(str_replace('\"', '"', $orderAdminMemo), ENT_QUOTES);

	if(count($goods) > 0){
		foreach($goods as $key => $val){
			$sql = "
				UPDATE at_order_item 
				set
					item_goods_price = :item_goods_price
					, item_ea = :item_ea
					, item_order_type = :item_order_type
			";
			$sql.= "		where item_seq = :item_seq";
			$database->prepare($sql);
			$database->bind(':item_seq', $val["seq"]);
			$database->bind(':item_goods_price', $val["price"]);
			$database->bind(':item_ea', $val["ea"]);
			$database->bind(':item_order_type', $val["type"]);
			$database->execute();
		}
	}
	
	if(count($set) > 0){
		foreach($set as $key => $val){
			$sql = "
				UPDATE at_order_item 
				set
					item_goods_price = :item_goods_price
					, item_order_type = :item_order_type
			";
			$sql.= "		where item_seq = :item_seq";
			$database->prepare($sql);
			$database->bind(':item_seq', $val["seq"]);
			$database->bind(':item_goods_price', $val["price"]);
			$database->bind(':item_order_type', $val["type"]);
			$database->execute();
		}
	}

	$sql = "
		UPDATE at_order_trans 
		set
			order_customer = :order_customer
			, order_mobile = :order_mobile
			, order_address = :order_address
			, order_memo = :order_memo
			, order_admin_memo = :order_admin_memo
			, order_payment_type = :order_payment_type
			, order_tot_goodsprice = :order_tot_goodsprice
			, order_tot_settleprice = :order_tot_settleprice
			, order_pgAppNo = :order_pgAppNo
			, order_seller = :order_seller
			, order_moddt = now()
	";
	$sql.= "		where order_seq = :order_seq ";
	$database->prepare($sql);
	$database->bind(':order_seq', $bbsSeq);
	$database->bind(':order_customer', $orderCustomer);
	$database->bind(':order_mobile', $orderMobile);
	$database->bind(':order_address', $orderAddress);
	$database->bind(':order_memo', $orderMemo);
	$database->bind(':order_admin_memo', $orderAdminMemo);
	$database->bind(':order_payment_type', $orderPaymentType);
	$database->bind(':order_tot_goodsprice', $orderTotOrgprice);
	$database->bind(':order_tot_settleprice', $orderTotSettleprice);
	$database->bind(':order_pgAppNo', $orderPgAppNo);
	$database->bind(':order_seller', $orderSeller);
	$database->execute();

	$redirectUrl = '../orderSettle.php?bbsSeq='.$bbsSeq;
	$resultMsg = '주문을 수정하였습니다.';

}else if($mode == 'memo'){

	getRequestVar('orderAdminMemo');
	if (!empty($orderAdminMemo)) $orderAdminMemo = htmlspecialchars(str_replace('\"', '"', $orderAdminMemo), ENT_QUOTES);

	$sql = "
		UPDATE at_order_trans 
		set
			order_admin_memo = :order_admin_memo
			, order_moddt = now()
	";
	$sql.= "		where order_seq = :order_seq ";
	$database->prepare($sql);
	$database->bind(':order_seq', $bbsSeq);
	$database->bind(':order_admin_memo', $orderAdminMemo);
	$database->execute();

	$redirectUrl = '../orderSettle.php?bbsSeq='.$bbsSeq;
	$resultMsg = '메모를 저장하였습니다.';

}else if($mode == 'cancel'){
	$sql = "
		UPDATE at_order_trans set
			order_canceldt = now()
			, order_status = 2
	";
	$sql.= "		where order_seq = :order_seq ";
	$database->prepare($sql);
	$database->bind(':order_seq', $bbsSeq);
	$database->execute();

	$redirectUrl = '../orderSettle.php?bbsSeq='.$bbsSeq;
	$resultMsg = '주문을 취소하였습니다.';

}else if($mode == 'del'){
	$sql = "
		UPDATE at_order_trans set
			order_moddt = now()
			, order_status = 9
	";
	$sql.= "		where order_seq = :order_seq ";
	$database->prepare($sql);
	$database->bind(':order_seq', $bbsSeq);
	$database->execute();

	$redirectUrl = '../orderList.php';
	$resultMsg = '주문을 삭제하였습니다.';
}

$database = null;//db connection close
pageRedirect($redirectUrl, $resultMsg);

?>