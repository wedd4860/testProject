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
			<li><a href="{$categoryUrl}"><i class="fa  fa-th-large"></i>상품 카테고리</a></li>
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
						<h3 class="box-title">카테고리 등록</h3>
					</div>
					<!-- /.box-header -->

					<!-- form start -->
					<form role="form" action="action/act.category.php" method="post" id="data_form" class="form-horizontal">
						<input type="hidden" name="page_type" value="admin" />
						<input type="hidden" id="mode" name="mode" value="{if !empty($pageInfo)}{$pageInfo}{/if}" />
						<div class="box-body">
						{if !empty($newCateCode)}
							<div class="form-group">
								<label for="categoryCode" class="col-sm-2 control-label">상품 카테고리 코드</label>
								<div class="col-sm-5">
									<input type="text" id="categoryCode" name="categoryCode" class="form-control" value="{$newCateCode}" disabled>
								</div>
							</div>
						{/if}
							<div class="form-group">
								<label for="categoryTitle" class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 상품 카테고리명</label>
								<div class="col-sm-5">
									<input name="categoryTitle" type="text" class="form-control" id="categoryTitle" placeholder="하위 카테고리명을 입력해 주세요.">
								</div>
							</div>
						</div>
						<!-- /.box-body -->

						<div class="box-footer">
							<button class="btn btn-sm btn-primary" onClick="funcAct('sub','data_form');return false;"><i class="fa fa-edit"></i> 상품 카테고리 추가</button>
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
<!-- utils : 검증 -->
<script src="plugins/utils/utils.js"></script>
<script>
function funcAct(mode, formName){
	if(mode == 'sub'){
		if (formCheck.isNull($('#categoryTitle'), '카테고리 이름을 입력해 주세요.', true)) return false;
	}
	$("#categoryCode").attr("disabled",false);
	$('#'+formName).submit();
	return false;
}
</script>
</body>
</html>