{assign var="nowTime" value = $smarty.now|date_format:"%Y%m%d"}
{assign var="regTime" value = $bbs.order_regdt|date_format:"%Y%m%d"}
{if $nowTime == $regTime}
	{assign var="modVal" value = true}
{else}
	{assign var="modVal" value = false}
{/if}

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
		<li class="active">주문서 상세</li>
		</ol>
	</section>

	<!-- Main content --><!-- form start -->
<form role="form" action="action/act.order.php" method="post" id="data_form">
<input type="hidden" id="mode" name="mode" {if $modVal}value="{$mode}"{/if}/>
<input type="hidden" name="page_type" value="admin" />
<input type="hidden"  name="bbsSeq" value="{$bbsSeq}" />
	<section class="invoice">
		{if $bbs.order_status == 2}
		<div class="invoice-cancel">
			<p>주문취소</p>
		</div>
		{/if}
		<!-- title row -->
		<div class="row">

			<div class="col-xs-12">
				<h2 class="page-header">
					<i class="fa fa-krw"></i> 주문서
					<small class="pull-right">{if !empty($bbs.order_regdt)}작성일 : {$bbs.order_regdt}{/if}{if !empty($bbs.order_moddt)}, 수정일 : {$bbs.order_moddt}{/if}{if !empty($bbs.order_canceldt)}, 취소일 : {$bbs.order_canceldt}{/if}
				</small>
				</h2>
			</div>
			<!-- /.col -->
		</div>
		
		<!-- info row -->
		<div class="row invoice-info custom-orderSettle-top">
			<div class="col-sm-6 invoice-col">
				<div class="custom-orderSettle-info">

					<div class="form-group">
						<label>고객이름</label>
						<input name="orderCustomer" type="text" class="form-control" id="orderCustomer" placeholder="고객이름을 적어주세요" value="{if !empty($bbs.order_customer)}{$bbs.order_customer}{/if}" {if !$modVal}disabled{/if}>
					</div>
					
					<div class="form-group">
						<label>휴대폰</label>
						<input name="orderMobile" type="text" class="form-control" id="orderMobile" placeholder="휴대폰" value="{if !empty($bbs.order_mobile)}{$bbs.order_mobile}{/if}" data-inputmask='"mask": "999-9999-9999"' data-mask {if !$modVal}disabled{/if}>
					</div>
					
					<div class="form-group">
						<label>주소</label>
						<input name="orderAddress"type="text" class="form-control" id="orderAddress" placeholder="주소" value="{if !empty($bbs.order_address)}{$bbs.order_address}{/if}" {if !$modVal}disabled{/if}>
					</div>

					<div class="form-group">
						<label>고객메모</label>
						<input name="orderMemo" type="text" class="form-control" id="orderMemo" placeholder="고객메모" value="{if !empty($bbs.order_memo)}{$bbs.order_memo}{/if}" {if !$modVal}disabled{/if}>
					</div>

					<div class="form-group">
						<label>결제구분</label>
						<div class="radio customOrderSettle">
							<label>
								<input type="radio" name="orderPaymentType" id="orderPaymentType" value="1" {if !empty($bbs.order_payment_type) && $bbs.order_payment_type == 1}checked=""{/if} {if !$modVal}disabled{/if}>현금
							</label>
							<label>
								<input type="radio" name="orderPaymentType" id="orderPaymentType" value="2" {if !empty($bbs.order_payment_type) && $bbs.order_payment_type == 2}checked=""{/if} {if !$modVal}disabled{/if}>카드
							</label>
							<label>
								<input type="radio" name="orderPaymentType" id="orderPaymentType" value="3" {if !empty($bbs.order_payment_type) && $bbs.order_payment_type == 3}checked=""{/if} {if !$modVal}disabled{/if}>현금+카드
							</label>
						</div>
					</div>
					
				</div>
			</div>
			<!-- /.col -->
			<div class="col-sm-6 invoice-col">
				<div class="custom-orderSettle-info">

					<div class="form-group">
						<label>주문번호</label>
						<input name="" type="text" class="form-control" id="orderCode" placeholder="주문번호" value="{if !empty($bbs.order_code)}{$bbs.order_code}{/if}" disabled>
					</div>

					<div class="form-group">
						<label>승인번호</label>
						<input name="orderPgAppNo" type="text" class="form-control" id="orderPgAppNo" placeholder="승인번호" value="{if !empty($bbs.order_pgAppNo)}{$bbs.order_pgAppNo}{/if}" {if !$modVal}disabled{/if}>
					</div>
				
					<div class="form-group">
						<label>판매직원</label>
						<input name="orderSeller" type="text" class="form-control" id="orderSeller" placeholder="판매직원" value="{if !empty($bbs.order_seller)}{$bbs.order_seller}{/if}" {if !$modVal}disabled{/if}>
					</div>

					<div class="form-group">
						<label>관리자 메모</label>
						<input name="orderAdminMemo" type="text" class="form-control" id="orderAdminMemo" placeholder="관리자 메모" value="{if !empty($bbs.order_admin_memo)}{$bbs.order_admin_memo}{/if}">
					</div>
				</div>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
{if $totalOneCount > 0}
		<!-- Table row -->
		<div class="row custom-marginTop-table custom-orderSettle-middle first-child">
			<div class="col-sm-12 custom-order-table on">
				<p class="custom-orderSettle-title">단품정보</p>
				<table class="table table-bordered table-striped">
					<colgroup>
						<col style="width:5%" />
						<col style="width:20%" />
						<col style="width:25%" />
						<col style="width:15%" />
						<col style="width:15%" />
						<col style="width:10%" />
						<col style="width:10%" />
					</colgroup>
					<thead>
						<tr>
							<th class="custom-table-textAlign">SEQ</th>
							<th class="custom-table-textAlign">카테고리명</th>
							<th class="custom-table-textAlign">제품이름</th>
							<th class="custom-table-textAlign">정가</th>
							<th class="custom-table-textAlign">결제가</th>
							<th class="custom-table-textAlign">수량</th>
							<th class="custom-table-textAlign">결제방식</th>
						</tr>
					</thead>
					<tbody class="custom-table-textAlign">
					{foreach $bbsOneList as $key=>$val}
						<tr>
							<td><input name="goods[{$val.item_seq}][seq]" type="hidden" value="{if !empty($val.item_seq)}{$val.item_seq}{/if}">{if !empty($val.item_seq)}{$val.item_seq}{/if}</td>
							<td>{if !empty($val.category_title)}{$val.category_title}{/if}</td>
							<td>{if !empty($val.goods_title)}{$val.goods_title}{/if}</td>
							<td class="goods-consumer" data-consumer="{if !empty($val.item_goods_consumer)}{$val.item_goods_consumer}{/if}">{if !empty($val.item_goods_consumer)}{$val.item_goods_consumer|number_format}{/if} 원</td>
							<td><input name="goods[{$val.item_seq}][price]" type="text" class="form-control goods-price event-totOrder" id="goodsPice_{$val.item_seq}" placeholder="0" value="{if !empty($val.item_goods_price)}{$val.item_goods_price}{/if}" {if !$modVal}disabled{/if}></td>
							<td><input name="goods[{$val.item_seq}][ea]" type="number" class="form-control goods-ea event-totOrder" id="goodsEa_{$val.item_seq}" placeholder="0" value="{if !empty($val.item_ea)}{$val.item_ea}{/if}" {if !$modVal}disabled{/if}></td>
							<td>
								<div class="radio">
									<label>
										<input type="radio" name="goods[{$val.item_seq}][type]" value="1" {if !empty($val.item_order_type) && $val.item_order_type == 1}checked=""{/if} {if !$modVal}disabled{/if}>현장수령
									</label>
									<label>
										<input type="radio" name="goods[{$val.item_seq}][type]" value="2" {if !empty($val.item_order_type) && $val.item_order_type == 2}checked=""{/if} {if !$modVal}disabled{/if}>예약배송
									</label>
								</div>
							</td>
						</tr>
					{/foreach}
					</tbody>
				</table>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
{/if}

