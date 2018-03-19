<?
if (!defined('_KIMILJUNG_')) exit;

$sqlCurrentFair = "
	SELECT fair.fair_title
	FROM at_setting_info setting
	LEFT JOIN at_fair_info fair ON setting.setting_fair_seq = fair.fair_seq
	ORDER BY setting.setting_seq DESC LIMIT 1
";
$database->prepare($sqlCurrentFair);
$currentFair = $database->dataFetch();
$currentFairTitle = $currentFair['fair_title'];

$pageVal;
if(in_array(_PHP_SELF, $NAV_GROUP['member']['page'])){
	$pageVal='member';
}else if(in_array(_PHP_SELF, $NAV_GROUP['category']['page'])){
	$pageVal='category';
}else if(in_array(_PHP_SELF, $NAV_GROUP['goods']['page'])){
	$pageVal='goods';
}else if(in_array(_PHP_SELF, $NAV_GROUP['set']['page'])){
	$pageVal='set';
}else if(in_array(_PHP_SELF, $NAV_GROUP['fair']['page'])){
	$pageVal='fair';
}else if(in_array(_PHP_SELF, $NAV_GROUP['order']['page'])){
	$pageVal='order';
};

if($NAV_GROUP[$pageVal]['level']>$ArrayLogin['level']){
	pageRedirect($_SERVER['HTTP_REFERER'],'권환이 없습니다.');
}

$pageTitle = $PAGE_INFO[$pageVal][$pageInfo]['title'];
$pageSubTitle = $PAGE_INFO[$pageVal][$pageInfo]['subTitle'];

?>
