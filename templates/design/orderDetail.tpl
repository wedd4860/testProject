{include file="header.tpl"}
{include file="nav.tpl"}
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h2>
			{$pageTitle}
			<small>{$pageSubTitle}</small>
		</h2>
		<ol class="breadcrumb">
			<li><a href="orderList.php"><i class="fa fa-krw"></i>주문</a></li>
			<li class="active">주문서 작성</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">

		<div class="row">
			<!-- left column -->
			<div class="col-md-12">

				<!-- form start -->
				<form role="form" action="./action/act.order.php" method="post" id="data_form">
				<input type="hidden" name="page_type" value="admin" />
				<input type="hidden" id="mode" name="mode" value="{$mode}" />
				<input type="hidden" id="fairSeq" name="fairSeq" value="{$currentFairSeq}" />

<div id="tempDisplay">

				<!-- SELECT2 EXAMPLE -->
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">단품정보</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<select name="categorySeq" id="categorySeq" class="form-control select2-one" style="width: 100%;">
										<option value="">상품을 선택해 주세요</option>
									{foreach $bbsGoods as $key => $val}
										<option data-goodsInfo='{$val|@json_encode}' value="{$val.goods_seq}">[{$val.category_title}] {$val.goods_title} , {$val.goods_model}</option>
									{/foreach}
									</select>
								</div>

								<div class="custom-marginTop-table custom-order-table" id="customOrderTableOne">
									<table class="table table-bordered table-striped">
										<colgroup>
											<col style="width:5%" />
											<col style="width:15%" />
											<col style="width:20%" />
											<col style="width:15%" />
											<col style="width:15%" />
											<col style="width:10%" />
											<col style="width:15%" />
											<col style="width:5%" />
										</colgroup>
										<thead>
											<tr class="odd">
												<th class="custom-table-textAlign">SEQ</th>
												<th class="custom-table-textAlign">카테고리명</th>
												<th class="custom-table-textAlign">제품이름</th>
												<th class="custom-table-textAlign">정가</th>
												<th class="custom-table-textAlign">결제가</th>
												<th class="custom-table-textAlign">상품수량</th>
												<th class="custom-table-textAlign">판매구분</th>
												<th class="custom-table-textAlign">삭제</th>
											</tr>
										</thead>
										<tbody class="select-one-tbody custom-table-textAlign"></tbody>
									</table>
								</div>
							</div><!--col-sm-12-->
						</div>
						<!-- /.row -->
					</div>
				</div>
				<!-- /.box -->

