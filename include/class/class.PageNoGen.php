<?
if (!defined('_KIMILJUNG_')) exit;
/* 사용법
// 페이지 관련 초기 정의
if(!$p || !is_numeric($p)){ $p = 1; }
$totalCount = 0;
$pagePerPage = 10;
$listPerPage = 10;
$dbRowStartNo = $listPerPage * ($p-1);

// 페이지 번호 작성 class
require _DOCUMENT_ROOT."/comm/pageNoGen.php";
$pages = new PageNoGen($totalCount, $listPerPage, $pagePerPage, $p, $_SERVER['PHP_SELF']);
$pages->addParam("parameter", $parameter);
$page_list = $pages->generate();
// 페이지 링크 출력
if($totalCount > 0 && $pages->loop_count_limit > 0){
	echo '<ul class="pagination">';
	if($page_list['prev']['url']){
		echo '<li><a href="'.$page_list['prev']['url'].'">&laquo;</a></li>';
	}
	for($i = 1; $i <= $pages->loop_count_limit; $i++){
		if ($p == (int) $page_list[$i]['name'])	echo '<li><a href="#">'.$p.'</a></li>';
		else echo '<li><a href="'.$page_list[$i]['url'].'">'.$page_list[$i]['pageNo'].'</a></li>';
	}
	if($page_list['next']['url']){
		echo '<li><a href="'.$page_list['next']['url'].'">&raquo;</a></li>';
	}
	echo '</ul>';
}else{
// 출력할 데이터가 없음
}
*/

class PageNoGen {
	var $total; // 게시판에서 검색된 총 리스트 수
	var $list_per_page; // 페이지당 출력해야할 리스트 수
	var $page_per_page; // 페이지당 연결시켜야할 페이지 수
	var $page_var_name = "p"; // 페이지 번호를 나타내는 파라미터 명
	var $page_pos = 1; // 페이지 번호
	var $url = ""; // 게시판 URL
	var $loop_count_limit = 0;

	var $params = NULL;
	var $pages = NULL;

	## 페이지 연결을 생성시키기 위한 생성자
	## 파라미터 변수들은 필수 항목으로 누락된 것이 없어야 한다.
	function PageNoGen($total, $list_per_page, $page_per_page, $page_pos, $url) {
		$this->total = $total;
		$this->list_per_page = $list_per_page;
		$this->page_per_page = $page_per_page;
		$this->page_pos = $page_pos;
		$this->url = $url;
	}

	## 페이지 연결시 넘겨지는 기본 파라미터 값 외의 파라미터 리스트를 받는다.
	function addParam($param_name, $param_value) {
		$cur_index = sizeof($this->params);
		$this->params[$cur_index]["name"] = $param_name;
		$this->params[$cur_index]["value"] = $param_value;
	}

	## 페이징 정보를 갖는 배열
	function generate() {
		$total_pages = 0; // 총 페이지수
		if (($this->total % $this->list_per_page) > 0) $total_pages = floor($this->total / $this->list_per_page) + 1;
		else $total_pages = floor($this->total / $this->list_per_page);

		$first_page_num = 0; // 출력한 페이지 리스트의 첫번째
		if (($this->page_pos % $this->page_per_page) > 0) $first_page_num = (floor($this->page_pos / $this->page_per_page) * $this->page_per_page) + 1;
		else $first_page_num = $this->page_pos - $this->page_per_page + 1;

		$page_list = 0; // 출력될 페이지 수
		if (($total_pages - $first_page_num) >= $this->page_per_page) $page_list = $this->page_per_page;
		else $page_list = $total_pages - $first_page_num + 1;

		$this->loop_count_limit = $page_list;

		$param_str = '';
		for ($i = 0; $i < sizeof($this->params); $i++) {
			$param_str .= '&'. $this->params[$i]['name']. '=' .urlencode($this->params[$i]['value']);
		}

		// 처음 페이지
		$this->pages["first"]["name"] = "&#9664;&#9664";
		$this->pages["first"]["url"] = $this->url."?".$this->page_var_name."=1"."&amp;".$param_str;
		// 마지막 페이지
		$this->pages["last"]["name"] = "&#9654;&#9654;";
		$this->pages["last"]["url"] = $this->url."?".$this->page_var_name."=".$total_pages."&amp;".$param_str;

		// 전 목록
		if ($first_page_num > $this->page_per_page) {
			$this->pages["prev"]["name"] = "&#9664";
			if (empty($param_str)) {
				$this->pages["prev"]["url"] = $this->url."?".$this->page_var_name."=".($first_page_num - 1);
			} else {
				$this->pages["prev"]["url"] = $this->url."?".$this->page_var_name."=".($first_page_num - 1)."&amp;".$param_str;
			}
			$this->pages["prev"]["pageNo"] = $first_page_num - 1;
		} else {
			$this->pages["prev"]["name"] = "";
			$this->pages["prev"]["url"] = "";
		}

		// 다음 목록
		if (($first_page_num + $this->page_per_page) <= $total_pages) {
			$this->pages["next"]["name"] = "&#9654;";
			if (empty($param_str)) {
				$this->pages["next"]["url"] = $this->url."?".$this->page_var_name."=".($first_page_num + $this->page_per_page);
			} else {
				$this->pages["next"]["url"] = $this->url."?".$this->page_var_name."=".($first_page_num + $this->page_per_page)."&amp;".$param_str;	
			}
			$this->pages["next"]["pageNo"] = $first_page_num + $this->page_per_page;
		} else {
			$this->pages["next"]["name"] = "";
			$this->pages["next"]["url"] = "";
		}

		for ($i = 1; $i <= $page_list; $i++) {
			$num = $i + $first_page_num - 1;
			if ($this->page_pos == $num) {
				$this->pages[$i]["name"] = $num;
				$this->pages[$i]["url"] = "";
				$this->pages[$i]["pageNo"] = $num;
			} else {
				$this->pages[$i]["name"] = $num;
				$this->pages[$i]["url"] = $this->url."?".$this->page_var_name."=".$num.$param_str;
				$this->pages[$i]["pageNo"] = $num;
			}
		}

		return $this->pages;

//		if ($this->pages[1]["url"] != "") { echo "asdf"; return $this->pages; }
//		else { return NULL; }
	}
} // :~ class PageNoGen
?>