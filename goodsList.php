<?
//initSet에서 _INCLUDE_ROOT 를 정의
include "include/init.php";
//template 사용시에만 include
include _CLASS_ROOT."class.smarty.php";
//DB 사용시에만 include
include _INCLUDE_ROOT."init.dbPDO.php";
$database = new Database(_DB_NAME);
include _INCLUDE_ROOT."init.utils.php";
include _INCLUDE_ROOT."init.login.php";

/*nav, title and subTitle*/
getRequestVar('pageInfo', 'list');

/*옵션 및 상품명*/
getRequestVar('p');
getRequestVar('s_ea');
getRequestVar('s_cate');
getRequestVar('s_title');

//카테고리 리스트
$bbsListCate = array();
$sqlCate = "
	SELECT category_seq, category_title, category_code FROM at_category_info
	WHERE category_status = 1
";
$database->prepare($sqlCate);
$bbsListCate = $database->dataAllFetch();


if(!$p || !is_numeric($p)){ $p = 1; }
$totalCount = 0;
$pagePerPage = 10;
if(!empty($s_ea)){
	$listPerPage=$s_ea;
}else{
	$listPerPage=10;
}
$dbRowStartNo = $listPerPage * ($p-1);

$sqlWhere = '';
if(!empty($s_title)){
	$sqlWhere.= "		and (goods.goods_title like '%".$s_title."%' or goods.goods_model like '%".$s_title."%' or goods.goods_code like '%".$s_title."%')";
}
if(!empty($s_cate)){
	$sqlWhere.= "		and cate.category_code = '".$s_cate."'";
}
//게시글 리스트
$sql = "
	select count(goods.goods_seq) as bbs_count
	from at_goods_info goods
		LEFT JOIN at_goods_link_info link ON goods.goods_seq=link.link_goods_seq
		LEFT JOIN at_category_info cate ON link.link_category_seq=cate.category_seq
	where goods_status = 1
";
$sql.= $sqlWhere;

$database->prepare($sql);
if($row = $database->dataFetch()){
	$totalCount = $row['bbs_count'];
}
if($totalCount > 0){

	//게시글 리스트
	$bbsList = array();
	$sql = "
		SELECT goods.goods_seq,goods.goods_title,cate.category_title
		,CASE WHEN COALESCE(goods.goods_code,'-')='-' OR goods.goods_code='' THEN '-' ELSE goods.goods_code END AS goods_code
		,CASE WHEN COALESCE(goods.goods_model,'-')='-' OR goods.goods_model='' THEN '-' ELSE goods.goods_model END AS goods_model
		,goods_regdt
			FROM at_goods_info goods
				LEFT JOIN at_goods_link_info link ON goods.goods_seq=link.link_goods_seq
				LEFT JOIN at_category_info cate ON link.link_category_seq=cate.category_seq
		WHERE goods_status = 1
	";
	$sql.= $sqlWhere;
	$sql.= "		group by goods_seq ";
	$sql.= "		order by goods_seq desc ";
	$sql.= "		limit ".$dbRowStartNo.", ".$listPerPage." ";
	$database->prepare($sql);
	$bbsList = $database->dataAllFetch();
}

include _INCLUDE_ROOT."init.nav.php";

/*page 작성*/
// 페이지 번호 작성 class
include _CLASS_ROOT."class.PageNoGen.php";
$pages = new PageNoGen($totalCount, $listPerPage, $pagePerPage, $p, _PHP_SELF);
if(!empty($s_ea)){$pages->addParam("s_ea", $s_ea);}
if(!empty($s_title)){$pages->addParam("s_title", $s_title);}
$page_list = $pages->generate();
$page_loop_count_limit = $pages->loop_count_limit;

/*template 변수설정*/
$template = new MySmarty();

/*기본 템플릿(기본, nav, login) 변수 설정*/
include _INCLUDE_ROOT."init.smarty.default.php";

//page 변수
$template->assign('bbsEa',$BBS_EA);
$template->assign('pageList',$page_list);
$template->assign('pageCount',$totalCount);
$template->assign('pageLoop',$page_loop_count_limit);
$template->assign('p',$p);
$template->assign('s_ea',$s_ea);
$template->assign('s_title',$s_title);
$template->assign('s_cate',$s_cate);

//template 변수
$template->assign('pageTitle',$pageTitle);
$template->assign('pageSubTitle',$pageSubTitle);
$template->assign('currentFairTitle',$currentFairTitle);
$template->assign('bbsList',$bbsList);
$template->assign('bbsListCate',$bbsListCate);

$template->d();


?>
