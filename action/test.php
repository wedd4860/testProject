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
	pageRedirect('../orderList.php','잘못된 접근입니다.');
}


$redirectUrl = "../orderList.php";
$resultMsg = "";

$remoteAddr = null;
if(!empty($_SERVER['REMOTE_ADDR'])) $remoteAddr = $_SERVER['REMOTE_ADDR'];
$httpUserAgent = null;
if(!empty($_SERVER['HTTP_USER_AGENT'])) $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];

if($mode == 'add'){

	//단품 셋트 시퀀스체크
	getRequestVar('goodsGoodsSeq');
	getRequestVar('goodsSetSeq');
	if(empty($goodsGoodsSeq) && empty($goodsSetSeq)){
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
	getRequestVar('orderTotGoodsprice');//총판매가
	getRequestVar('orderTotDiscount');//추가할인가
	getRequestVar('orderTotSettleprice');//결제가
	getRequestVar('orderPaymentType');//결제구분
	getRequestVar('orderPgAppNo');//승인번호
	getRequestVar('orderAdminMemo');//관리자메모

	$orderCode='123123123';

	//DB에 임시로 저장하고 BBS_SEQ 취득
	$sql = "
		INSERT INTO at_order_trans (order_code, order_fair_seq, order_customer, order_mobile, order_address, order_memo, order_admin_memo, order_member_seq, order_regdt, order_tot_discount, order_tot_orgprice, order_tot_goodsprice, order_tot_settleprice, order_type, order_payment_type, order_pgAppNo, order_status, order_user_agent, order_ip, order_seller) 
		VALUES (:order_code, :order_fair_seq, ':order_customer, :order_mobile, :order_address, :order_memo, :order_admin_memo, :order_member_seq, NOW(), :order_tot_discount, :order_tot_orgprice, :order_tot_goodsprice, :order_tot_settleprice, :order_type, :order_payment_type, :order_pgAppNo, 1, :order_user_agent, :order_ip, :order_seller)
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
	$database->bind(':order_tot_discount', $orderTotDiscount);
	$database->bind(':order_tot_orgprice', $orderTotOrgprice);
	$database->bind(':order_tot_goodsprice', $orderTotGoodsprice);
	$database->bind(':order_tot_settleprice', $orderTotSettleprice);
	$database->bind(':order_type', $orderType);
	$database->bind(':order_payment_type', $orderPaymentType);
	$database->bind(':order_pgAppNo', $orderPgAppNo);
	$database->bind(':order_user_agent', $httpUserAgent);
	$database->bind(':order_ip', $remoteAddr);
	$database->bind(':order_seller', $orderAdminMemo);
	$database->execute();

	$sql = "select last_insert_id() as order_seq ";
	$database->prepare($sql);
	if ($row = $database->dataFetch()) $bbsSeq = $row['order_seq'];
/*
	$redirectUrl = '../fairDetail.php?bbsSeq='.$bbsSeq;
	$resultMsg = '게시글을 등록하였습니다.';
*/
	//단품정보
	if(!empty($goodsGoodsSeq)){
		getRequestVar('goodsCategoryTitle');
		getRequestVar('goodsGoodsSeq');
		getRequestVar('goodsPricePrice');
		getRequestVar('goodsGoodsEa');
		getRequestVar('goodsPriceConsumer');
		getRequestVar('goodsOneColor');
		
		$goods_one_info=array();
		for($i=0;count($goodsGoodsSeq)>$i;$i++){
			array_push($goods_one_info,array(
				$goodsGoodsSeq[$i],$goodsGoodsSeq[$i],$goodsPricePrice[$i],$goodsGoodsEa[$i],$goodsPriceConsumer[$i],$goodsOneColor[$i])
			);
		}

		
		for($i=0;count($goods_one_info)>$i;$i++){
			//DB에 임시로 저장하고 BBS_SEQ 취득
			$sqlOne = "
				INSERT INTO at_order_item (item_order_seq, item_goods_seq, item_option_seq, item_goods_price, item_ea, item_gubun) 
				VALUES (:item_order_seq, :item_goods_seq, :item_option_seq, :item_goods_price, :item_ea, 1)
			";
			$database->prepare($sqlOne);
			$database->bind(':item_order_seq', $bbsSeq);
			$database->bind(':item_goods_seq', $goods_one_info[$i][0]);
			$database->bind(':item_option_seq', $goods_one_info[$i][6]);
			$database->bind(':item_goods_price', $goods_one_info[$i][3]);
			$database->bind(':item_ea', $goods_one_info[$i][4]);
			$database->execute();
		}//end for $goods_one_info
	}//end 단품정보


	//세트정보
	if(!empty($goodsSetSeq)){
		getRequestVar('goodsSetTitle');
		getRequestVar('goodsSetGoodsTitle');
		getRequestVar('goodsSetPricePrice');
		getRequestVar('goodsSetSetPrice');
		getRequestVar('goodsSetEa');
		getRequestVar('goodsSetColor');
		
		$goods_set_info=array();
		for($i=0;count($goodsSetSeq)>$i;$i++){
			array_push($goods_set_info,array(
				$goodsSetSeq[$i],$goodsSetTitle[$i],$goodsSetPricePrice[$i],$goodsSetSetPrice[$i],$goodsSetEa[$i],$goodsSetColor[$i])
			);
		}
		
		for($i=0;count($goods_set_info)>$i;$i++){
			//DB에 임시로 저장하고 BBS_SEQ 취득
			$sqlSet = "
				INSERT INTO at_order_item (item_order_seq, item_goods_seq, item_option_seq, item_goods_price, item_ea, item_gubun) 
				VALUES (:item_order_seq, :item_goods_seq, :item_option_seq, :item_goods_price, :item_ea, 1);
		

				INSERT INTO at_goods_info (goods_title, goods_name_sub, goods_code, goods_desc, goods_model, goods_launchdt, goods_origin, goods_regdt, goods_user_agent, goods_ip, goods_status) 
				VALUES (:goods_title, :goods_name_sub, :goods_code, :goods_desc, :goods_model, :goods_launchdt, :goods_origin, NOW(), :httpUserAgent, :remoteAddr, 1)
			";
			$database->prepare($sqlOne);
			$database->bind(':item_order_seq', $bbsSeq);
			$database->bind(':item_goods_seq', $goods_one_info[$i][0]);
			$database->bind(':item_option_seq', $goods_one_info[$i][5]);
			$database->bind(':item_goods_price', $goods_one_info[$i][3]);
			$database->bind(':item_ea', $goods_one_info[$i][4]);
			$database->execute();
		}//end for $goods_one_info

		print_r($goods_set_info);
		echo "<br>세트";
	}


}
echo $orderCustomer." / ".$orderMobile." / ".$orderAddress." / ".$orderMemo." / ".$orderType." / ".$orderSeller." / ".$orderTotOrgprice." / ".$orderTotGoodsprice." / ".$orderTotDiscount;
?>