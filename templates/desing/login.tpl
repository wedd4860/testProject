{include file="header.tpl"}

<body class="hold-transition login-page">
<div class="login-box">
	<div class="login-logo">
		<a href="{$siteUrl}"><b>FORB</b>(admin)</a>
	</div>
	<!-- /.login-logo -->

	<div class="login-box-body">
	<p class="login-box-msg">아이디 및 비밀번호를 입력해 주세요.</p>
		<form action="action/act.login.php" method="post" role="form" id="data_form">
			<div class="form-group has-feedback">
				<input type="text" class="form-control" placeholder="아이디" name="id" id="idCheck">
				<span class="glyphicon glyphicon-user form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" class="form-control" placeholder="패스워드" name="password" id="passCheck">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
						<label>
							<input type="checkbox" id="idSave"> 아이디 기억하기
						</label>
					</div>
				</div>
				<!-- /.col -->
				<div class="col-xs-4">
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

	//아디저장
	if (getCookie("id")) {
		$('#idCheck').val(getCookie("id"));
		$('#idSave').iCheck('check');
	}
});

function funcAct(formName){
	if (formCheck.isNull($('#idCheck'), '아이디를 입력해 주세요.', true)) return false;
	if (formCheck.isNull($('#passCheck'), '패스워드를 입력해 주세요.', true)) return false;
	
	if ($("#idSave").is(":checked") == true) { // 아이디 저장을 체크 하였을때
		setCookie("id", $('#idCheck').val(), 7); //쿠키이름을 id로 아이디입력필드값을 7일동안 저장
	} else { // 아이디 저장을 체크 하지 않았을때
		setCookie("id", $('#idCheck').val(), 0); //날짜를 0으로 저장하여 쿠키삭제
	}


	$('#'+formName).submit();
	return false;
}


function setCookie(name, value, expiredays){
	var todayDate = new Date();
	todayDate.setDate(todayDate.getDate() + expiredays);
	document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function getCookie(Name) {
	var search = Name + "=";
	if (document.cookie.length > 0) { // if there are any cookies
		offset = document.cookie.indexOf(search);
		if (offset != -1) { // if cookie exists
			offset += search.length; // set index of beginning of value
			end = document.cookie.indexOf(";", offset); // set index of end of cookie value
			if (end == -1)
				end = document.cookie.length;
			return unescape(document.cookie.substring(offset, end));
		}
	}
}

</script>
</body>
</html>
