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
			<li class="active">상세</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- left column -->
			<div class="col-md-12">
				<!-- general form elements -->
				<div class="box custom-box-list">
				<div class="box-header with-border">
				  <h3 class="box-title">세트정보</h3>
				</div>
				<!-- /.box-header -->

				<!-- form start -->
				<form role="form" action="action/act.goods.set.php" method="post" id="data_form" class="form-horizontal">
				<input type="hidden" id="bbsSeq" name="bbsSeq" value="{$bbsSeq}" />
				<input type="hidden" name="page_type" value="admin" />
				<input type="hidden" id="mode" name="mode" value="{$mode}" />
				
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 전시회이름</label>
						<div class="col-sm-8">
							<select name="setFairSeq" id="setFairSeq" class="form-control select2" style="width: 100%;" {if $mode=="mod"}disabled{/if}>
								<option value="">전시회를 선택해 주세요</option>
							{foreach $bbsFair as $key => $val}
								<option value="{$val.fair_seq}" {if $mode=='mod' && $val.fair_seq==$bbs.set_fair_seq}selected{/if}>{$val.fair_title}</option>
							{/foreach}
							</select>
						</div>
					</div>
					<!-- /.form group -->

					<div class="form-group">
						<label class="col-sm-2 control-label" for="setTitle"><i class="fa fa-check custom-input-title"></i> 세트이름</label>
						<div class="col-sm-8">
							<input name="setTitle" type="text" class="form-control" id="setTitle" placeholder="세트이름을 적어주세요" {if !empty($bbs.set_title)}value="{$bbs.set_title}"{/if}>
						</div>
					</div>
					<!-- /.form group -->

					<div class="form-group">
						<label class="col-sm-2 control-label" for="setGoodsCnt"><i class="fa fa-check custom-input-title"></i> 세트 내 상품수</label>
						<div class="col-sm-8">
							<input name="setGoodsCnt" type="number" class="form-control" id="setGoodsCnt" placeholder="세트 내 상품수를 적어주세요" {if !empty($bbs.set_goods_cnt)}value="{$bbs.set_goods_cnt}"{/if}>
						</div>
					</div>
					<!-- /.form group -->

				</div>
				<!-- /.box-body -->

				<div class="box-footer">
					<button class="btn btn-sm btn-primary" onClick="funcAct('add','data_form');return false;"><i class="fa fa-edit"></i> {if $mode == "add"}세트정보 추가{else}세트정보 저장{/if}</button>
					{if $mode == "mod" || $mode == "del"}
					<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i> 세트정보 삭제</button>
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
									<p>현재 세트정보를 정말로 삭제 하시겠습니까?</p>
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
				<!-- /.box-footer -->
				</form>
				<!-- /form end -->
				</div>
				<!-- /.box -->
			</div>
			<!--/.col (top) -->


{if $mode=="mod"}
			<!-- right column -->
			<div class="col-md-12">
			  <div class="box box-success">
				<div class="box-header with-border">
				  <h3 class="box-title">상품 및 가격정보</h3>
				</div>
				<!-- /.box-header -->

				<!-- form start -->
					<div class="box-body">

			<div class="col-sm-12 form-inline">
			<form name="search_form" id="search_form" method="get" action="{$phpSelf}">
			<input type="hidden" id="bbsSeq" name="bbsSeq" value="{$bbsSeq}" />
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
			</form>
			</div>


			<div class="col-sm-12 custom-marginTop-table">
				<table class="table table-bordered table-striped">
					<colgroup>
						<col style="width:5%" />
						<col style="width:25%" />
						<col style="width:15%" />
						<col style="width:15%" />
						<col style="width:15%" />
						<col style="width:15%" />
						<col style="width:10%" />
					</colgroup>
					<thead>
						<tr class="odd">
							<th class="custom-table-textAlign">SEQ</th>
							<th class="custom-table-textAlign">상품이름</th>
							<th class="custom-table-textAlign">정가</th>
							<th class="custom-table-textAlign">판매가</th>
							<th class="custom-table-textAlign">세트가</th>
							<th class="custom-table-textAlign">할인율</th>
							<th class="custom-table-textAlign">상세보기</th>
						</tr>
					</thead>
					{if !empty($bbsSetOptionList)}
					<tbody class="custom-table-textAlign">
						{foreach $bbsSetOptionList as $key => $val}
						<tr class="odd">
							<td>{$val.set_option_seq}</td>
							<td class="custom-table-textAlignCancel">{$val.goods_title}</td>
							<td>{$val.price_goods_consumer|number_format} 원</td>
							<td>{$val.price_goods_price|number_format} 원</td>
							<td>{($val.price_goods_consumer-($val.price_goods_consumer*$val.set_option_percent/100))|number_format} 원</td>
							<td>{$val.set_option_percent}%</td>
							<td>
								<a href="goodsSetOptionDetail.php?bbsSeq={$val.set_option_seq}"><i class="fa  fa-share-square-o"></i> 상세보기</a>
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
			</div>

					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<a href="goodsSetOptionDetail.php?setSeq={$bbsSeq}&setFairSeq={$bbs.set_fair_seq}&mode=add" class="btn btn-sm btn-success"><i class="fa fa-slack"></i> 상품 및 가격정보 추가</a>
					</div>

			  </div>
			  <!-- /.box -->

			</div>
{else}

			

			<!-- right column -->
			<div id="tempList" class="col-md-12">
				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">상품 및 가격정보</h3>
					</div>
				<!-- /.box-header -->

				<!-- form start -->
					<div class="box-body">

						<div class="col-sm-12 custom-marginTop-table">
							<table class="table table-bordered table-striped">
								<colgroup>
									<col style="width:5%" />
									<col style="width:25%" />
									<col style="width:15%" />
									<col style="width:15%" />
									<col style="width:15%" />
									<col style="width:15%" />
									<col style="width:10%" />
								</colgroup>
								<thead>
									<tr class="odd">
										<th class="custom-table-textAlign">SEQ</th>
										<th class="custom-table-textAlign">상품이름</th>
										<th class="custom-table-textAlign">정가</th>
										<th class="custom-table-textAlign">판매가</th>
										<th class="custom-table-textAlign">세트가</th>
										<th class="custom-table-textAlign">할인율</th>
										<th class="custom-table-textAlign">상세보기</th>
									</tr>
								</thead>
							</table>
						</div><!--col-sm-12-->
					</div><!--/box-body-->

				</div>
				<!-- /.box -->
			</div>
{/if}

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
		if (formCheck.isNull($('#setFairSeq'), '전시회를 선택해 주세요.', true)) return false;
		if (formCheck.isNull($('#setTitle'), '세트이름을 입력해 주세요.', true)) return false;
		if (formCheck.isNull($('#setGoodsCnt'), '세트 내 상품수를 입력해 주세요.', true)) return false;
	}else if(mode == 'del'){
		$('#mode').val('del');
	}
	
	$('#'+formName).submit();
	return false;
}



</script>
</body>
</html>