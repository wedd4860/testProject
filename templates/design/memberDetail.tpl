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
			<li><a href="memberDetail.php"><i class="fa fa-laptop"></i>정보관리</a></li>
		</ol>
	</section>

<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#member" data-toggle="tab">내정보 관리</a></li>
					<li><a href="#fair" data-toggle="tab">전시회 관리</a></li>
				</ul>
			<div class="tab-content">

			<div class="tab-pane active" id="member">
				<form class="form-horizontal" role="form" action="action/act.member.php" method="post" id="data_form_member">
					<input type="hidden" name="mode" value="member" />
					<input type="hidden" id="memberSeq" name="memberSeq" value="{$login.seq}" />
					<input type="hidden" name="page_type" value="admin" />
					<div class="form-group">
						<label for="inputName" class="col-sm-2 control-label">이름</label>
						<div class="col-sm-5">
							<input type="type" class="form-control" placeholder="이름" value="{$login.name}" disabled>
						</div>
					</div><!--/end form-group-->

					<div class="form-group">
						<label for="inputName" class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 닉네임</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="inputName" id="memberNick" name="memberNick" placeholder="변경할 닉네임을 입력해 주세요." value="{$login.nickName}">
						</div>
					</div><!--/end form-group-->

					<div class="form-group">
						<label for="inputExperience" class="col-sm-2 control-label">비밀번호 변경</label>
						<div class="col-sm-5">
							<input type="password" name="memberPwd" id="memberPwd" class="form-control" id="inputName" placeholder="변경할 비밀번호를 입력해 주세요." value="">
						</div>
					</div><!--/end form-group-->

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button class="btn btn-sm btn-primary" onClick="funcAct('member','data_form_member');return false;"><i class="fa fa-edit"></i> 내정보 수정</button>
						</div>
					</div><!--/end form-group-->
				</form>
			</div>
			<!-- /.tab-pane member-->

			<div class="tab-pane" id="fair">
				<form class="form-horizontal" role="form" action="action/act.member.php" method="post" id="data_form_fair">
					<input type="hidden" name="mode" value="fair" />
					<input type="hidden" name="page_type" value="admin" />
					
					<div class="form-group">
						<label for="inputName" class="col-sm-2 control-label"><i class="fa fa-check custom-input-title"></i> 현재 설정된 전시회</label>
						<div class="col-sm-5">
							<select name="setFairSeq" id="setFairSeq" class="form-control select2" style="width: 100%;">
								<option value="">전시회를 선택해 주세요</option>
							{foreach $bbsFair as $key => $val}
								<option value="{$val.fair_seq}" 
								{if $val.fair_seq == $bbsSetting.setting_fair_seq }selected{/if}
								>{$val.fair_title}</option>
							{/foreach}
							</select>
						</div>
					</div><!--/ end form-group-->

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-5">
							<button class="btn btn-sm btn-primary" onClick="funcAct('fair','data_form_fair');return false;"><i class="fa fa-edit"></i> 현재 전시회 정보 수정</button>
						</div>
					</div><!--/ end form-group-->
				</form>
			</div>
			<!-- /.tab-pane fair -->
			</div>
			<!-- /.tab-content -->
			</div>
			<!-- /.nav-tabs-custom -->
		</div>
		<!-- /.col -->
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
	if(mode == 'member'){
		if (formCheck.isNull($('#inputName'), '닉네임을 입력해 주세요.', true)) return false;
	}else if(mode == 'fair') {
		if (formCheck.isNull($('#setFairSeq'), '전시회를 선택해 주세요.', true)) return false;
	}
	
	$('#'+formName).submit();
	return false;
}
</script>
</body>
</html>