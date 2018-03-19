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

if($mode == 'mod'){
	/*nav, title and subTitle*/
	getRequestVar('pageInfo', 'mod');
	getRequestVar('bbsSeq');

	//게시글 정보
	if(!empty($bbsSeq)){
		$sql = "
			select fair_seq, fair_title ,fair_sdt ,fair_edt
			from at_fair_info
			where fair_status = 1 and fair_seq = :fair_seq
		";
		$database->prepare($sql);
		$database->bind(':fair_seq', $bbsSeq);
		$bbs = $database->dataFetch();
		$std_date=date("Y-m-d", strtotime($bbs['fair_sdt']));
		$end_date=date("Y-m-d", strtotime($bbs['fair_edt']));
	}else{
		pageRedirect('fairList.php');
	}
}elseif($mode == 'add'){
	/*nav, title and subTitle*/
	getRequestVar('pageInfo', 'add');
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
if($mode=="mod"){
	$template->assign('bbsSeq',$bbsSeq);
	$template->assign('pageInfo',$pageInfo);
	$template->assign('bbs',$bbs);
	$template->assign('stdDate',$std_date);
	$template->assign('endDate',$end_date);
}else if($mode=="add"){
	$template->assign('pageInfo',$pageInfo);
}

$template->d();
?>