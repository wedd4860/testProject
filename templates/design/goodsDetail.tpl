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
			<li class="active">상세</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- left column -->
			<div class="col-md-6">
				<!-- general form elements -->
				<div class="box custom-box-list">
					<div class="box-header with-border">
						<h3 class="box-title">상품 카테고리</h3>
					</div>
					<!-- /.box-header -->

					<!-- form start -->
					<form role="form" class="form-horizontal" action="action/act.goods.php" method="post" id="data_form">
						<input type="hidden" id="bbsSeq" name="bbsSeq" value="{if !empty($bbsSeq)}{$bbsSeq}{/if}" />
						<input type="hidden" name="page_type" value="admin" />
						<input type="hidden" id="mode" name="mode" value="{$mode}" />
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-3 control-label"><i class="fa fa-check custom-input-title"></i> 상품 카테고리</label>
								<div class="col-sm-8">
									<select name="categorySeq" id="categorySeq" class="form-control select2" style="width: 100%;">
										<option value="">상품 카테고리를 선택해 주세요</option>
									{foreach $bbsCategory as $key => $val}
									{if {$val.category_code|count_characters} > 3}
										<option value="{$val.category_seq}" 
										{if $mode == 'mod' && $val.category_seq == $bbs.category_seq}
											selected
										{/if}
										>{$val.category_title}</option>
									{/if}
									{/foreach}
									</select>
								</div><!--/end form-group-->
							</div><!--/ end form-group-->
					</div>
					<!-- /.box-body -->

				</div>
				<!-- /.box -->

				<!-- general form elements -->
				<div class="box custom-box-list">
					<div class="box-header with-border">
						<h3 class="box-title">상품 상세정보</h3>
					</div>
					<!-- /.box-header -->

					<!-- form start -->
					<div class="box-body form-horizontal">

						<div class="form-group">
							<label for="goodsTitle" class="col-sm-3 control-label"><i class="fa fa-check custom-input-title"></i> 상품이름</label>
							<div class="col-sm-8">
								<input name="goodsTitle" type="text" class="form-control" id="goodsTitle" placeholder="상품이름" value="{if !empty($bbs.goods_title)}{$bbs.goods_title}{/if}">
							</div>
						</div>
						<!-- /.form group -->

						<div class="form-group">
							<label for="goodsTitle" class="col-sm-3 control-label">상품보조이름</label>
							<div class="col-sm-8">
								<input name="goodsNameSub" type="text" class="form-control" id="goodsNameSub" placeholder="상품보조이름" value="{if !empty($bbs.goods_name_sub)}{$bbs.goods_name_sub}{/if}">
							</div>
						</div>
						<!-- /.form group -->

						<!-- IP mask -->
						<div class="form-group">
							<label class="col-sm-3 control-label"><i class="fa fa-check custom-input-title"></i> 상품코드</label>
							<div class="col-sm-8">
								<input name="goodsCode" id="goodsCode" type="text" class="form-control" value="{if !empty($bbs.goods_code)}{$bbs.goods_code}{/if}">
							</div>
						</div>
						<!-- /.form group -->

						<!-- form group -->
						<div class="form-group">
							<label class="col-sm-3 control-label">상품설명</label>
							<div class="col-sm-8">
								<textarea name="goodsDesc" class="form-control" rows="3" placeholder="상품 상세설명을 입력해 주세요">{if !empty($bbs.goods_desc)}{$bbs.goods_desc}{/if}</textarea>
							</div>
						</div>
						<!-- /.form group -->

						<div class="form-group">
							<label for="goodsModel" class="col-sm-3 control-label">모델명</label>
							<div class="col-sm-8">
								<input name="goodsModel" type="text" class="form-control" id="goodsModel" placeholder="모델명" value="{if !empty($bbs.goods_model)}{$bbs.goods_model}{/if}">
							</div>
						</div>
						<!-- /.form group -->

						<!-- form group -->
						<div class="form-group">
							<label class="col-sm-3 control-label">출시일</label>
							<div class="col-sm-8">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input name="goodsLaunchdt" type="text" class="form-control pull-right" id="datepicker" value="{if !empty($bbs.goods_launchdt)}{$bbs.goods_launchdt}{/if}">
								</div>
								<!-- /.input group -->
							</div>
						</div>
						<!-- /.form group -->

						<div class="form-group">
							<label for="goodsOrigin" class="col-sm-3 control-label">원산지</label>
							<div class="col-sm-8">
								<input name="goodsOrigin" type="text" class="form-control" id="goodsOrigin" placeholder="원산지" value="{if !empty($bbs.goods_origin)}{$bbs.goods_origin}{/if}">
							</div>
						</div>
						<!-- /.form group -->

					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<button class="btn btn-sm btn-primary" onClick="funcAct('add','data_form');return false;"><i class="fa fa-edit"></i> {if $mode == "add"}상품 추가{else}상품 저장{/if}</button>
						{if $mode == "mod"}
						<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i> 상품삭제</button>
						<div class="modal modal-danger fade" id="modal-danger">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
										<h4 class="modal-title">상품정보 삭제</h4>
									</div>
									<div class="modal-body">
										<p>현재 상품정보를 정말로 삭제 하시겠습니까?</p>
										<br>
										<p>상품정보 관리의 가격정보, 세트정보 관리, 주문 관리 중 하나라도 내역이 존재하는 경우 삭제되지 않습니다.</p>
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
					</div><!-- /.box-footer -->
				</form>

				</div>
				<!-- /.box -->
			</div>
			<!--/.col (left) -->
{if $mode=="mod"}
			<!-- right column -->
			<div class="col-md-6">
				{* 2018-01-05 상품옵션 삭제 요청 :: 요청자 : 박진경
				<!-- general form elements -->
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">상품 색상정보</h3>
					</div>
					<!-- /.box-header -->

					<div class="box-body">
						<form id="formOptionDel" method="post">
							<input type="hidden" name="page_type" value="admin" />
							<input type="hidden" id="mode" name="optionMode" value="del" />
							<div class="form-group" id="optionSection">

						{if !empty($optionBbsList)}
							{foreach $optionBbsList as $key => $val}
								<button class="btn btn-default btn-xs custom-marginTop-btn" id="custom_btn_{$val.option_seq}" onclick="return false">
									<span  class="badge" style="background-color:{$val.option_color_code}">　</span> {$val.option_color_title}
									<span class="ajaxColorDel" data-colorSeq="{$val.option_seq}">X</span>
								</button>
							{/foreach}
						{/if}
							</div>
						</form>

						<!-- form start -->
						<form id="formOption" class="form-horizontal" method="post">
							<input type="hidden" id="optionGoodsSeq" name="optionGoodsSeq" value="{if !empty($bbsSeq)}{$bbsSeq}{/if}" />
							<input type="hidden" id="optionBbsName" name="optionBbsName" value="goodsOption" />
							<input type="hidden" name="page_type" value="admin" />
							<input type="hidden" id="mode" name="optionMode" value="add" />
							<div class="form-group">
								<label for="optionColorTitle" class="col-sm-3 control-label"><i class="fa fa-check custom-input-title"></i> 색상이름</label>
								<div class="col-sm-8">
									<input name="optionColorTitle" type="text" class="form-control" id="optionColorTitle" placeholder="색상이름을 적어주세요">
								</div>
							</div>
							<div class="form-group">
								<label for="optionColorCode" class="col-sm-3 control-label">색상코드</label>
								<div class="col-sm-8">
									<input name="optionColorCode" type="text" class="form-control my-colorpicker1" id="optionColorCode" placeholder="색상코드를 적어주세요">
								</div>
							</div>
						</form>
					</div>
					<!-- /.box-body -->
						
					<div class="box-footer">
						<button class="btn btn-success btn-sm ajaxColorAdd"><i class="fa fa-slack"></i> 색상 추가</button>
					</div>
					<!-- /.box-footer -->

				</div>
				<!-- /.box -->
				*}

				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">전시회별 상품 가격정보</h3>
					</div>
					<!-- /.box-header -->

					<!-- form start -->
					<div class="box-body">
						<form name="search_form" id="search_form" method="get" action="{$phpSelf}">

						<input type="hidden" id="bbsSeq" name="bbsSeq" value="{$bbsSeq}" />
							<div class="col-sm-5 form-inline">
								<div class="form-group">
									<select id="fair_ea" name="s_ea" class="form-control custom-border-radius input-sm">
										{foreach $bbsEa as $key=>$val}
										<option value="{$val}" 
										{if $s_ea == $val}selected="selected"{/if}
										>{$val}</option>
									{/foreach}
									</select>
								</div>
								<button class="btn btn-default btn-sm" type="submit">보기</button>
							</div>

							<div class="col-sm-7">
								<div class="input-group input-group-sm col-sm-7 pull-right">
									<input id="fair_title" name="s_title" type="text" {if !empty($s_title)}value="{$s_title}"{/if} placeholder="전시회 이름으로 검색" class="form-control input-sm">
									<span class="input-group-btn">
										<button type="submit" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
									</span>
								</div>
							</div>
						</form>


						<div class="col-sm-12 custom-marginTop-table">
							<table class="table table-bordered table-striped">
								<colgroup>
									<col style="width:5%" />
									<col style="width:39%" />
									<col style="width:20%" />
									<col style="width:20%" />
									<col style="width:16%" />
								</colgroup>
								<thead>
									<tr class="odd">
										<th class="custom-table-textAlign">SEQ</th>
										<th class="custom-table-textAlign">전시회명</th>
										<th class="custom-table-textAlign">정가</th>
										<th class="custom-table-textAlign">원가</th>
										<th class="custom-table-textAlign">상세</th>
									</tr>
								</thead>
								{if !empty($bbsPriceList)}
								<tbody class="custom-table-textAlign">
									{foreach $bbsPriceList as $key=>$val}
									<tr class="odd">
										<td>{$val.price_seq}<?=$bbs['price_seq']?></td>
										<td>{$val.fair_title}<?=$bbs['fair_title']?></td>
										<td>{$val.price_goods_consumer|number_format} 원</td>
										<td>{$val.price_goods_supply|number_format} 원</td>
										<td>
											<a href="goodsPriceDetail.php?bbsSeq={$val.price_seq}&priceGoodsSeq={$bbsSeq}"><i class="fa fa-share-square-o"></i>상세</a>
										</td>
									</tr>
									{/foreach}
								</tbody>
								{/if}
							</table>
						</div><!--col-sm-12-->


						<div class="col-sm-12">
{if $pageCount > 0 && $pageLoop > 0}
	<ul class="pagination custom-zero-margin">
	{if !empty($pageList.prev.url)}
		<li><a href="{$pageList.prev.url}">&laquo;</a></li>
	{/if}
	{for $i=1 to $pageLoop}
		{if is_int($p) && $p == $pageList[$i].name}
		<li><a href="#">{$p}</a></li>
		{else}
		<li><a href="{$pageList[$i].url}">{$pageList[$i].pageNo}</a></li>
		{/if}
	{/for}
	{if !empty($pageList.next.url)}
		<li><a href="{$pageList.next.url}">&raquo;</a></li>
	{/if}
	</ul>
{/if}
						</div><!-- /.col-sm-12 -->
					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<a href="goodsPriceDetail.php?mode=add&priceGoodsSeq={$bbsSeq}" class="btn btn-success btn-sm"><i class="fa fa-slack"></i> 가격정보 추가</a>
					</div>
				</div>
				<!-- /.box -->
			</div>
			<!--/.col (right) -->
{else}
<!-- right column -->
			<div id="tempList" class="col-md-6">

				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">전시회별 상품 가격정보 <span style="color:#fff !important">FO17SSSC0011</span></h3>
					</div>
					<!-- /.box-header -->

					<!-- form start -->
					<div class="box-body">

						<div class="col-sm-12 custom-marginTop-table">
							<table class="table table-bordered table-striped">
								<colgroup>
									<col style="width:5%" />
									<col style="width:20%" />
									<col style="width:18%" />
									<col style="width:18%" />
									<col style="width:18%" />
									<col style="width:16%" />
								</colgroup>
								<thead>
									<tr class="odd">
										<th class="custom-table-textAlign">SEQ</th>
										<th class="custom-table-textAlign">전시회명</th>
										<th class="custom-table-textAlign">정가</th>
										<th class="custom-table-textAlign">판매가</th>
										<th class="custom-table-textAlign">공급가</th>
										<th class="custom-table-textAlign">상세</th>
									</tr>
								</thead>
							</table>
						</div><!--col-sm-12-->
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!--/.col (right) -->
{/if}<!--END if $mode-->

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
<!-- PACE -->
<script src="bower_components/PACE/pace.min.js"></script>

<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- Select2 -->
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>

<!-- bootstrap Date picker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.kr.js"></script>
<!-- utils : 검증 -->
<script src="plugins/utils/utils.js"></script>
<script>

  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

	//Date picker
    $('#datepicker').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		language: "kr"
    })

  })

function funcAct(mode, formName){
	if(mode == 'mod' || mode == 'add'){
		if (formCheck.isNull($('#categorySeq'), '카테고리를 선택해 주세요.', true)) return false;
		if (formCheck.isNull($('#goodsTitle'), '상품이름을 입력해 주세요.', true)) return false;
		if (formCheck.isNull($('#goodsCode'), '상품코드를 입력해 주세요.', true)) return false;
	}else if(mode == 'del'){
		$('#mode').val('del');
	}
	
	$('#'+formName).submit();
	return false;
}

</script>
</body>
</html>