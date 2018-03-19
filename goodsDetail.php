<?
ini_set('display_errors', true);

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

if($mode == 'mod'){
	/*nav, title and subTitle*/
	getRequestVar('pageInfo', 'mod');
	getRequestVar('bbsSeq');

	//카테고리 정보
	$sqlCategory = "
		select category_seq, category_title, category_code
		from at_category_info
		where category_status = 1
	";
	$database->prepare($sqlCategory);
	$bbsCategory = $database->dataAllFetch();

	//게시글 정보
	if(!empty($bbsSeq)){
		$sql = "
			select cate.category_seq, cate.category_title, goods.goods_seq, goods.goods_title, goods.goods_name_sub, goods.goods_code, goods.goods_desc, goods.goods_model, goods.goods_launchdt, goods.goods_origin
			from at_goods_info goods
				left join at_goods_link_info link on goods.goods_seq = link.link_goods_seq
				left join at_category_info cate on cate.category_seq = link.link_category_seq
			where goods.goods_status = 1 and goods.goods_seq = :goods_seq
		";
		$database->prepare($sql);
		$database->bind(':goods_seq', $bbsSeq);
		$bbs = $database->dataFetch();
	}else{
		pageRedirect('goodsList.php');
	}

/*
	//상품옵션
	$sqlOption = "
		SELECT COUNT(option_seq) AS option_bbs_count
		FROM at_goods_option_info
		WHERE option_status = 1
	";
	$database->prepare($sqlOption);
	if($row = $database->dataFetch()){$optionCount = $row['option_bbs_count'];}
	if($optionCount > 0){
		//옵션 리스트
		$bbsList = array();
		$sqlOption = "
		select option_seq, option_color_title, option_color_code
			from at_goods_option_info
			where option_status = 1 and option_goods_seq= :option_goods_seq
		";
		$database->prepare($sqlOption);
		$database->bind(':option_goods_seq', $bbsSeq);
		$optionBbsList = $database->dataAllFetch();
	}
*/

	//전시회별 상품 가격정보
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
		$sqlWhere.= "		and fair_title like '%".$s_title."%' ";
	}

	//전시회별 상품 가격정보 리스트
	$sqlPriceCount = "
		select count(price_seq) as price_count
		from at_goods_price_history
		where price_status = 1 AND price_goods_seq = :price_goods_seq
	";
	$database->prepare($sqlPriceCount);
	$database->bind("price_goods_seq",$bbsSeq);
	if($row = $database->dataFetch()){
		$totalCount = $row['price_count'];
	}
	
	$bbsPriceList = array();
	if($totalCount > 0){
		//게시글 리스트
		$sqlPrice = "
			SELECT fair.fair_title, price.price_seq, price.price_goods_consumer, price.price_goods_price, price.price_goods_supply
			FROM at_goods_price_history price
			LEFT JOIN at_fair_info fair ON price.price_fair_seq = fair.fair_seq
		";
		$sqlPrice.="		WHERE price.price_status = 1 AND price.price_goods_seq = :price_goods_seq";
		$sqlPrice.=$sqlWhere;
		$sqlPrice.= "		GROUP BY price.price_seq ";
		$sqlPrice.= "		ORDER BY price.price_seq DESC ";
		$sqlPrice.= "		limit ".$dbRowStartNo.", ".$listPerPage." ";
		$database->prepare($sqlPrice);
		$database->bind(':price_goods_seq',$bbsSeq);
		$bbsPriceList = $database->dataAllFetch();
	}

	/*전시회별 상품 가격정보page 작성*/
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

	//카테고리 정보
	$sqlCategory = "
		select category_seq, category_title, category_code 
		from at_category_info
		where category_status = 1
	";
	$database->prepare($sqlCategory);
	$bbsCategory = $database->dataAllFetch();

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
$template->assign('bbsCategory',$bbsCategory);
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
	//색상정보 삭제$template->assign('optionBbsList',$optionBbsList);
	$template->assign('bbsPriceList',$bbsPriceList);
}else if($mode=="add"){
	$template->assign('pageInfo',$pageInfo);
}

$template->d();
?>