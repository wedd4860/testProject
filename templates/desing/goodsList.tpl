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
			<li><a href="./goodsList.php"><i class="fa fa-file-o"></i>상품정보</a></li>
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
						<h3 class="box-title">상품정보 리스트</h3>
					</div>

					<div class="box-search">
						<form name="search_form" id="search_form" method="get" action="{$phpSelf}">
						<div class="col-sm-4 form-inline">
							<div class="form-group">
								<select id="goods_ea" name="s_ea" class="form-control custom-border-radius input-sm">
									{foreach $bbsEa as $key=>$val}
										<option value="{$val}" 
										{if $s_ea == $val}selected="selected"{/if}
										>{$val}</option>
									{/foreach}
								</select>
							</div><!--/ end form-group-->
							<div class="form-group">
								<select id="goods_cate" name="s_cate" class="form-control custom-border-radius input-sm">
										<option value="">카테고리 선택</option>
									{foreach $bbsListCate as $key=>$val}
									{if $val.category_code|count_characters > 3}
										<option value="{$val.category_code}" 
										{if $s_cate == $val.category_code}selected="selected"{/if}
										>{$val.category_title}</option>
									{/if}
									{/foreach}
								</select>
							</div><!--/ end form-group-->
							<button class="btn btn-sm btn-default" type="submit">보기</button>
						</div>

						<div class="col-sm-8">
							<div class="input-group input-group-sm col-sm-6 pull-right">
								<input id="fair_title" name="s_title" type="text" {if !empty($s_title)}value="{$s_title}"{/if} placeholder="상품이름, 모델명, 상품코드로 검색" class="form-control">
								<span class="input-group-btn">
									<button type="submit" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
								</span>
							</div>
						</div>
						</form>
					</div><!--/ end box-search-->

					<div class="col-sm-12 custom-marginTop-table">
						<table class="table table-bordered table-striped">
							<colgroup>
								<col style="width:5%" />
								<col style="width:15%" />
								<col style="width:25%" />
								<col style="width:15%" />
								<col style="width:15%" />
								<col style="width:15%" />
								<col style="width:10%" />
							</colgroup>
							<thead>
								<tr class="odd">
									<th class="custom-table-textAlign">SEQ</th>
									<th class="custom-table-textAlign">카테고리</th>
									<th class="custom-table-textAlign">상품이름</th>
									<th class="custom-table-textAlign">모델명</th>
									<th class="custom-table-textAlign">상품코드</th>
									<th class="custom-table-textAlign">작성일</th>
									<th class="custom-table-textAlign">상세보기</th>
								</tr>
							</thead>
							{if !empty($bbsList)}
							<tbody class="custom-table-textAlign">
								{foreach $bbsList as $key => $val}
								<tr class="odd">
									<td>{$val.goods_seq}</td>
									<td>{$val.category_title}</td>
									<td class="custom-table-textAlignCancel">{$val.goods_title}</td>
									<td>{$val.goods_model}</td>
									<td>{$val.goods_code}</td>
									<td>{$val.goods_regdt}</td>
									<td>
										<a href="goodsDetail.php?bbsSeq={$val.goods_seq}"><i class="fa fa-share-square-o"></i> 상세보기</a>
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
	{if !empty($pageList.prev.url)}
		<li><a href="{$pageList.prev.url}">&laquo;</a></li>
	{/if}
	{for $i=1 to $pageLoop}
		{if is_int($p) && $p == $pageList[$i].name}
		<li><a href="#">{$p}</a></li>
		{else}
		<li><a href="{$pageList[$i].url}">{$pageList[$i].pageNo}</a></li>
		{/if}
	{/for}
	{if !empty($pageList.next.url)}
		<li><a href="{$pageList.next.url}">&raquo;</a></li>
	{/if}
	</ul>
{/if}
					</div>
					<div class="col-sm-6">
						<a href="goodsDetail.php?mode=add" class="btn btn-primary btn-sm pull-right"><i class="fa  fa-edit"></i> 상품 추가하기</a>
					</div>
				</div><!--box-body-->
				</div><!--box-->

			</div>
		</div><!--/ end row-->
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