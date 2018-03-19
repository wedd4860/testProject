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
			<li><a href="{$orderUrl}"><i class="fa fa-krw"></i>주문</a></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">

		<!-- contents start -->
		<div class="row">
			<div class="col-md-12">

				<div class="box custom-box-list">
				<div class="box-body">
					<div class="box-header">
						<h3 class="box-title">판매통계</h3>
					</div>
					
					<div class="box-search">
						<form name="data_form" id="data_form" method="post" action="">
						<input type="hidden" name="mode" id="mode" value="{$mode}">
						<div class="col-md-12">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input name="dateRange" type="text" class="form-control pull-right" id="reservation" value="{if !empty($dateRange)}{$dateRange}{/if}">
								<span class="input-group-btn">
									<button class="btn btn-primary" onclick="funcAct('down','data_form');return false;">다운로드</button>
									<button class="btn btn-primary" onclick="funcAct('search','data_form');return false;">검색</button>
								</span>
							</div>
							<!-- /.input group -->
						</div>
					</div>



					<div class="col-sm-12 custom-marginTop-table">
{if $mode == 'search'}
	{if $cateList}
<div class="table-responsive statistics-wrap" id="exData">
						<table class="statistics-table table">
							<colgroup>
								<col style="width:100px">
								<col style="width:100px">
								<col style="width:120px">
							{foreach $dateList as $dateKey => $dateVal}
								<col style="width:40px">
								<col style="width:120px">
							{/foreach}{*$dateList*}
								<col style="width:40px">
								<col style="width:120px">
							</colgroup>
							<thead>
								<tr>
									<th rowspan="2">구분</th>
									<th rowspan="2">상품명</th>
									<th rowspan="2">상품명(색상)</th>
								{foreach $dateList as $dateKey => $dateVal}
									<th colspan="2">{$dateVal}</th>
								{/foreach}{*$dateList*}
									<th colspan="2">합계</th>
								</tr>
								<tr>
								{foreach $dateList as $dateKey => $dateVal}
									<th>수량</th>
									<th>결제가</th>
								{/foreach}{* dateList *}
									<!-- 합계 -->
									<th>수량</th>
									<th>결제가</th>
								</tr>
							</thead>
							<tbody>
{foreach $cateList as $cateKey => $cateVal}
{if $cateCountList[$cateVal.category_code].cateCount>0}
{* 코드번호 : $cateVal.category_code*}
								<tr>
{*START 카운트를 위한 값설정*}
{assign var="cateTrCount" value="0"}
{foreach $cateDetailList[$cateVal.category_code] as $cateDetailKey => $cateDetailVal}
{if $cateDetailCountList[$cateDetailVal.category_code].cateCount > 0}
{$cateTrCount=$cateTrCount+1}
{/if}
{/foreach}
{*END 카운트를 위한 값설정*}
									<td rowspan="{$cateCountList[$cateVal.category_code].cateCount + $cateTrCount}">{$cateVal.category_title}</td>
									
		{foreach $cateDetailList[$cateVal.category_code] as $cateDetailKey => $cateDetailVal}
		{if $cateDetailCountList[$cateDetailVal.category_code].cateCount > 0}

									<td rowspan="{$cateDetailCountList[$cateDetailVal.category_code].cateCount}">{$cateDetailVal.category_title}{*상품명*}</td>
								
				{foreach $goodsList[$cateDetailVal.category_code] as $goodsKey => $goodsVal}
					{if $goodsKey != "0"}
								<tr>
					{/if}
									<td>{$goodsVal.goods_title}{*상품명(색상)*}</td>
						{assign var="addGoodsEa" value="0"}{*ea합계*}
						{assign var="addGoodsPice" value="0"}{*price합계*}
						{foreach $dateList as $dateKey => $dateVal}
						{*$dateVal :: 2018-01-10*}
									<td>
									{if $orderGoodsList[$dateVal][$goodsVal.goods_seq].ea}
											{$addGoodsEa=$addGoodsEa+$orderGoodsList[$dateVal][$goodsVal.goods_seq].ea}
											{$orderGoodsList[$dateVal][$goodsVal.goods_seq].ea}
									{else}-{/if}</td>
									<td>
										{if $orderGoodsList[$dateVal][$goodsVal.goods_seq].price}
											{$addGoodsPice=$addGoodsPice+$orderGoodsList[$dateVal][$goodsVal.goods_seq].price}
											{$orderGoodsList[$dateVal][$goodsVal.goods_seq].price|number_format}
									{else}-{/if}</td>
						{/foreach}{* $dateList *}
									<td class="table-statistics-total">{$addGoodsEa}</td>
									<td class="table-statistics-total table-subTitle">
										<span class="table-statistics-subTitle">{$goodsVal.goods_title}</span>
										{$addGoodsPice|number_format}
									</td>
								</tr>
				{/foreach}{* $goodsList[$cateDetailVal.category_code] *}
								<tr class="table-statistics-total">
									<td colspan="2">{$cateDetailVal.category_title} 소계{*상품 소계*}</td>
						{assign var="addTotalEa" value="0"}{*ea합계*}
						{assign var="addTotalPice" value="0"}{*price합계*}
						{foreach $dateList as $dateKey => $dateVal}
									<td>
									{if $orderCateList[$dateVal][$cateDetailVal.category_code].ea}
											{$addTotalEa=$addTotalEa+$orderCateList[$dateVal][$cateDetailVal.category_code].ea}
											{$orderCateList[$dateVal][$cateDetailVal.category_code].ea}
									{else}-{/if}</td>
									<td>
									{if $orderCateList[$dateVal][$cateDetailVal.category_code].price}
											{$addTotalPice=$addTotalPice+$orderCateList[$dateVal][$cateDetailVal.category_code].price}
											{$orderCateList[$dateVal][$cateDetailVal.category_code].price|number_format}
									{else}-{/if}</td>
						{/foreach}{* $dateList *}
									<td class="table-statistics-total">{$addTotalEa}</td>
									<td class="table-statistics-total table-subTitle">
										<span class="table-statistics-subTitle">{$cateDetailVal.category_title} 소계</span>
										{$addTotalPice|number_format}
									</td>
								</tr>
		{/if}
		{/foreach}{* $cateDetailList[$cateVal.category_code] *}
{/if}
{/foreach}
							</tbody>
						</table>
</div>
	{/if}{* $cateList *}
{/if}{* $mode *}
					</div><!--col-sm-12-->

				</div><!--box-body-->
				</div><!--box-->

			</div><!-- /end col-md-12-->
		</div>
		<!-- /end row -->

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
		}
	});
})

function funcAct(mode, formName){
	if(mode == 'down'){
		$('#'+formName).attr('action', 'download/excel/index.php');
		$("#mode").val('down');
	}else if(mode == 'search'){
		$('#'+formName).attr('action', '{$phpSelf}');
		$("#mode").val('search');
	}
	$('#'+formName).submit();
	return false;
}
</script>
</body>
</html>