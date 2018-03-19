<body class="hold-transition skin-forb sidebar-mini">
<div class="wrapper">

<!-- Main Header -->
<header class="main-header">
	<a href="{$siteUrl}" class="logo">
		<span class="logo-lg"><b>FORB</b>(admin)</span>
	</a>

	<nav class="navbar navbar-static-top" role="navigation">
		<div class="navbar-custom-fair">
			<p>현재 설정된 전시회 정보 : {$currentFairTitle}</p>
		</div>

		<div class="navbar-custom-logout">
			<a href="{$logout}"><i class="fa fa-sign-out"></i>Log out</a>
		</div>
	</nav><!--/end navbar-->
</header><!--/end header-->


<aside class="main-sidebar">

	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left forb-logo">
				<img src="./dist/img/logo.png" alt="log">
			</div>
			<div class="pull-left info">
				<p class="member-name">{$login.name}</p>
				<!-- Status -->
				<p><a href="{$memberUrl}"><i class="fa fa-circle text-forb"></i> {$login.nickName}</a></p>
			</div>
		</div><!--/end user-panel -->

		<ul class="sidebar-menu" data-widget="tree">
			{foreach $navGroup as $key => $val}
				{if $val.level <= $login.level}
					{if $pageVal == $key}
						{$navActive="active"}
					{else}
						{$navActive=""}
					{/if}
					<li class="{$navActive}"><a href="{$val.href}"><i class="fa fa-{$val.fa}"></i> <span>{$val.title}</span></a></li>
				{/if}
			{/foreach}
		</ul>
		<!-- /.sidebar-menu -->
	</section>
	<!-- /.sidebar -->
</aside><!--/end main-sidebar -->