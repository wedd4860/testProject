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

getRequestVar('mode','list');
getRequestVar('pageInfo', 'list');
/*
if($mode == 'mod'){
	//nav, title and subTitle
	getRequestVar('pageInfo', 'mod');

	$pageTitle="";
	getRequestVar('bbsSeq');

	//게시글 정보
	if(!empty($bbsSeq)){
		$sql = "
			select category_seq, category_title 
			from at_category_info
			where category_status = 1 and category_seq = :category_seq
		";
		$database->prepare($sql);
		$database->bind(':category_seq', $bbsSeq);
		$bbs = $database->dataFetch();
	}else{
		pageRedirect('categoryList.php');
	}
}elseif($mode == 'add'){
	//nav, title and subTitle
	getRequestVar('pageInfo', 'add');
}else{
	//에러
	exit();
}
*/

include _INCLUDE_ROOT."init.nav.php";

/*template 변수설정*/
$template = new MySmarty();

/*기본 템플릿(기본, nav, login) 변수 설정*/
include _INCLUDE_ROOT."init.smarty.default.php";

//template 변수
$template->assign('pageTitle',$pageTitle);
$template->assign('pageSubTitle',$pageSubTitle);
$template->assign('currentFairTitle',$currentFairTitle);

//template 변수

$template->assign('pageTitle',$pageTitle);
$template->assign('pageSubTitle',$pageSubTitle);
$template->assign('currentFairTitle',$currentFairTitle);
/*
$template->assign('mode',$mode);
if($mode=="mod"){
	$template->assign('bbsSeq',$bbsSeq);
	$template->assign('pageInfo',$pageInfo);
	$template->assign('bbs',$bbs);
}else if($mode=="add"){
	$template->assign('pageInfo',$pageInfo);
}
*/

$template->d();
?>