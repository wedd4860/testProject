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
	<span class="header-close" onClick="window.close();">X</span>
</header><!---->

<div id="content">
	<section class="report">
		<div class="after row">
			<h3>{$date} 판매 상품</h3>
			<p class="report-subTitle">(부가세 포함)</p>
			<div class="price col-md-12">
				<table class="table table-bordered table-striped">
					<colgroup>
						<col style="width:35%">
						<col style="width:30%">
						<col style="width:35%">
					</colgroup>
					<thead>
						<tr class="odd">
							<th>카테고리명</th>
							<th>수량</th>
							<th>합계</th>
						</tr>
					</thead>
					<tbody>
{foreach $bbsList as $key => $val}
						<tr class="odd">
							<td>{$val.category_title}</td>
							<td class="goods-infoEa">{$val.ea}</td>
							<td>{$val.price|number_format}원</td>
						</tr>
{/foreach}
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div><!--content-->

</body>
</html>