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
						<h3 class="box-title">주문 정보 리스트</h3>
					</div>

					<div class="box-search">
						<form name="search_form" id="search_form" method="get" action="{$phpSelf}">
						<div class="col-sm-2 form-inline">
							<div class="form-group">
								<select id="fair_ea" name="s_ea" class="form-control custom-border-radius input-sm">
									{foreach $bbsEa as $key=>$val}
										<option value="{$val}" 
										{if $s_ea == $val}selected="selected"{/if}
										>{$val}</option>
									{/foreach}
								</select>
							</div>
							<button class="btn btn-sm btn-default" type="submit">보기</button>
						</div>
						<div class="col-sm-10">
							<div class="input-group input-group-sm col-sm-4 pull-right">
								<input id="fair_title" name="s_title" type="text" {if $s_title}value="{$s_title}"{/if} placeholder="전시회 이름, 고객이름, 고객휴대폰 으로 검색" class="form-control">
								<span class="input-group-btn">
									<button type="submit" class="btn btn-sm btn-default btn-flat"><i class="fa fa-search"></i></button>
								</span>
							</div>
						</div>
						</form>
					</div><!--/end box-search-->

					<div class="col-sm-12 custom-marginTop-table">
						<table class="table table-bordered table-striped">
							<colgroup>
								<col style="width:5%" />
								<col style="width:6%" />
								<col style="width:20%" />
								<col style="width:9%" />
								<col style="width:15%" />
								<col style="width:10%" />
								<col style="width:10%" />
								<col style="width:15%" />
								<col style="width:10%" />
							</colgroup>
							<thead>
								<tr class="odd">
									<th class="custom-table-textAlign">SEQ</th>
									<th class="custom-table-textAlign">상태</th>
									<th class="custom-table-textAlign">전시회 이름</th>
									<th class="custom-table-textAlign">고객이름</th>
									<th class="custom-table-textAlign">고객휴대폰</th>
									<th class="custom-table-textAlign">총 정가</th>
									<th class="custom-table-textAlign">총 결제가</th>
									<th class="custom-table-textAlign">주문일</th>
									<th class="custom-table-textAlign">상세보기</th>
								</tr>
							</thead>
							{if $bbsList}
							<tbody class="custom-table-textAlign">
								{foreach $bbsList as $key => $val}
								<tr class="odd">
									<td>{$val.order_seq}</td>
									<td>{$val.order_status}</td>
									<td class="custom-table-textAlignCancel">{$val.fair_title}</td>
									<td>{$val.order_customer}</td>
									<td>{$val.order_mobile}</td>
									<td>{$val.order_tot_orgprice|number_format} 원</td>
									<td>{$val.order_tot_settleprice|number_format} 원</td>
									<td>{$val.order_regdt}</td>
									<td>
										<a href="orderSettle.php?bbsSeq={$val.order_seq}"><i class="fa fa-share-square-o"></i> 상세보기
										</a>
									</td>
								</tr>
								{/foreach}
							</tbody>
							{/if}
						</table>
					</div><!--col-sm-12-->

					<div class="col-sm-6">

{if $pageCount > 0 && $pageLoop > 0}
	<ul class="pagination custom-zero-margin">
	{if $pageList.prev.url}
		<li><a href="{$pageList.prev.url}">&laquo;</a></li>
	{/if}
	{for $i=1 to $pageLoop}
		{if is_int($p) && $p == $pageList[$i].name}
		<li><a href="#">{$p}</a></li>
		{else}
		<li><a href="{$pageList[$i].url}">{$pageList[$i].pageNo}</a></li>
		{/if}
	{/for}
	{if $pageList.next.url}
		<li><a href="{$pageList.next.url}">&raquo;</a></li>
	{/if}
	</ul>
{/if}
					</div>
					<div class="col-sm-6">
						<a href="orderDetail.php" class="btn btn-primary btn-sm pull-right"><i class="fa  fa-edit"></i> 주문서 작성</a>
					</div>
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

</script>
</body>
</html>