<?
/*template 변수설정*/
$template = new MySmarty();

//기본 변수
$template->assign('siteUrl',_SITE_URL);
$template->assign('phpSelf',_PHP_SELF);
$template->assign('logout',"logout.php");
$template->assign('memberUrl',"memberDetail.php");
$template->assign('categoryUrl',"categoryList.php");
$template->assign('fairUrl',"fairList.php");
$template->assign('orderUrl',"orderList.php");
$template->assign('goodsUrl',"goodsList.php");
$template->assign('setUrl',"goodsSetList.php");
$template->assign('chartUrl',"categoryList.php");

//nav 변수
$template->assign('navGroup',$NAV_GROUP);
$template->assign('pageVal',$pageVal);

//login 변수
$template->assign('login',$ArrayLogin);

?>