</div><!--tempDisplay-->


				<div class="row tempAdd">
					<div class="col-md-12">
						<button id="tempBtn" type="button" class="btn btn-block btn-warning">세트상품 추가</button>
					</div>
				</div>
				<!-- /.box -->

				<div class="row">
					<div class="col-md-6">
						<!-- general form elements -->
						<div class="box custom-box-list">
							<div class="box-header with-border">
							  <h3 class="box-title">주문정보</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-body form-horizontal">

								<div class="form-group">
									<label for="orderCustomer" class="col-sm-3 control-label"><i class="fa fa-check custom-input-title"></i> 고객이름</label>
									<div class="col-sm-9">
										<input name="orderCustomer" type="text" class="form-control event-enter" id="orderCustomer" placeholder="고객이름" >
									</div>
								</div>
								<!-- /.form group -->

								<div class="form-group">
									<label for="orderMobile" class="col-sm-3 control-label"><i class="fa fa-check custom-input-title"></i> 휴대폰</label>
									<div class="col-sm-9">
										<input name="orderMobile" type="text" class="form-control event-enter" id="orderMobile" placeholder="휴대폰" data-inputmask='"mask": "999-9999-9999"' data-mask >
									</div>
								</div>
								<!-- /.form group -->

								<div class="form-group">
									<label for="orderAddress" class="col-sm-3 control-label">주소</label>
									<div class="col-sm-9">
										<input name="orderAddress" type="text" class="form-control event-enter" id="orderAddress" placeholder="주소" >
									</div>
								</div>
								<!-- /.form group -->

								<div class="form-group">
									<label for="orderMemo" class="col-sm-3 control-label">고객메모</label>
									<div class="col-sm-9">
										<input name="orderMemo" type="text" class="form-control event-enter" id="orderMemo" placeholder="고객메모" >
									</div>
								</div>
								<!-- /.form group -->

								<div class="form-group">
									<label for="orderSeller" class="col-sm-3 control-label">판매직원</label>
									<div class="col-sm-9">
										<input name="orderSeller" type="text" class="form-control event-enter" id="orderSeller" placeholder="판매직원" >
									</div>
								</div>
								<!-- /.form group -->

							</div>
							<!-- /.box-body -->

						</div>
						<!-- /.box -->
					</div><!--/col-6-->
					
					
					<div class="col-md-6">
						<!-- general form elements -->
						<div class="box custom-box-list">
							<div class="box-header with-border">
								<h3 class="box-title">가격정보</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-body form-horizontal">

								<div class="form-group">
									<label for="orderTotOrgprice"  class="col-sm-3 control-label"><i class="fa fa-check custom-input-title"></i> 총 정가</label>
									<div class="col-sm-9">
										<input name="orderTotOrgprice" type="text" class="form-control" id="orderTotOrgprice" placeholder="0" disabled>
									</div>
								</div>
								<!-- /.form group -->

								<div class="form-group">
									<label for="orderTotSettleprice" class="col-sm-3 control-label"><i class="fa fa-check custom-input-title"></i> 총 결제가</label>
									<div class="col-sm-9">
										<input name="orderTotSettleprice" type="text" class="form-control" id="orderTotSettleprice" placeholder="0" disabled>
									</div>
								</div>
								<!-- /.form group -->

								<div class="form-group">
									<label for="orderPaymentType" class="col-sm-3 control-label"><i class="fa fa-check custom-input-title"></i> 결제구분</label>
									<div class="col-sm-9">
										<div class="radio customOrderDetail">
											<label>
												<input type="radio" name="orderPaymentType" id="orderPaymentType" value="1" checked="">현금
											</label>
											<label>
												<input type="radio" name="orderPaymentType" id="orderPaymentType" value="2" >카드
											</label>
											<label>
												<input type="radio" name="orderPaymentType" id="orderPaymentType" value="3" >현금+카드
											</label>
										</div>
									</div>
								</div>
								<!-- /.form group -->

								<div class="form-group">
									<label for="orderPgAppNo" class="col-sm-3 control-label">승인번호</label>
									<div class="col-sm-9">
										<input name="orderPgAppNo" type="text" class="form-control event-enter" id="orderPgAppNo" placeholder="승인번호" >
									</div>
								</div>
								<!-- /.form group -->

								<div class="form-group">
									<label for="orderAdminMemo" class="col-sm-3 control-label">관리자메모</label>
									<div class="col-sm-9">
										<input name="orderAdminMemo" type="text" class="form-control event-enter" id="orderAdminMemo" placeholder="관리자메모" >
									</div>
								</div>
								<!-- /.form group -->


							</div>
							<!-- /.box-body -->

							<div class="box-footer">
								<button class="btn btn-sm btn-primary" onClick="funcAct('add','data_form');return false;"><i class="fa fa-edit"></i> 주문서 작성완료</button>
								
							</div>
							</form>
						</div>
						<!-- /.box -->
					</div><!--/col-6-->
				</div><!--/.row-->


			</div>
			<!--/.col (left) -->

		</div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->
{include file="footer.tpl"}

<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- Select2 -->
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>

<!-- utils : 검증 -->
<script src="plugins/utils/utils.js"></script>
<script>

$(function () {
	//Initialize Select2 Elements
	var orderTotOrgprice=0;//정가(소비자가)
	var orderTotGoodsprice=0;//판매가

	$('.select2-one').select2()
	$('.select2-one').on('change',function(){
		//$(".select2 option:selected").val()
		$("#customOrderTableOne").show();

		var test_info=$(".select2-one option:selected").data('goodsinfo');

		//단품
		$('.select-one-tbody').append('<tr class="odd">'
+'<td><input type="hidden" name="goodsGoodsSeq[]" value="'+test_info.goods_seq+'">'+test_info.goods_seq+'</td>'
+'<td><input type="hidden" name="goodsCategoryTitle[]" value="'+test_info.category_title+'">'+test_info.category_title+'</td>'
+'<td><input type="hidden" name="goodsGoodsTitle[]" value="'+test_info.goods_title+'">'+test_info.goods_title+'</td>'
+'<td><input class="tot-price-consumer goods-consumer" type="hidden" name="goodsPriceConsumer[]" value="'+test_info.price_goods_consumer+'">'+test_info.price_goods_consumer+'</td>'
+'<td><input type="text" class="form-control input-sm tot-price-price tot-event-price goods-price" name="goodsPricePrice[]" value="0"></td>'
+'<td class="tot-ea">'+'<input type="number" class="form-control input-sm tot-goods-ea tot-event-price" name="goodsGoodsEa[]" value="1">'+'</td>'
+'<td><div class="radio radio-orderType customOrderDetail"><label><input type="radio" class="orderType-1" name="goodsOrderType['+test_info.goods_seq+'][]" value="1" checked="">현장수령</label><label><input type="radio" class="orderType-2" name="goodsOrderType['+test_info.goods_seq+'][]" value="2">예약배송</label></div></td>'
+'<td><button type="button" class="btn btn-default btn-xs btn-goods-remove"><i class="fa fa-remove"></i></button></td>'
			+'</tr>'
		);
	
		//정가계산
		func_orderTotOrgprice();
	});

	//inputmask
	$('[data-mask]').inputmask();

})
function funcAct(mode, formName){
	func_orderTotOrgprice();
	var price_check_array=[];
	$(".goods-price").each(function(idx,ele){
		price_check_array.push(parseInt($(ele).val()))
	})
	$(".set-price").each(function(idx,ele){
		price_check_array.push(parseInt($(ele).val()))
	})

	if((price_check_array.indexOf(0) !== -1)){
		alert("금액을 입력해주세요.")
		return false;
	}



	if(mode == 'add'){
		if (formCheck.isNull($('#orderCustomer'), '고객이름을 입력해 주세요.', true)) return false;
		if (formCheck.isNull($('#orderMobile'), '휴대폰 번호를 입력해 주세요.', true)) return false;
		if (formCheck.isPhoneNo($('#orderMobile'), '숫자만 입력해 주세요.', true)) return false;
	}
	$(".tot-price-price").attr("disabled",false);
	$("#orderTotOrgprice").attr("disabled",false);
	$("#orderTotGoodsprice").attr("disabled",false);
	$("#orderTotSettleprice").attr("disabled",false);
	$('#'+formName).submit();
	return false;
}

