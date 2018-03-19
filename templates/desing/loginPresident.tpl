{include file="header.tpl"}

<body class="hold-transition login-page">
<div class="login-box">
	<div class="login-logo">
		<a href="{$siteUrl}"><b>FORB</b>(admin)</a>
	</div>
	<!-- /.login-logo -->

	<div class="login-box-body">
	<p class="login-box-msg">비밀번호를 입력해 주세요.</p>
		<form action="action/act.loginPresident.php" method="post" role="form" id="data_form">
			<div class="form-group has-feedback">
				<input type="password" class="form-control" placeholder="패스워드" name="password" id="passCheck">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
				<!-- /.col -->
				<div class="col-xs-12">
					<button type="submit" class="btn btn-primary btn-block btn-flat" onClick="funcAct('data_form');return false;">Sign In</button>
				</div>
				<!-- /.col -->
			</div>
		</form>

	</div>
	<!-- /.login-box-body -->
</div>
<!-- /.login-box -->


<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>

<!-- utils : 검증 -->
<script src="plugins/utils/utils.js"></script>
<script>
$(function () {
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
		increaseArea: '20%' // optional
	});
});

function funcAct(formName){
	if (formCheck.isNull($('#passCheck'), '패스워드를 입력해 주세요.', true)) return false;

	$('#'+formName).submit();
	return false;
}
</script>
</body>
</html>
