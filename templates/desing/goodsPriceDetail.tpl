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
			<li><a href="{$goodsUrl}"><i class="fa fa-file-o"></i>상품정보</a></li>
			<li><a href="goodsDetail.php?bbsSeq={$priceGoodsSeq}">상세</a></li>
			<li class="active">가격</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- left column -->
			<div class="col-md-12">
				<!-- general form elements -->
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">상품가격 등록</h3>
					</div>
					<!-- /.box-header -->

					<form role="form" class="form-horizontal" action="action/act.goods.price.php" method="post" id="data_form">
						<input type="hidden" id="bbsSeq" name="bbsSeq" value="{if !empty($bbsSeq)}{$bbsSeq}{/if}" />
						<input type="hidden" id="priceGoodsSeq" name="priceGoodsSeq" value="{$priceGoodsSeq}" />
						<input type="hidden" name="page_type" value="admin" />
						<input type="hidden" id="mode" name="mode" value="{$mode}" />
					<!-- form start -->
					<div class="box-body">

						<div class="form-group">
							<label class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 전시회 선택</label>
							<div class="col-sm-8">
								<select name="priceFairSeq" id="priceFairSeq" class="form-control select2" style="width: 100%;" {if $mode=='mod'}disabled{/if}>
									<option value="">전시회를 선택해 주세요</option>
								{foreach $bbsFair as $key => $val}
									<option value="{$val.fair_seq}" {if $mode=="mod" && $val.fair_seq==$bbs.price_fair_seq}selected{/if}>{$val.fair_title}</option>
								{/foreach}
								</select>
							</div>
						</div><!--/.form-group-->

						<div class="form-group">
							<label for="priceGoodsConsumer" class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 정가</label>
							<div class="col-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa  fa-krw"></i></span>
									<input name="priceGoodsConsumer" type="number" class="form-control" id="priceGoodsConsumer" placeholder="정가를 입력해주세요" 
									{if !empty($bbs.price_goods_consumer)}
										value="{$bbs.price_goods_consumer}"
									{else}
										{if !empty($bbsPrice.price_goods_consumer)}
											value="{$bbsPrice.price_goods_consumer}"
										{/if}
									{/if}>
								</div>
							</div>
						</div><!--/.form-group-->

						<div class="form-group">
							<label for="priceGoodsSupply" class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 원가</label>		
							<div class="col-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa  fa-krw"></i></span>
									<input name="priceGoodsSupply" type="number" class="form-control" id="priceGoodsSupply" placeholder="원가를 입력해주세요"
									{if !empty($bbs.price_goods_supply)}
										value="{$bbs.price_goods_supply}"
									{else}
										{if !empty($bbsPrice.price_goods_supply)}
											value="{$bbsPrice.price_goods_supply}"
										{/if}
									{/if}>
								</div>
							</div>
						</div><!--/.form-group-->
					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<button class="btn btn-sm btn-success" onClick="funcAct('add','data_form');return false;"><i class="fa fa-edit"></i> 
						{if $mode == "add"}
							상품가격 추가
						{else}
							상품가격 저장
						{/if}</button>
						{if $mode == "mod"}
						<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i> 상품가격 삭제</button>
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
										<p>현재 상품가격을 정말로 삭제 하시겠습니까?</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-sm btn-outline pull-left" data-dismiss="modal">취소</button>
										<button type="button" class="btn btn-sm btn-outline" onClick="funcAct('del','data_form');return false;">삭제하기</button>
									</div>
								</div>
								<!-- /.modal-content -->
							</div>
							<!-- /.modal-dialog -->
						</div>
						<!-- /.modal -->
						{/if}
					</div>
					</form>
				</div>
				<!-- /.box -->
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

<!-- utils : 검증 -->
<script src="plugins/utils/utils.js"></script>
<script>
$(function () {
	//Initialize Select2 Elements
	$('.select2').select2()
})

function funcAct(mode, formName){
	if(mode == 'mod' || mode == 'add'){
		if (formCheck.isNull($('#priceFairSeq'), '전시회를 선택해 주세요.', true)) return false;
		if (formCheck.isNull($('#priceGoodsConsumer'), '정가를 입력해 주세요.', true)) return false;
		if (formCheck.isNull($('#priceGoodsSupply'), '원가를 입력해 주세요.', true)) return false;
	}else if(mode == 'del'){
		$('#mode').val('del');
	}
	
	$('#'+formName).submit();
	return false;
}
</script>
</body>
</html>*}