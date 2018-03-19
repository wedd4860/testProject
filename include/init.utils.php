<?php
if (!defined('_KIMILJUNG_')) exit;
/*
	header Refresh기능을 이용하여 데이터 중복 방지
	action 페이지에서 사용자에게 보여지는 메시지와 이동시킬 페이지를 자바스크립트로 생성
	$msg : 메세지
	$forward_url : 이동시킬 URL
	이용방법 : 마지막에 pageRedirectAlertScript("message", "xxxxx.php?aaa=bbb");
*/
function pageRedirect( $forward_url, $msg = null ) {
	global $conn;
	if (isset($conn) && $conn != null && !$conn->isClosed){
		$conn->close();
	}
	global $database;
	if(isset($database) && $database != null){
		$database = null;//db connection close
	}

	header("Refresh:0; URL=$forward_url");

	$str = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	$str .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
	$str .= "<head>";
	$str .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	if ( $msg != null ) {
		$str .= "<script language=\"javascript\">"; 
		$str .= "alert('$msg');"; 
		$str .= "</script>";
	}
	$str .= "</head>";
	$str .= "<body>";
	$str .= "</body>";
	$str .= "</html>";

	echo $str;
	exit();
}


/*
number에 콤마를 작성해주는 함수.
null 일 경우 null을 리턴. number_format함수의 0으로 초기화되는 것을 방지.
*/
function numberFormat($var) {
	if ($var === null) {
		return null;
	} else {
		return number_format($var);
	}
}


/*
 * 텍스트 리사이징
 */
function resizeString($Str, $size, $addStr="..")  {
	return mb_strimwidth($Str, 0, $size, $addStr, 'UTF-8');
}


/*
 * text에 링크가 있다면 링크를 만들어준다.
 */
function autoLink($str) {
	// htmlspecialchars
	$str = preg_replace("/&lt;/", "\t_lt_\t", $str);
	$str = preg_replace("/&gt;/", "\t_gt_\t", $str);
	$str = preg_replace("/&amp;/", "&", $str);
	$str = preg_replace("/&quot;/", "\"", $str);
	$str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);

	// URL 치환
	$url_pattern = "/((www\.[_0-9a-zㄱ-ㅎ가-힣-]+)|"; // www로 시작하는 도메인
	$url_pattern.= "(http|https|ftp|mms):\/\/[0-9a-z-]+\.[_0-9a-zㄱ-ㅎ가-힣-])"; // protocol+domain
	$url_pattern.= "([\.~_0-9a-z-]+\/?)*"; // sub roots
	$url_pattern.= "(\S+\.[_0-9a-z]+)?"; // file & extension string
	$url_pattern.= "(\?[_0-9a-z#%&=\-\+]+)*/i"; // parameters
	$replacement_url = "<a href='\\0' target='_blank'>\\0</a>";
	$str = preg_replace($url_pattern, $replacement_url, $str, -1);

	// www URL href 치환
	$str = str_replace("href='www.", "href='http://www.", $str);

	// 메일 치환
	$email_pattern = "([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+){1,})";
	$replacement_email = "<a href='mailto:\\0'>\\0</a>";
	$str = preg_replace($email_pattern, $replacement_email, $str, -1);

	return $str;
}


/*
 * 브라우저 언어 가져오기
 */
function get_user_language() {
	
	$langs = array();
	
	if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {

		// break up string into pieces (languages and q factors)
		preg_match_all( '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse );
		
		if ( count( $lang_parse[1] ) ) {
			// create a list like â??enâ?? => 0.8
			$langs = array_combine( $lang_parse[1], $lang_parse[4] );
			// set default to 1 for any without q factor
			foreach ( $langs as $lang => $val ) {
				if ( $val === '' ) $langs[$lang] = 1;
			}
			// sort list based on value
			arsort( $langs, SORT_NUMERIC );
		}
	}
	
	// extract most important (first)
	foreach ( $langs as $lang => $val ) { break; }
	
	// if complex language simplify it
	if ( stristr( $lang, '-' ) ) {
		$tmp = explode( '-', $lang );
		$lang = $tmp[0];
	}
	
	return $lang;
	
}

function getMillisecond(){
	list($microtime,$timestamp) = explode(' ',microtime());
	$time = $timestamp.substr($microtime, 2, 3);

	return $time;
}
?>