{if $totalSetCount > 0}
	{foreach $bbsSetList as $key=>$val}
		<!-- Table row -->
		<div class="row custom-marginTop-table custom-orderSettle-middle">
			<div class="col-sm-12 custom-order-table on">
				<p class="custom-orderSettle-title">({$key+1})세트정보</p>
				<table class="table table-bordered table-striped">
					<colgroup>
						<col style="width:5%" />
						<col style="width:20%" />
						<col style="width:25%" />
						<col style="width:15%" />
						<col style="width:15%" />
						<col style="width:10%" />
						<col style="width:10%" />
					</colgroup>
					<thead>
						<tr>
							<th class="custom-table-textAlign">SEQ</th>
							<th class="custom-table-textAlign">카테고리명</th>
							<th class="custom-table-textAlign">제품이름</th>
							<th class="custom-table-textAlign">정가</th>
							<th class="custom-table-textAlign">결제가</th>
							<th class="custom-table-textAlign">수량</th>
							<th class="custom-table-textAlign">결제방식</th>
						</tr>
					</thead>
					<tbody class="custom-table-textAlign">
					{foreach $val as $tempKey=>$tempVal}
						<tr>
							<td><input name="set[{$tempVal.item_seq}][seq]" type="hidden" value="{if !empty($tempVal.item_seq)}{$tempVal.item_seq}{/if}">{if !empty($tempVal.item_seq)}{$tempVal.item_seq}{/if}</td>
							<td>{if !empty($tempVal.category_title)}{$tempVal.category_title}{/if}</td>
							<td>{if !empty($tempVal.goods_title)}{$tempVal.goods_title}{/if}</td>
							<td class="set-consumer" data-consumer="{if !empty($tempVal.item_goods_consumer)}{$tempVal.item_goods_consumer}{/if}">{if !empty($tempVal.item_goods_consumer)}{$tempVal.item_goods_consumer|number_format}{/if} 원</td>
							<td><input name="set[{$tempVal.item_seq}][price]" type="text" class="form-control set-price event-totOrder" id="goodsPice_{$tempVal.item_seq}" placeholder="0" value="{if !empty($tempVal.item_goods_price)}{$tempVal.item_goods_price}{/if}" {if !$modVal}disabled{/if}></td>
							<td>{if !empty($tempVal.item_ea)}{$tempVal.item_ea}{/if}</td>
							<td>
								<div class="radio">
									<label>
										<input type="radio" name="set[{$tempVal.item_seq}][type]" value="1" {if !empty($tempVal.item_order_type) && $tempVal.item_order_type == 1}checked=""{/if} {if !$modVal}disabled{/if}>현장수령
									</label>
									<label>
										<input type="radio" name="set[{$tempVal.item_seq}][type]" value="2" {if !empty($tempVal.item_order_type) && $tempVal.item_order_type == 2}checked=""{/if} {if !$modVal}disabled{/if}>예약배송
									</label>
								</div>
							</td>
						</tr>
					{/foreach}
					</tbody>
				</table>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	{/foreach}
{/if}

		
			<div class="row custom-orderSettle-bottom">
				<!-- accepted payments column -->
				<div class="col-md-12">
					<p class="custom-orderSettle-title">결제정보 {if !empty($bbs.fair_title)}({$bbs.fair_title}){else}(-){/if}</p>
					<div class="table-responsive row">
						<div class="col-md-6">
							<div class="form-group">
								<label>총 정가</label>
								<input name="orderTotOrgprice" type="text" class="form-control" id="orderTotOrgprice" placeholder="0" value="{if !empty($bbs.order_tot_orgprice)}{$bbs.order_tot_orgprice}{/if}" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>총 결제가</label>
								<input name="orderTotSettleprice" type="text" class="form-control" id="orderTotSettleprice" placeholder="0" value="{if !empty($bbs.order_tot_settleprice)}{$bbs.order_tot_settleprice}{/if}" disabled>
							</div>
						</div>
					</div>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->

			<!-- this row will not appear when printing -->
			<div class="row no-print">
				<div class="col-xs-12">
					<button class="btn btn-sm btn-primary" onClick={if $modVal}"funcAct('mod','data_form');return false;"{else}"funcAct('memo','data_form');return false;"{/if}><i class="fa fa-edit"></i> 주문저장</button>
					<button type="button" class="btn btn-danger btn-sm pull-right" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i> 주문삭제
					</button>
					<button type="button" class="btn btn-sm btn-warning pull-right" data-toggle="modal" data-target="#modal-warning" style="margin-right: 5px;"><i class="fa fa-warning"></i> 주문취소
					</button>

					<div class="modal modal-warning fade" id="modal-warning">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h4 class="modal-title">경고</h4>
								</div>
								<div class="modal-body">
									<p>현재 주문을 정말로 취소 하시겠습니까?</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-sm btn-outline pull-left" data-dismiss="modal">취소</button>
									<button type="button" class="btn btn-sm btn-outline" onClick="funcAct('cancel','data_form');return false;">취소하기</button>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->

					<div class="modal modal-danger fade" id="modal-danger">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h4 class="modal-title">위험</h4>
								</div>
								<div class="modal-body">
									<p>현제 주문을 정말로 삭제 하시겠습니까?</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-sm  btn-outline pull-left" data-dismiss="modal">취소</button>
									<button type="button" class="btn btn-sm  btn-outline" onClick="funcAct('del','data_form');return false;">삭제하기</button>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->
				</div>
			</div>
	</section>
