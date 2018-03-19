<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>FORB 관리자 사이트</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=2" name="viewport">
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="dist/css/president.css?v=0.1">
</head>
<body>
<header id="header">
	<h2>전시회 매출 자료</h2>
</header><!---->

<div id="content">
	<section class="report">
		<div class="after row">
			<h3>{$currentList.fair_title}</h3>
			<p class="report-subTitle">(부가세 포함)</p>
			<div class="price col-md-12">
				<table class="table table-bordered table-striped">
					<colgroup>
						<col style="width:40%">
						<col style="width:60%">
					</colgroup>
					<thead>
						<tr class="odd">
							<th>날짜</th>
							<th>매출</th>
						</tr>
					</thead>
					<tbody>
{assign var="addCurrent" value="0"}{*합계*}
{foreach $dateCurrent as $key => $val}
						<tr class="odd">
							<td><a href="presidentInfo.php?date={$val}" target="_blank">{$val}</a></td>
							<td>
							{if $currentOrderList[$val]}
								{$addCurrent=$addCurrent+$currentOrderList[$val].tot_price}
								{$currentOrderList[$val].tot_price|number_format}원
							{else}
								0원
							{/if}
							</td>
						</tr>
{/foreach}
						</tr>
					</tbody>
				</table>
			</div>
			<p class="total-price">총합계 : {$addCurrent|number_format}원</p>
		</div>
	</section>

	
	<section class="report">
		<div class="before row">
			<h3>참고 : {$beforeList.fair_title}</h3>
			<p class="report-subTitle">(부가세 포함)</p>
			<div class="price col-md-12">
				<table class="table table-bordered table-striped">
					<colgroup>
						<col style="width:40%">
						<col style="width:60%">
					</colgroup>
					<thead>
						<tr class="odd">
							<th>날짜</th>
							<th>매출</th>
						</tr>
					</thead>
					<tbody>
{assign var="addBefore" value="0"}{*합계*}
{foreach $dateBefore as $key => $val}
						<tr class="odd">
							<td><a href="presidentInfo.php?date={$val}" target="_blank">{$val}</a></td>
							<td>
							{if $beforeOrderList[$val]}
								{$addBefore=$addBefore+$beforeOrderList[$val].tot_price}
								{$beforeOrderList[$val].tot_price|number_format}원
							{else}
								0원
							{/if}
							</td>
						</tr>
{/foreach}
					</tbody>
				</table>
				<p class="total-price">총합계 : {$addBefore|number_format}원</p>
			</div>
		</div>
	</section>
</div><!--content-->

</body>
</html>