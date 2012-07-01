<?php

class Auth_Crypt
{
	protected static $iv      = NULL;
	protected static $iv_size = NULL;

	public function __contstruct($key = "")
	{
		$this->iv_size = mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB);
		$this->iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);
	}
	
	public function encrypt($data, $key = "")
	{
		//$size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_ECB);
		//$data = self::pkcs5_pad($data, $size);
		//return base64_encode(mcrypt_encrypt(MCRYPT_DES, $key, $data, MCRYPT_MODE_ECB, $this->iv));
		return base64_encode($data);
		return $data;
	}

	public function decrypt($data, $key = "")
	{
		return base64_decode($data);
		//return self::pkcs5_unpad(rtrim(mcrypt_decrypt(MCRYPT_DES, $key, base64_decode($data), MCRYPT_MODE_ECB, $this->iv)));
		return $data;
	}

	public function pkcs5_pad($text, $blocksize)
	{
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	public function pkcs5_unpad($text)
	{
		$pad = ord($text{strlen($text)-1});
		if ($pad > strlen($text)) return false;
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
		return substr($text, 0, -1 * $pad);
	}
}
?>