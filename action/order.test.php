<?
//initSet에서 _INCLUDE_ROOT 를 정의
include "../include/init.php";
include _INCLUDE_ROOT."init.utils.php";

getRequestVar('orderCustomer');
getRequestVar('orderMobile');
getRequestVar('orderAddress');
getRequestVar('orderMemo');
getRequestVar('orderPaymentType');
getRequestVar('orderPgAppNo');
getRequestVar('orderSeller');
getRequestVar('orderAdminMemo');

getRequestVar('goods');
getRequestVar('set');

/*
print_r($orderItam);
print_r($set);
*/

foreach($goods as $key => $val){
	echo $val["seq"]."<br>";
}
echo "<br>";
foreach($set as $key => $val){
	echo $val["seq"]."<br>";
}
/*
$goods_one_info=array();
for($i=0;count($goods)>$i;$i++){
	//$goodsGoodsSeq[$i],$goodsCategoryTitle[$i],$goodsGoodsTitle[$i],$goodsPricePrice[$i],$goodsGoodsEa[$i],$goodsPriceConsumer[$i],$goodsOrderType[$i][$goodsGoodsSeq[$i]])
	array_push($goods_one_info,array(
		$goods[$i]['seq'],$goods[$i]['price'],$goods[$i]['ea'],$goods[$i]['type'])
	);
}
*/
/*
getRequestVar('goodsGoodsSeq');
getRequestVar('goodsCategoryTitle');
getRequestVar('goodsGoodsTitle');
getRequestVar('goodsPricePrice');
getRequestVar('goodsGoodsEa');
getRequestVar('goodsPriceConsumer');
getRequestVar("goodsOrderType");

$goods_one_info=array();
for($i=0;count($goodsGoodsSeq)>$i;$i++){
	//$goodsGoodsSeq[$i],$goodsCategoryTitle[$i],$goodsGoodsTitle[$i],$goodsPricePrice[$i],$goodsGoodsEa[$i],$goodsPriceConsumer[$i],$goodsOrderType[$i][$goodsGoodsSeq[$i]])
	array_push($goods_one_info,array(
		$goodsOrderType[$goodsGoodsSeq[$i]][0])
	);
}

print_r($goods_one_info);
*/

/*start test*/
/*
getRequestVar("setGoodsEa");
getRequestVar("setGoodSeq");
getRequestVar("setCategoryTitle");
getRequestVar("setGoodsTitle");
getRequestVar("setPriceConsumer");
getRequestVar("setPricePrice");
getRequestVar("setOrderType");

print_r($setOrderType);
print_r($setGoodSeq);
/*
for($i=0;count($setGoodSeq)>$i;$i++){
	foreach($setGoodSeq as $key => $val){
		echo $setCategoryTitle[$i][$key]." / ".$setGoodsTitle[$i][$key]."</br>";
	}
}
*/

/*
$goods_set_info=array();
foreach($setGoodSeq as $i => $set){
	foreach($set as $key => $val){
		//echo "<br>//setGoodSeq//".$setGoodSeq[$i][$key]."<br>";
		//echo "<br>//setOrderType//".$setOrderType[$i][$setGoodSeq[$i][$key]][0]."<br>";
		//echo $setOrderType[$i][$setGoodSeq[$i][$key]][$key]."<br>";
		array_push($goods_set_info,array(
			$setGoodsEa[$i][$key],$setGoodSeq[$i][$key],$setCategoryTitle[$i][$key],$setGoodsTitle[$i][$key],$setPriceConsumer[$i][$key],$setPricePrice[$i][$key],$setOrderType[$i][$setGoodSeq[$i][$key]][0],$i
		));
	}
}
/*
for($i=0;count($setGoodSeq)>$i;$i++){
	foreach($setGoodSeq as $key => $val){
		//echo "<br>//setGoodSeq//".$setGoodSeq[$i][$key]."<br>";
		//echo "<br>//setOrderType//".$setOrderType[$i][$setGoodSeq[$i][$key]][0]."<br>";
		//echo $setOrderType[$i][$setGoodSeq[$i][$key]][$key]."<br>";
		array_push($goods_set_info,array(
			$setGoodsEa[$i][$key],$setGoodSeq[$i][$key],$setCategoryTitle[$i][$key],$setGoodsTitle[$i][$key],$setPriceConsumer[$i][$key],$setPricePrice[$i][$key],$setOrderType[$i][$setGoodSeq[$i][$key]][0]
		));
	}
}
*/

?>