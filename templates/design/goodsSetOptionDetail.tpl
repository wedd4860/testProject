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
			<li><a href="{$setUrl}"><i class="fa fa-files-o"></i>세트정보</a></li>
			<li><a href="goodsSetDetail.php?bbsSeq={$setSeq}">상세</a></li>
			<li class="active">할인율</li>
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
				  <h3 class="box-title">상품 및 가격정보</h3>
				</div>
				<!-- /.box-header -->

				<!-- form start -->
				<form role="form" action="action/act.goods.set.option.php" method="post" id="data_form" class="form-horizontal">
					<input type="hidden" id="bbsSeq" name="bbsSeq" value="{if $mode=='mod'}{$bbsSeq}{/if}" />
					<input type="hidden" id="setSeq" name="setSeq" value="{$setSeq}" />
					<input type="hidden" id="setFairSeq" name="setFairSeq" value="{$setFairSeq}" />
					<input type="hidden" name="page_type" value="admin" />
					<input type="hidden" id="mode" name="mode" value="{$mode}" />

					<div class="box-body">

						<div class="form-group">
							<label class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 상품이름</label>
							<div class="col-sm-8">
								<select name="setOptionGoodsSeq" id="setOptionGoodsSeq" class="form-control select2" style="width: 100%;"{if $mode=='mod'}disabled{/if}>
									<option value="">상품을 선택해 주세요</option>
								{if $mode=='mod'}
									<option value="{$bbs.goods_seq}" selected>{$bbs.goods_title}</option>
								{else}
									{foreach $bbsGoods as $key => $val}
										<option value="{$val.goods_seq}">{$val.goods_title}</option>
									{/foreach}
								{/if}
								</select>
							</div>
						</div><!--/.form-group-->

						<div class="form-group">
							<label class="col-sm-2 control-label" for="setOptionPercent"><i class="fa fa-check custom-input-title"></i> 할인율(%)</label>
							<div class="col-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-bar-chart"></i></span>
									<input name="setOptionPercent" type="text" class="form-control" id="setOptionPercent" placeholder="가격 할인율(%)를 입력해주세요" data-inputmask='"mask": "99"' data-mask {if !empty($bbs.set_option_percent)}value="{$bbs.set_option_percent}"{/if}>
								</div>
							</div>
						</div><!--/.form-group-->
					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<button class="btn btn-sm btn-success" onClick="funcAct('add','data_form');return false;"><i class="fa fa-edit"></i> 
						{if $mode == "add"}
							상품 및 가격정보 추가
						{else}
							상품 및 가격정보 저장
						{/if}</button>
						{if $mode == "mod"}
						<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i> 상품 및 가격정보 삭제</button>
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
										<p>상품 및 가격정보를 정말로 삭제 하시겠습니까?</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">취소</button>
										<button type="button" class="btn btn-outline" onClick="funcAct('del','data_form');return false;">삭제하기</button>
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
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>

<!-- utils : 검증 -->
<script src="plugins/utils/utils.js"></script>
<script>
$(function () {
	//Initialize Select2 Elements
	$('.select2').select2()

	//inputmask
    $('[data-mask]').inputmask();
})

function funcAct(mode, formName){
	if(mode == 'mod' || mode == 'add'){
		if (formCheck.isNull($('#setOptionGoodsSeq'), '상품을 선택해 주세요.', true)) return false;
		if (formCheck.isNull($('#setOptionPercent'), '가격 할인율(%)를 입력해주세요.', true)) return false;
	}else if(mode == 'del'){
		$('#mode').val('del');
	}
	
	$('#'+formName).submit();
	return false;
}
</script>
</body>
</html>