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

getRequestVar('mode', 'add');

if($mode == 'add'){
	/*nav, title and subTitle*/
	getRequestVar('pageInfo', 'sub');

	$pageTitle="";
	getRequestVar('bbsCode');

	//게시글 정보
	if(!empty($bbsCode)){
		$sql = "
			SELECT category_seq, category_title, category_code
				FROM at_category_info
			WHERE 
				category_code = (SELECT MAX(category_code) FROM at_category_info WHERE category_code LIKE :bbsCode AND LENGTH(category_code)> 3)
		";
		$database->prepare($sql);
		$database->bind(':bbsCode', $bbsCode."%");
		$bbs = $database->dataFetch();
		
		$transInt = (int)substr($bbs['category_code'],3,5);
		$newCateCode = $bbsCode.sprintf("%03d",$transInt+1);
	}else{
		pageRedirect('categoryList.php');
	}
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
if($mode=="add"){
	$template->assign('bbs',$bbs);
	$template->assign('pageInfo',$pageInfo);
	$template->assign('newCateCode',$newCateCode);
}

$template->d();
?>