</form><!-- form end -->

	<!-- /.content -->
	<div class="clearfix"></div>

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
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- utils : 검증 -->
<script src="plugins/utils/utils.js"></script>
<script>
$(function () {
	//inputmask
	$('[data-mask]').inputmask();
})

function totOrder(){
	var totOrderOrgprice=0;
	var totOrderSettleprice=0;
	$(".goods-consumer").each(function(index){
		totOrderOrgprice+=($(this).data("consumer")*$(".goods-ea").eq(index).val())
		totOrderSettleprice+=parseInt($(".goods-price").eq(index).val())
		//totOrderSettleprice+=($(".goods-price").eq(index).val()*$(".goods-ea").eq(index).val())
	})
	$(".set-consumer").each(function(index){
		totOrderOrgprice+=parseInt($(this).data("consumer"))
		totOrderOrgprice+=parseInt($(this).data("consumer"))
		totOrderSettleprice+=parseInt($(".set-price").eq(index).val())
	})
	
	$("#orderTotOrgprice").val(totOrderOrgprice);
	$("#orderTotSettleprice").val(totOrderSettleprice);

	//return [totOrderOrgprice,totOrderSettleprice];
}

$(".event-totOrder").on('change',function(){
	totOrder();
})

function funcAct(mode, formName){
	totOrder();
	if(mode == 'mod'){
		/*if (formCheck.isNull($('#orderAdminMemo'), '전시회 이름을 입력해 주세요.', true)) return false;*/
		$("#orderTotOrgprice").attr("disabled",false);
		$("#orderTotSettleprice").attr("disabled",false);
	}else if(mode == 'cancel'){
		$('#mode').val('cancel');
	}else if(mode == 'del'){
		$('#mode').val('del');
	}else if(mode == 'memo'){
		$('#mode').val('memo');
	}
	$('#'+formName).submit();
	return false;
}
</script>
</body>
</html>