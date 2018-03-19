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
						<input type="hidden" id="bbsName" name="bbsSeq" value="{if !empty($bbsSeq)}{$bbsSeq}{/if}" />
						<input type="hidden" name="page_type" value="admin" />
						<input type="hidden" id="mode" name="mode" value="{if !empty($mode)}{$mode}{/if}" />
						<div class="box-body">
						{if !empty($bbs.category_code)}
							<div class="form-group">
								<label class="col-sm-2 control-label">상품 카테고리 코드</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" value="{$bbs.category_code}" disabled>
								</div>
							</div>
						{/if}
							<div class="form-group">
								<label for="categoryTitle" class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 상품 카테고리명</label>
								<div class="col-sm-5">
									<input name="categoryTitle" type="text" class="form-control" id="categoryTitle" placeholder="상품 카테고리명을 입력해 주세요." value="{if !empty($bbs.category_title)}{$bbs.category_title}{/if}">
								</div>
						{if !empty($bbs.category_code)}
							{if {$bbs.category_code|count_characters} < 4}
							<a href="./categoryDetailSub.php?bbsCode={$bbs.category_code}" class="btn btn-success"><i class="fa fa-slack"></i> 하위 카테고리 추가</a>
							{/if}
						{/if}
							</div>
						</div>
						<!-- /.box-body -->

						<div class="box-footer">
							<button class="btn btn-sm btn-primary" onClick="funcAct('add','data_form');return false;"><i class="fa fa-edit"></i> {if $mode == "add"}
								상품 카테고리 추가
							{else}
								카테고리 저장
							{/if}
							</button>
							{if $mode == "mod"}
							<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i> 카테고리 삭제</button>
							<div class="modal modal-danger fade" id="modal-danger">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											<h4 class="modal-title">상품 카테고리 삭제</h4>
										</div>
										<div class="modal-body">
											<p>현재 카테고리를 정말로 삭제 하시겠습니까?</p>
											<br>
											<p>상품정보 관리의 내역이 존재하는 경우 삭제되지 않습니다.</p>
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
<!-- utils : 검증 -->
<script src="plugins/utils/utils.js"></script>
<script>
function funcAct(mode, formName){
	if(mode == 'mod' || mode == 'add'){
		if (formCheck.isNull($('#categoryTitle'), '카테고리 이름을 입력해 주세요.', true)) return false;
	}else if(mode == 'del'){
		$('#mode').val('del');
	}
	
	$('#'+formName).submit();
	return false;
}
</script>
</body>
</html>