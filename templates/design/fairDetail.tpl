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
			<li><a href="{$fairUrl}"><i class="fa fa-calendar"></i>전시회</a></li>
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
				  <h3 class="box-title">전시회 정보</h3>
				</div>
				<!-- /.box-header -->

				<!-- form start -->
				<form role="form" action="action/act.fiar.php" method="post" id="data_form" class="form-horizontal">
					<input type="hidden" name="bbsSeq" id="bbsSeq" value="{if !empty($bbsSeq)}{$bbsSeq}{/if}" />
					<input type="hidden" name="page_type" value="admin" />
					<input type="hidden" name="mode" id="mode" value="{$mode}" />
					<div class="box-body">
						<div class="form-group">
							<label for="fairTitle" class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 전시회 이름</label>
							<div class="col-md-8">
								<input name="fairTitle" type="text" class="form-control" id="fairTitle" placeholder="전시회 이름을 적어주세요" value="{if !empty($bbs.fair_title)}{$bbs.fair_title}{/if}">
							</div>
						</div>

						<!-- Date range -->
						<div class="form-group">
							<label class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 전시회 기간</label>
							<div class="col-sm-8">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input name="dateRange" type="text" class="form-control pull-right" id="reservation">
								</div>
								<!-- /.input group -->
							</div>
						</div>
						<!-- /.form group -->
					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<button class="btn btn-sm btn-primary" onClick="funcAct('add','data_form');return false;"><i class="fa fa-edit"></i> 
						{if $mode == "add"}
							전시회 정보 추가
						{else}
							전시회 정보 저장
						{/if}
						</button>
						{if $mode == "mod"}
						<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i> 전시회 정보 삭제</button>
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
										<p>현재 전시회 정보를 정말로 삭제 하시겠습니까?</p>
										<br>
										<p>주문관리, 세트정보 관리, 상품정보 관리의 가격정보 중 하나라도 내역이 존재하는 경우 삭제되지 않습니다.</p>
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
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<!-- date-range-picker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap Date picker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- utils : 검증 -->
<script src="plugins/utils/utils.js"></script>
<script>
$(function () {
	//Date range picker
	$('#reservation').daterangepicker({
		locale: {
			format: 'YYYY-MM-DD',
			"separator": " ~ ",
			"daysOfWeek": ["일","월","화","수","목","금","토"],
			"monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
		},
		{if !empty($stdDate) }
			"startDate":"{$stdDate}","endDate":"{$endDate}",
		{/if}
	});
})

function funcAct(mode, formName){
	if(mode == 'mod' || mode == 'add'){
		if (formCheck.isNull($('#fairTitle'), '전시회 이름을 입력해 주세요.', true)) return false;
	}else if(mode == 'del'){
		$('#mode').val('del');
	}
	
	$('#'+formName).submit();
	return false;
}
</script>
</body>
</html>