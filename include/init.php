<?php
/*
 * 이 페이지는 항상 제일 먼저 include 되어야 한다.
 * 프로그램 초기값 셋팅.
 */
// 과거의 날짜
header("Expires: Mon, 26 Jul 2007 00:00:00 GMT");

// 항상 변경됨
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// HTTP/1.0
header("Pragma: no-cache");

// 쿠키 보안문제 
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');

header("Content-type: text/html; charset=utf-8");

// 디폴트 타임존 셋팅
date_default_timezone_set("Asia/Seoul");

// GET URL 분석후 이상 URL 차단(SQL 인젝션 등)
$REJECT_URL_STRING = array("';", ';declare', 'varchar(');
define('_THIS_LOWER_REQUEST_URI', str_replace(' ', '', urldecode(strtolower($_SERVER['REQUEST_URI']))));
foreach($REJECT_URL_STRING as $v) {
	if (strpos(_THIS_LOWER_REQUEST_URI, $v) !== false) {
		exit();
	}
}

// 윈도우인지 아닌지 구분하기 위한 상수 셋팅.
if (is_callable("checkdnsrr") == false) {
	define("_IS_WINDOWS", true);
	$debugMode = false;								// 화면에 에러 내용을 뿌릴지 말지.
} else {
	define("_IS_WINDOWS", false);
	$debugMode = false;								// 화면에 에러 내용을 뿌릴지 말지.
}

// 서비스 명 
$explodeUrlFromScriptName = explode('/', $_SERVER['SCRIPT_NAME']);
$explodeUrlFromScriptName = array_filter($explodeUrlFromScriptName);
define("_SERVICE_NAME", $explodeUrlFromScriptName[1]);

if($_SERVER["SERVER_ADDR"] == '127.0.0.1'){// 개발용
	// DB 명 테스트 db명
	define("_DB_NAME", "");
}else{
	// DB 명 실서버 디비명
	define("_DB_NAME", "");
}

define("_client_id", "jvh8QiFHp9OXURYhBE_n");
define("_client_secret", "FBk5WFJrcO");

$explodeUrlFromScriptName = explode('.', $_SERVER['SERVER_NAME']);
$explodeUrlFromScriptName = array_filter($explodeUrlFromScriptName);
$explodeUrlFromScriptName[0] = null;
$explodeUrlFromScriptName = array_filter($explodeUrlFromScriptName);
$explodeUrlFromScriptName = implode('.', $explodeUrlFromScriptName);
// 도메인 명
define("_DOMAIN_NAME", $explodeUrlFromScriptName);

// 사이트 URL
define("_SITE_URL", "http://".$_SERVER['SERVER_NAME']."/"._SERVICE_NAME."/");

// Image, CSS domain (CDN)
define('_CDN_DOMAIN', '/');

// 도큐먼트 루트
define('_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');

// init root 폴더를 적어줄것 예 define('_FOLDER_ROOT', 'root/')
define('_FOLDER_ROOT', '');

// templates 루트
define('_TEMPLATES_ROOT', _DOCUMENT_ROOT._FOLDER_ROOT.'templates/');

// include 루트
define('_INCLUDE_ROOT', _DOCUMENT_ROOT._FOLDER_ROOT.'include/');

// class 루트
define('_CLASS_ROOT', _DOCUMENT_ROOT._FOLDER_ROOT.'include/class/');

// START front-end
// bower components 루트
define('_COMPONENTS_ROOT', _DOCUMENT_ROOT._FOLDER_ROOT.'bower_components/');
// plugins 루트
define('_PLUGINS_ROOT', _DOCUMENT_ROOT._FOLDER_ROOT.'plugins/');
//END front-end


// 현재 페이지 명
define('_PHP_SELF',basename($_SERVER['PHP_SELF']));

// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define('_KIMILJUNG_',true);

/*
 * ####################################
 * Error 핸들링 시작
 * ####################################
 */
error_reporting(E_ALL ^ E_NOTICE);

