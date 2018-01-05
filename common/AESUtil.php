<?php
     	
if (!function_exists('hex2bin')) {
    function hex2bin($str) {
        $sbin = "";
        $len = strlen($str);
        for ($i = 0; $i < $len; $i += 2) {
            $sbin .= pack("H*", substr($str, $i, 2));
        }

        return $sbin;
    }
}

class AESUtil {

    private $_cipher = MCRYPT_RIJNDAEL_128;
    private $_mode = MCRYPT_MODE_ECB;

    private function _pkcs5Pad($text, $blockSize) {
        $pad = $blockSize - (strlen($text) % $blockSize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private function _pkcs5Unpad($text) {
        $end = substr($text, -1);
        $last = ord($end);
        $len = strlen($text) - $last;
        if (substr($text, $len) == str_repeat($end, $last)) {
            return substr($text, 0, $len);
        }
        return false;
    }

    public function encrypt($encrypt, $password) {
    	// 对应Java SecureRandom.getInstance("SHA1PRNG")
    	$key = substr(openssl_digest(openssl_digest($password, 'sha1', true), 'sha1', true), 0, 16);
        $blockSize = mcrypt_get_block_size($this->_cipher, $this->_mode);
        $paddedData = $this->_pkcs5Pad($encrypt, $blockSize);
        $ivSize = mcrypt_get_iv_size($this->_cipher, $this->_mode);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $encrypted = mcrypt_encrypt($this->_cipher, $key, $paddedData, $this->_mode, $iv);
        return bin2hex($encrypted);
    }

    public function decrypt($decrypt, $password) {
    	$key = substr(openssl_digest(openssl_digest($password, 'sha1', true), 'sha1', true), 0, 16);
        $decoded = hex2bin($decrypt);
        $blockSize = mcrypt_get_iv_size($this->_cipher, $this->_mode);
        $iv = mcrypt_create_iv($blockSize, MCRYPT_RAND);
        $decrypted = mcrypt_decrypt($this->_cipher, $key, $decoded, $this->_mode, $iv);
        return $this->_pkcs5Unpad($decrypted);
    }
}