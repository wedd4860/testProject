<?php
if (!defined('_KIMILJUNG_')) exit;
// client 에 쿠키를 저장하고 가져오는 class
class COOKIE {
	/*
	 * Key가 16byte이면 키를 암호화 했을때에는 44byte가 나온다.
	 */
	private $CRYPT_KEY = "kimijv#)kimij$3";		// 로그인 정보를 crypt하는 key	반드시 15byte로 작성되어져야 한다. (PHP 5.6 이상)
	private $CRYPT_KEY_CRYPT_KEY = "kimijlcaj(ioelak";		// 로그인 정보를 crypt하는 key를 crypt하는 key 16byte
	private $SETCOOKIE_KEY = "l7aio#lcl4ikimij";		// 로그인 정보 전체를 crypt하는 key 16byte
	private $cookie_name;

	public function COOKIE($cookie_name) {
		$this->cookie_name = _SERVICE_NAME.$cookie_name;
		include_once _CLASS_ROOT."class.crypt.php";
		return true;
	}

	/* 쿠키에 구워지는 메세지를 총 3개의 key를 이용하여 암호화 처리를 한 후 메세지를 되돌린다.
		$unique_key : 암호화에 사용되는 고유키 (user 별 고유한 값) 8byte
		$cookie_msg : 암호화 되어야 하는 메세지
	*/
	private function cookie_encrypt( $unique_key, $cookie_msg ) {
		// 쿠키에 저장되는 정보들을 crypt
		$cookie_msg_crypt_key = $this->CRYPT_KEY . $unique_key;
		$crypt1 = new CRYPT( $cookie_msg_crypt_key );
		$crypt_cookie_msg = $crypt1->encrypt_md5($cookie_msg);

		// cookie_msg를 crypt하는 key를 crypt
		$crypt2 = new CRYPT( $this->CRYPT_KEY_CRYPT_KEY );
		$crypt_key = $crypt2->encrypt_md5($cookie_msg_crypt_key);

		// cookie_msg와 crypt된 key를 더해서 다시 crypt를 한다.
		$total_cookie_msg = $crypt_key . $crypt_cookie_msg;
		$crypt3 = new CRYPT( $this->SETCOOKIE_KEY );
		$crypt_total_cookie_msg = $crypt3->encrypt_md5($total_cookie_msg);

		return $crypt_total_cookie_msg;
	}

	/*
		쿠키에 구워진 암호화된 메세지를 복호화 시킨후 되돌린다.
	*/
	private function cookie_decrypt( $cookie_msg ) {
		$crypt4 = new CRYPT( $this->SETCOOKIE_KEY );
		$decrypt_total_cookie_msg = $crypt4->decrypt_md5($cookie_msg);

		$crypt5 = new CRYPT( $this->CRYPT_KEY_CRYPT_KEY );
		$decrypt_key = $crypt5->decrypt_md5( substr($decrypt_total_cookie_msg, 0, 44 ) );

		$crypt6 = new CRYPT( $decrypt_key );
		$decrypt_cookie_msg = $crypt6->decrypt_md5( substr( $decrypt_total_cookie_msg, 44, strlen($decrypt_total_cookie_msg) - 44 ) );

		return $decrypt_cookie_msg;
	}

	// 로그인 쿠키 set
	public function setLoginCookie ( $system_id, $msg, $addTime = true, $expire = null ) {
		if ($addTime) {
			$msg = "TIME=" . time() . "&" . $msg;
		}

		if ($expire == null) {
			$setcookie_check = setcookie ( $this->cookie_name, $this->cookie_encrypt($system_id, $msg), 0, "/", $_SERVER['SERVER_NAME'] );	
		} else {
			$setcookie_check = setcookie ( $this->cookie_name, $this->cookie_encrypt($system_id, $msg), $expire, "/", $_SERVER['SERVER_NAME'] );
		}

		return $setcookie_check;
	}

	// 일반 쿠키 set
	public function setCookie ( $msg, $addTime = true, $expire = null ) {
		if ($addTime) {
			$msg = "TIME=" . time() . "&" . $msg;
		}

		if ($expire == null) {
			$setcookie_check = setcookie ( $this->cookie_name, $this->cookie_encrypt(substr(time(), -8), $msg), 0, "/", $_SERVER['SERVER_NAME'] );
		} else {
			$setcookie_check = setcookie ( $this->cookie_name, $this->cookie_encrypt(substr(time(), -8), $msg), $expire, "/", $_SERVER['SERVER_NAME'] );
		}

		return $setcookie_check;
	}

	public function setCookieNonEnc($msg, $addTime = true, $expire = null ) {
		if ($expire == null) {
			$setcookie_check = setcookie ( $this->cookie_name, $msg, 0, "/", $_SERVER['SERVER_NAME'] );
		} else {
			$setcookie_check = setcookie ( $this->cookie_name, $msg, $expire, "/", $_SERVER['SERVER_NAME'] );
		}
	}

	public function getCookieNonEnc() {
		$total_cookie_msg = null;
		if (isset($_COOKIE[$this->cookie_name])) {
			$total_cookie_msg = $_COOKIE[$this->cookie_name];
		}
		return $total_cookie_msg;
	}

	// 로그인 쿠키 get
	public function getCookie() {
		if (isset($_COOKIE[$this->cookie_name])) {
			$total_cookie_msg = $_COOKIE[$this->cookie_name];

			$decrypt_cookie_msg = $this->cookie_decrypt($total_cookie_msg);
			return $decrypt_cookie_msg;
		}
	}

	public function deleteCookie() {
		setcookie ($this->cookie_name, "", time() - 3600, "/", $_SERVER['SERVER_NAME']);
	}
}
?>