function func_orderTotOrgprice() {
	var totPriceArray=[];
	$(".goods-price").each(function(idx,ele){
		//var goodsEa=$(ele).parent().siblings(".tot-ea").children(".tot-goods-ea").val();
		totPriceArray.push(parseInt($(ele).val()))
	})
	$(".set-price").each(function(idx,ele){
		//console.log($(ele).val())
		totPriceArray.push(parseInt($(ele).val()))
	})
	//console.log(totPriceArray)
	var totPrice=totPriceArray.reduce(function(a,b){
			return a+b
		},0);

	$("#orderTotSettleprice").val(parseInt(totPrice));

	var totConsumerArray=[];
	$(".goods-consumer").each(function(idx,ele){
		var goodsEa=$(ele).parent().siblings(".tot-ea").children(".tot-goods-ea").val();
		totConsumerArray.push(parseInt($(ele).val()*goodsEa));
	})
	$(".set-consumer").each(function(idx,ele){
		totConsumerArray.push(parseInt($(ele).val()));
	})
	var totConsumer=totConsumerArray.reduce(function(a,b){
			return a+b
		},0);
	$("#orderTotOrgprice").val(totConsumer);
}


$(document).on('click',".btn-goods-remove",function(){
	$(this).parent().parent().remove();
	func_orderTotOrgprice();//정가
})

$(document).on('click',".btn-set-remove",function(){
	$('.select-set-tbody').children('.setSeq'+$(this).data('setseq')).remove();
	func_orderTotOrgprice();//정가
})


$(document).on('focusout',".tot-event-price",function(){
	func_orderTotOrgprice();//정가
})


var tempTag='<div class="box box-warning tempClone">'
+'<div class="box-header with-border"><h3 class="box-title">추가세트 정보</h3></div>'
+'<div class="box-body"><div class="row"><div class="col-md-12">'
+'<div class="form-group"><select name="categorySeq" class="select2-set tempCate form-control" style="width: 100%;"><option value="">상품을 선택해 주세요</option>'
+'{foreach $bbsGoods as $key => $val}'
+'<option data-goodsInfo="{$val|@json_encode}" value="{$val.goods_seq}">[{$val.category_title}]'
+'{$val.goods_title} , {$val.goods_model}</option>'
+'{/foreach}'
+'</div></select></div>'
+'<div class="custom-marginTop-table custom-order-table">'
+'<table class="table table-bordered table-striped">'
+'<colgroup><col style="width:5%" /><col style="width:15%" /><col style="width:20%" />'
+'<col style="width:15%" /><col style="width:15%" /><col style="width:15%" />'
+'<col style="width:5%" /></colgroup>'
+'<thead><tr class="odd"><th class="custom-table-textAlign">SEQ</th>'
+'<th class="custom-table-textAlign">카테고리명</th><th class="custom-table-textAlign">제품이름</th>'
+'<th class="custom-table-textAlign">정가</th><th class="custom-table-textAlign">결제가</th>'
+'<th class="custom-table-textAlign">판매구분</th>'
+'<th class="custom-table-textAlign">삭제</th></tr></thead>'
+'<tbody class="select-set-tbody tempTableBody custom-table-textAlign"></tbody></table>'
+'</div>'
+'<div class="row form-group form-horizontal">'
+'<label for="" class="col-sm-8 control-label"><i class="fa fa-check custom-input-title"></i> 결제가</label>'
+'<div class="col-sm-4"><input type="text" class="form-control totSetprice" placeholder="0" >'
+'</div></div>'
+'</div></div></div>'

