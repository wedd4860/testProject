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
	$sqlWhere.= "		and (orderTrans.order_customer like '%".$s_title."%' or orderTrans.order_mobile like '%".$s_title."%' or fair.fair_title like '%".$s_title."%')";
}

//게시글 리스트
$sql = "
	select count(orderTrans.order_seq) as bbs_count
	from at_order_trans orderTrans
		LEFT JOIN at_fair_info fair ON orderTrans.order_fair_seq = fair.fair_seq
	where orderTrans.order_status = 1 or orderTrans.order_status = 2
";
$sql.= $sqlWhere;

$database->prepare($sql);
if($row = $database->dataFetch()){
	$totalCount = $row['bbs_count'];
}
if($totalCount > 0){
	//게시글 리스트
	$bbsList = array();
	/*2018-01-05 :: 할인가 삭제 요청 :
	$sql = "
		SELECT orderTrans.order_seq,fair.fair_title,orderTrans.order_customer,orderTrans.order_mobile,orderTrans.order_tot_orgprice,orderTrans.order_tot_goodsprice,orderTrans.order_tot_discount,orderTrans.order_tot_settleprice,orderTrans.order_regdt,
		CASE WHEN (coalesce(orderTrans.order_status,0)=1) THEN '-'
				WHEN (coalesce(orderTrans.order_status,0)=2) THEN '취소'
				ELSE '-' END as order_status		
			FROM at_order_trans orderTrans
			LEFT JOIN at_fair_info fair ON orderTrans.order_fair_seq = fair.fair_seq
		where (orderTrans.order_status = 1 or orderTrans.order_status = 2)
	";
	*/
	$sql = "
		SELECT orderTrans.order_seq,fair.fair_title,orderTrans.order_customer,orderTrans.order_mobile,orderTrans.order_tot_orgprice,orderTrans.order_tot_settleprice,orderTrans.order_regdt,
		CASE WHEN (coalesce(orderTrans.order_status,0)=1) THEN '-'
				WHEN (coalesce(orderTrans.order_status,0)=2) THEN '취소'
				ELSE '-' END as order_status		
			FROM at_order_trans orderTrans
			LEFT JOIN at_fair_info fair ON orderTrans.order_fair_seq = fair.fair_seq
		where (orderTrans.order_status = 1 or orderTrans.order_status = 2)
	";
	$sql.= $sqlWhere;
	$sql.= "		group by orderTrans.order_seq ";
	$sql.= "		order by orderTrans.order_seq desc ";
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

//template 변수
$template->assign('pageTitle',$pageTitle);
$template->assign('pageSubTitle',$pageSubTitle);
$template->assign('currentFairTitle',$currentFairTitle);
$template->assign('bbsList',$bbsList);

$template->d();
?>
