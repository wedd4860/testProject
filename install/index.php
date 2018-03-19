<?php
ini_set('display_errors', true);
//initSet에서 _INCLUDE_ROOT 를 정의
include "../include/init.php";
//DB 사용시에만 include
include _INCLUDE_ROOT."init.dbPDO.php";
$database = new Database(_DB_NAME);
include _INCLUDE_ROOT."init.utils.php";

if(is_file('license.txt')){
	$database = null;//db connection close
	pageRedirect('../login.php','프로그램이 이미 설치되어 있습니다.');
	exit;
}else{
	// 테이블 생성 ------------------------------------
	$file = implode('', file('./forbOrder.sql'));
	eval("\$file = \"$file\";");

	$f = explode(';', $file);
	for ($i=0; $i<count($f); $i++) {
		if (trim($f[$i]) == '') continue;
		$database->prepare($f[$i]);
		$database->execute();
	}
	// 테이블 생성 ------------------------------------

	// 파일 생성 ------------------------------------
	$license = fopen("license.txt", "w") or die("Unable to open file!");
	$txt = date("Y-m-d H:i:s",time())." 에 라이센스에 동의하였습니다.";
	fwrite($license, $txt);
	fclose($license);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>AdminLTE 2 | Lockscreen</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="../dist/css/base.css">
	<link rel="stylesheet" href="../dist/css/main.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
	<div class="lockscreen-logo">
		<a href="../login.php"><b>FORB</b>(admin)</a>
	</div>

	<div class="text-center custom-install-title">
		테이블 생성이 완료되었습니다.
	</div>

	<div class="lockscreen-item">
		<a href="../login.php" class="btn btn-default btn-sm btn-block">로그인 페이지 이동</a>
	</div>
	<div class="lockscreen-footer text-center">
		© FORB, All rights reserved.
	</div>
</div>
<!-- /.center -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