$(document).on("click","#tempBtn",function(){
	var cloneLength=$('.tempClone').length | 0;
	$(tempTag).clone().appendTo('#tempDisplay').find(".tempCate").addClass("select2-set-"+cloneLength).closest(".tempClone").attr("data-set",cloneLength);
	$(".select2-set-"+cloneLength).select2();
})

$(document).on('change','.select2-set',function(){
	//$(".select2 option:selected").val()
	//console.log($(".select2-set option:selected").data('goodsinfo'))
	var test_info=$(this).find('option:selected').data('goodsinfo');
	var selectSetTbody=$(this).parent().parent().find('.tempTableBody');
	var selectSetTable=$(this).parent().parent().find('.custom-order-table');

	var setNumber=$(this).closest(".tempClone").data("set");

	//추가상품
	selectSetTable.show();
	selectSetTbody.append('<tr class="odd">'
+'<td><input type="hidden" class="form-control input-sm tot-goods-ea tot-event-price" name="setGoodsEa['+setNumber+'][]" value="1"><input type="hidden" name="setGoodSeq['+setNumber+'][]" value="'+test_info.goods_seq+'">'+test_info.goods_seq+'</td>'
+'<td><input type="hidden" name="setCategoryTitle['+setNumber+'][]" value="'+test_info.category_title+'">'+test_info.category_title+'</td>'
+'<td><input type="hidden" name="setGoodsTitle['+setNumber+'][]" value="'+test_info.goods_title+'">'+test_info.goods_title+'</td>'
+'<td><input class="tot-price-consumer set-consumer" type="hidden" name="setPriceConsumer['+setNumber+'][]" value="'+test_info.price_goods_consumer+'">'+test_info.price_goods_consumer+'</td>'
+'<td><input type="text" class="form-control input-sm tot-price-price tot-event-price set-price" name="setPricePrice['+setNumber+'][]" value="0" disabled></td>'
+'<td><div class="radio radio-orderType customOrderDetail"><label><input type="radio" class="orderType-1" name="setOrderType['+setNumber+']['+test_info.goods_seq+'][]" value="1" checked="">현장수령</label><label><input type="radio" class="orderType-2" name="setOrderType['+setNumber+']['+test_info.goods_seq+'][]" value="2">예약배송</label></div></td>'
+'<td><button type="button" class="btn btn-default btn-xs btn-goods-remove"><i class="fa fa-remove"></i></button></td>'
		+'</tr>'
	);
	
	//정가계산
	func_orderTotOrgprice();
});

//세트가격 정리중
$(document).on('focusout',".totSetprice",function(){
	var thisSetTotPriceVal=$(this).val()
	var thisSelect=$(this).closest(".col-md-12").find(".tot-price-consumer")
	var setTotPriceArray=[];
	/*thisSelect.length :: 1부터*/
	thisSelect.each(function(index){
		/*index :: 0부터*/
		setTotPriceArray.push(parseInt($(this).closest(".col-md-12").find(".tot-price-consumer").eq(index).val()))
	})
	/*
		thisSetTotPriceVal :: 세트결제합
		setTotPriceVal :: 세트정가합
	*/
	setTotPriceVal=setTotPriceArray.reduce(function(a,b){
			return a+b;
		},0);
	/*
		비율계산
		setDiscountTotPriceArray :: 세트 결제가격
	*/
	var setDiscountTotPriceArray=[];
	setTotPriceArray.forEach(function(val,index){

		if(index == (thisSelect.length-1)){
			var lastVal=parseInt(thisSetTotPriceVal)-parseInt(setDiscountTotPriceArray.reduce(function(a,b){
					return a+b;
				},0));
			setDiscountTotPriceArray.push(lastVal || 0);
		}else{
			setDiscountTotPriceArray.push(Math.floor(thisSetTotPriceVal*(val / setTotPriceVal)));
		}
	})

	/* setDiscountTotPriceArray :: 최종 결제가 */
	thisSelect.each(function(index){
		/*index :: 0부터*/
		$(this).closest(".col-md-12").find(".tot-price-price").eq(index).val(setDiscountTotPriceArray[index])
	});

	func_orderTotOrgprice();
})

</script>
</script>
</body>
</html>