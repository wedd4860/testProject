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

getRequestVar('mode', 'mod');

//전시회 셀렉정보
$sqlFiar = "
	SELECT fair.fair_seq, fair.fair_title
	FROM at_fair_info fair
	WHERE fair.fair_status=1
";

if($mode == 'mod'){
	/*nav, title and subTitle*/
	getRequestVar('pageInfo', 'mod');

	getRequestVar('bbsSeq');
	$database->prepare($sqlFiar);
	$bbsFair = $database->dataAllFetch();

	//게시글 정보
	if(!empty($bbsSeq)){
		$sql = "
			SELECT set_title,set_fair_seq,set_goods_cnt 
			from at_goods_set_info
			where set_status = 1 and set_seq = :set_seq
		";
		$database->prepare($sql);
		$database->bind(':set_seq', $bbsSeq);
		$bbs = $database->dataFetch();
		
	}else{
		pageRedirect('goodsList.php');
	}

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

	//게시글 리스트
	$sqlsetOptionCount = "
	select count(setOption.set_option_seq) as set_option_count
	from at_goods_set_option_info setOption
	where setOption.set_option_status = 1 and setOption.set_option_set_seq = :set_option_set_seq
	";
	$database->prepare($sqlsetOptionCount);
	$database->bind("set_option_set_seq",$bbsSeq);

	if($row = $database->dataFetch()){
		$totalCount = $row['set_option_count'];
	}

	$bbsSetOptionList = array();
	if($totalCount > 0){
		//세트옵션 리스트
		$sqlSetOption = "
		SELECT setOption.set_option_seq,setOption.set_option_percent, goods.goods_title, price.price_goods_consumer, price.price_goods_price, fair.fair_title
		FROM at_goods_set_option_info setOption
			LEFT JOIN at_goods_info goods ON setOption.set_option_goods_seq = goods.goods_seq
			LEFT JOIN at_goods_price_history price ON goods.goods_seq = price.price_goods_seq	
			LEFT JOIN at_fair_info fair ON price.price_fair_seq = fair.fair_seq
		";
		$sqlSetOption.="		WHERE price.price_fair_seq = :price_fair_seq AND goods.goods_status = 1 AND setOption.set_option_status = 1 AND setOption.set_option_set_seq = :set_option_set_seq";
		$sqlSetOption.=$sqlWhere;
		$sqlSetOption.= "		GROUP BY set_option_seq ";
		$sqlSetOption.= "		ORDER BY set_option_seq DESC ";
		$sqlSetOption.= "		limit ".$dbRowStartNo.", ".$listPerPage." ";
		$database->prepare($sqlSetOption);
		$database->bind(':set_option_set_seq',$bbsSeq);
		$database->bind(':price_fair_seq',$bbs['set_fair_seq']);
		$bbsSetOptionList = $database->dataAllFetch();
	}

	/*세트옵션 page 작성*/
	// 페이지 번호 작성 class
	include _CLASS_ROOT."class.PageNoGen.php";
	$pages = new PageNoGen($totalCount, $listPerPage, $pagePerPage, $p, _PHP_SELF);
	if(!empty($s_ea)){$pages->addParam("s_ea", $s_ea);}
	if(!empty($s_title)){$pages->addParam("s_title", $s_title);}
	$page_list = $pages->generate();
	$page_loop_count_limit = $pages->loop_count_limit;

}else if($mode == 'add'){
	/*nav, title and subTitle*/
	getRequestVar('pageInfo', 'add');
	$sqlFiar.= "";
	$database->prepare($sqlFiar);
	$bbsFair = $database->dataAllFetch();
}else{
	//에러
	exit();
}


include _INCLUDE_ROOT."init.nav.php";

/*template 변수설정*/
$template = new MySmarty();

/*기본 템플릿(기본, nav, login) 변수 설정*/
include _INCLUDE_ROOT."init.smarty.default.php";

//template 변수
$template->assign('pageTitle',$pageTitle);
$template->assign('pageSubTitle',$pageSubTitle);
$template->assign('currentFairTitle',$currentFairTitle);

$template->assign('mode',$mode);
$template->assign('bbsFair',$bbsFair);
if($mode=="mod"){
	//page 변수
	$template->assign('bbsEa',$BBS_EA);
	$template->assign('pageList',$page_list);
	$template->assign('pageCount',$totalCount);
	$template->assign('pageLoop',$page_loop_count_limit);
	$template->assign('p',$p);
	$template->assign('s_ea',$s_ea);
	$template->assign('s_title',$s_title);
	//게시글 정보
	$template->assign('bbsSeq',$bbsSeq);
	$template->assign('pageInfo',$pageInfo);
	$template->assign('bbs',$bbs);
	$template->assign('bbsSetOptionList',$bbsSetOptionList);
}else if($mode=="add"){
	$template->assign('pageInfo',$pageInfo);
}

$template->d();
?>