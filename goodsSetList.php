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
getRequestVar('s_title');
getRequestVar('s_cate');

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
	$sqlWhere.= "		and (setInfo.set_title like '%".$s_title."%' or fair.fair_title like '%".$s_title."%')";
}
if(!empty($s_cate)){
	$sqlWhere.= "		and setInfo.set_fair_seq = ".$s_cate;
}

//페어 리스트
$bbsListFair = array();
$sqlFair = "
	SELECT fair_seq, fair_title FROM at_fair_info
	WHERE fair_status = 1
";
$database->prepare($sqlFair);
$bbsListFair = $database->dataAllFetch();

//게시글 리스트
$sql = "
	select count(setInfo.set_seq) as bbs_count
	from at_goods_set_info setInfo
		left join at_fair_info fair on setInfo.set_fair_seq = fair.fair_seq
	where set_status = 1
";
$sql.= $sqlWhere;

$database->prepare($sql);
if($row = $database->dataFetch()){
	$totalCount = $row['bbs_count'];
}

$bbsList = array();
if($totalCount > 0){
	//게시글 리스트
	$sql = "
		SELECT setInfo.set_seq, setInfo.set_title, fair.fair_title, date_format(fair.fair_sdt,  '%Y-%m-%d') as dateType_fair_sdt, date_format(fair.fair_edt,  '%Y-%m-%d') as dateType_fair_edt, set_regdt
			FROM at_goods_set_info setInfo
			LEFT JOIN at_fair_info fair ON setInfo.set_fair_seq = fair.fair_seq
			WHERE setInfo.set_status = 1
	";
	$sql.= $sqlWhere;
	$sql.= "		group by setInfo.set_seq ";
	$sql.= "		order by setInfo.set_seq desc ";
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
$template->assign('bbsListFair',$bbsListFair);
$template->assign('bbsList',$bbsList);

$template->d();
?>