<?php
if (!defined('_KIMILJUNG_')) exit;
// 메세지 암호화, 복호화 클래스
class CRYPT {
	private $key;

	function CRYPT( $key = "spmlcefvikiqprtbe" ) {
		$this->key = $key;
		return true;
	}

	private function bytexor($a,$b,$ilimit) {
		$c="";
		for($i=0;$i<$ilimit;$i++) {
			$c .= $a{$i}^$b{$i};
		}
		return $c;
	}

	function encrypt_md5($value) {
		if(!$value) return false;

		$key = $this->key;
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);

		$value = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB, $iv);

		return trim(base64_encode($value));
	}

	function decrypt_md5($value) {
		if(!$value) return false;

		$key = $this->key;
		$value = base64_decode($value);
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);

		$value = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB, $iv);

		return trim($value);
	}
}
?>