// user defined error handling function
function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
	// timestamp for the error entry
	$dt = date("Y-m-d H:i:s (T)");

	// define an assoc array of error string
	// in reality the only entries we should
	// consider are E_WARNING, E_NOTICE, E_USER_ERROR,
	// E_USER_WARNING and E_USER_NOTICE
	$errortype = array (
		E_ERROR           => "Error",
		E_WARNING         => "Warning",
		E_PARSE           => "Parsing Error",
		E_NOTICE          => "Notice",
		E_CORE_ERROR      => "Core Error",
		E_CORE_WARNING    => "Core Warning",
		E_COMPILE_ERROR   => "Compile Error",
		E_COMPILE_WARNING => "Compile Warning",
		E_USER_ERROR      => "User Error",
		E_USER_WARNING    => "User Warning",
		E_USER_NOTICE     => "User Notice",
		E_STRICT          => "Runtime Notice"
	);
	// set of errors for which a var trace will be saved
	$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);

	$err = "<errorentry>\n";
	$err .= "\t<datetime>" . $dt . "</datetime>\n";
	$err .= "\t<errornum>" . $errno . "</errornum>\n";
	$err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";
	$err .= "\t<errormsg>" . $errmsg . "</errormsg>\n";
	$err .= "\t<scriptname>" . $filename . "</scriptname>\n";
	$err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";
	if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
		$err .= "\t<ip>" . $_SERVER['REMOTE_ADDR'] . "</ip>\n";
	}
	$err .= "</errorentry>\n\n";

	global $debugMode;
	if ($debugMode) {
		printf($err);
		printf("<br>");
		printf("<br>");
	}

	$tempErrorLogFile = null;
	$serverNameF = substr($_SERVER['SERVER_NAME'], 0, strpos($_SERVER['SERVER_NAME'], '.'));
	$tempErrorLogFile = _DOCUMENT_ROOT._FOLDER_ROOT."logs/" . $serverNameF ."_error.log";

	$postData = '';
	if ($_SERVER['REQUEST_METHOD'] == "POST") {			// post 의 경우에만
		while (list ($key, $val) = each ($_POST)) {
			if ($key != 'submit' && $key != 'button' && $key != 'fm') {
				if (is_array($val)) {
					$postData .= '   '. $key . ': ';
					while (list ($key2, $val2) = each ($val)) {
						$postData .= $val2 . ' | ';
					}
					$postData .= "\n";
				} else {
					$postData .= '   '. $key . ': ' . $val . "\n";
				}
			}
		}
	}

	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $postData .= 'HTTP_ACCEPT_LANGUAGE => ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "\n";
	if (isset($_SERVER['HTTP_UA_CPU'])) $postData .= 'HTTP_UA_CPU => ' . $_SERVER['HTTP_UA_CPU'] . "\n";
	if (isset($_SERVER['HTTP_REFERER'])) $postData .= 'HTTP_REFERER => ' . $_SERVER['HTTP_REFERER'] . "\n";
	if (isset($_SERVER['HTTP_USER_AGENT'])) $postData .= 'HTTP_USER_AGENT => ' . $_SERVER['HTTP_USER_AGENT'] . "\n";
	if (isset($_SERVER['HTTP_COOKIE'])) $postData .= 'HTTP_COOKIE => ' . $_SERVER['HTTP_COOKIE'] . "\n";
	if (isset($_SERVER['REMOTE_HOST'])) $postData .= 'REMOTE_HOST => ' . $_SERVER['REMOTE_HOST'] . "\n";
	if (isset($_SERVER['REMOTE_ADDR'])) $postData .= 'REMOTE_ADDR => ' . $_SERVER['REMOTE_ADDR'] . "\n";
	if (isset($_SERVER['REQUEST_METHOD'])) $postData .= 'REQUEST_METHOD => ' . $_SERVER['REQUEST_METHOD'] . "\n";

	error_log("REQUEST: ". $_SERVER['REQUEST_URI'] ."  \nTIME: ". date("Y.m.d H:i:s") ." \n", 3, $tempErrorLogFile);
	if (!empty($postData))		error_log($postData, 3, $tempErrorLogFile);

	// save to the error log, and e-mail me if there is a critical user error
	error_log($err, 3, $tempErrorLogFile);

	if ($errno == E_USER_ERROR) {
		mail("wedd4860@naver.com", "Critical User Error", $err);
	}
}

set_error_handler("userErrorHandler");
/*
 * ####################################
 * Error 핸들링 끝
 * ####################################
 */


/*
 * ####################################
 * 로그인 정보 Bean 설정 시작
 * ####################################
 */

include_once _CLASS_ROOT."class.cookie.php";

class LOGIN_INFO {

	private $logon = false;
	private $memberSeq = null;
	private $memberId =null;
	private $memberName = null;
	private $memberNick = null;
	private $memberLevel = null;

	function LOGIN_INFO() {
		$cookie = new COOKIE("MEMBER_LOGIN_COOKIE");

		//쿠키 정보가 있는지 없는지
		$decrypt_cookie_msg = $cookie->getCookie();

		if ( $decrypt_cookie_msg == null ) {		// 쿠키 정보가 없을 경우 로그인 정보가 없음.
			$cookie->deleteCookie();
		} else {
			$cookie_info = array();
			parse_str($decrypt_cookie_msg, $cookie_info);		// 쿠키값 변수 셋팅.

			if ( time() > $cookie_info['TIME'] + 7200 ) {		// 로그인 후 2시간이 경과하였다면 다시 로그인을 시키기 위해 로그아웃 시켜 버린다.
				$this->logon = false;
				$cookie->deleteCookie();
			} else {
				if ( ($cookie_info['TIME'] + 3600 < time()) && (time() < $cookie_info['TIME'] + 7200) ) {	// 로그인 1시간 경과후 2시간 이내라면 쿠키를 다시 셋팅한다.
					$cookie->setLoginCookie( $cookie_info['member_seq']
										, substr($decrypt_cookie_msg, strpos($decrypt_cookie_msg, "&")+1) );
				}

				// 로그인 정보 셋팅
				$this->logon = true;
				$this->memberSeq = $cookie_info['member_seq'];
				if (isset($cookie_info['member_id'])) { $this->memberId = $cookie_info['member_id']; }
				if (isset($cookie_info['member_name'])) { $this->memberName = $cookie_info['member_name']; }
				if (isset($cookie_info['member_nick'])) { $this->memberNick = $cookie_info['member_nick']; }
				if (isset($cookie_info['member_level'])) { $this->memberLevel = $cookie_info['member_level']; }

			}
		}
		return true;
	}

	// public 로그인되어 있는지 아닌지
	function isLogin() { return $this->logon; }
	function getMemberSeq() { return $this->memberSeq; }
	function getMemberId() { return $this->memberId; }
	function getMemberName() { return $this->memberName; }
	function getMemberNick() { return $this->memberNick; }
	function getMemberLevel() { return $this->memberLevel; }

} // end class

$LOGIN_INFO = new LOGIN_INFO();
/*
 * ####################################
 * 로그인 정보 Bean 설정 끝
 * ####################################
 */


/*
 * ####################################
 * 넘어오는 변수 초기화 시작 (파일 데이터는 가져올 수 없음.)
 * $varStr = 넘어오는 변수명
 * $defaultValue = 디폴트 값 - null 이거나 "" 빈 공백
 * $isNo = true 숫자, 관련 ","를 전부 지운것을 리턴해준다.
 * ####################################
 */
function getRequestVar($varStr, $defaultValue = null, $isNumber = false) {
	//가변 변수
	global $$varStr;

	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		if (isset($_GET[$varStr]) && $_GET[$varStr] != "") {
			${$varStr} = $_GET[$varStr];
		} else {
			if ($defaultValue != null || empty(${$varStr})) {
				if (is_array(${$varStr})) {
					foreach (${$varStr} as $k => $v) {
						${$varStr}[$k] = $defaultValue;
					}
				} else {
					${$varStr} = $defaultValue;	
				}
			}
		}
	} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST[$varStr]) && $_POST[$varStr] != "") {
			${$varStr} = $_POST[$varStr];
		} else {
			if ($defaultValue != null || empty(${$varStr})) {
				if (is_array(${$varStr})) {
					foreach (${$varStr} as $k => $v) {
						${$varStr}[$k] = $defaultValue;
					}
				} else {
					${$varStr} = $defaultValue;	
				}
			}
		}
	}

	if ($isNumber && ${$varStr} !== null) {
		if (is_array(${$varStr})) {
			foreach (${$varStr} as $k => $v) {
				${$varStr}[$k] = str_replace(",", "", ${$varStr}[$k]);	
			}
		} else {
			${$varStr} = str_replace(",", "", ${$varStr});
		}
	}
	return ${$varStr};
}
/*
 * ####################################
 * 넘어오는 변수 초기화 끝
 * ####################################
 */

/*
 * ####################################
 * 각종 정의
 * ####################################
 */
{
	$BBS_EA=array('10','20','50');
/*
	$NAV_GROUP = array(
		'member'=>array('memberDetail.php')
		, 'category'=>array('categoryList.php','categoryDetail.php')
		, 'goods'=>array('goodsList.php','goodsDetail.php','goodsPriceDetail.php')
		, 'set'=>array('goodsSetList.php','goodsSetDetail.php','goodsSetOptionDetail.php')
		, 'fair'=>array('fairList.php','fairDetail.php')
		, 'order'=>array('orderList.php','orderDetail.php','orderSettle.php')
	);

	init.nav.php :: 권한 검증
*/

	$NAV_GROUP = array(
		'member'=>array(
				'page'=>array('memberDetail.php'),
				'level'=>'10',
				'href'=>'memberDetail.php',
				'fa'=>'laptop',
				'title'=>'정보 관리'),
		'category'=>array(
				'page'=>array('categoryList.php','categoryDetail.php','categoryDetailSub.php'),
				'level'=>'20',
				'href'=>'categoryList.php',
				'fa'=>'calendar',
				'title'=>'상품 카테고리 관리'),
		'goods'=>array(
				'page'=>array('goodsList.php','goodsDetail.php','goodsPriceDetail.php'),
				'level'=>'20',
				'href'=>'goodsList.php',
				'fa'=>'th-large',
				'title'=>'상품정보 관리'),
		'fair'=>array(
				'page'=>array('fairList.php','fairDetail.php'),
				'level'=>'20',
				'href'=>'fairList.php',
				'fa'=>'files-o',
				'title'=>'전시회 관리'),
		'order'=>array(
				'page'=>array('orderList.php','orderDetail.php','orderSettle.php'),
				'level'=>'10',
				'href'=>'orderList.php',
				'fa'=>'krw',
				'title'=>'주문 관리'),
		'chart'=>array(
				'page'=>array('chartList.php'),
				'level'=>'1000',
				'href'=>'chartList.php',
				'fa'=>'bar-chart',
				'title'=>'차트 관리'),
		'statistics'=>array(
				'page'=>array('statistics.php'),
				'level'=>'10',
				'href'=>'statistics.php',
				'fa'=>'bar-chart',
				'title'=>'통계')
	);

	$PAGE_INFO = array(
		'member'=>array(
			'mod'=>array(
				'title'=>'정보 관리','subTitle'=>'(내정보 및 전시회 정보 관리페이지 입니다.)'
			)
		)
		,'category'=>array(
			'list'=>array(
				'title'=>'상품 카테고리 관리','subTitle'=>'(상품 카테고리를 관리하는 게시판 입니다.)'
			)
			,'add'=>array(
				'title'=>'상품 카테고리 관리','subTitle'=>'(상품 카테고리를 추가합니다.)'
			)
			,'sub'=>array(
				'title'=>'하위 카테고리 관리','subTitle'=>'(하위 카테고리를 추가합니다.)'
			)
			,'mod'=>array(
				'title'=>'상품 카테고리 관리','subTitle'=>'(상품 카테고리를 수정합니다.)'
			)
		)
		,'goods'=>array(
			'list'=>array(
				'title'=>'상품정보 관리','subTitle'=>'(상품 정보를 관리하는 게시판 입니다.)'
			)
			,'add'=>array(
				'title'=>'상품정보 관리','subTitle'=>'(상품을 추가합니다.)'
			)
			,'mod'=>array(
				'title'=>'상품정보 관리','subTitle'=>'(해당 상품을 수정합니다.)'
			)
			,'priceAdd'=>array(
				'title'=>'상품정보 관리','subTitle'=>'(전시회별 상품 가격정보를 추가합니다.)'
			)
			,'priceMod'=>array(
				'title'=>'상품정보 관리','subTitle'=>'(전시회별 상품 가격정보를 수정합니다.)'
			)
		)
		,'fair'=>array(
			'list'=>array(
				'title'=>'전시회 관리 ','subTitle'=>'(전시회 정보를 관리하는 게시판 입니다.)'
			)
			,'add'=>array(
				'title'=>'전시회 관리','subTitle'=>'(전시회 이름 및 기간을 추가합니다)'
			)
			,'mod'=>array(
				'title'=>'전시회 관리','subTitle'=>'(전시회 이름 및 기간을 수정합니다)'
			)
		)
		,'order'=>array(
			'list'=>array(
				'title'=>'주문 관리','subTitle'=>'(주문을 관리하는 페이지 입니다.)'
			)
			,'add'=>array(
				'title'=>'주문 관리','subTitle'=>'(주문서 작성)'
			)
			,'mod'=>array(
				'title'=>'주문 관리','subTitle'=>'(주문서 상세)'
			)
		)
		,'chart'=>array(
			'list'=>array(
				'title'=>'차트 관리','subTitle'=>'(차드를 관리하는 페이지 입니다.)'
			)
		)
		,'statistics'=>array(
			'list'=>array(
				'title'=>'통계 관리','subTitle'=>'(통계를 관리하는 페이지 입니다.)'
			)
		)
	);
}
/*
 * ####################################
 * 각종 정의 끝
 * ####################################
 */
?>