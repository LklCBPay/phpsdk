<?php

/* * ***
 * 拉卡拉PHP支付公共函数库
 * @author chunkuan <urcn@qq.com>
 */


/* * **** DES 加密解密库 Start ******** */
/**
 * des加密用到的临时函数
 */
function des_pkcs5_pad($text, $blocksize){
  $pad = $blocksize - (strlen($text) % $blocksize);
  return $text.str_repeat(chr($pad), $pad);
}

/**
 * des加密字符串
 * @param string $key 加密密钥
 * @param string $data 待加密字符串
 * @return 加密后的十六进制数据
 * @author chunkuan <urcn@qq.com>
 */
function des_encrypt($key, $data){
  $size = mcrypt_get_block_size('des', 'ecb');
  $data = des_pkcs5_pad($data, $size);
  $td = mcrypt_module_open('des', '', 'ecb', '');
  $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
  @mcrypt_generic_init($td, $key, $iv);
  $data = mcrypt_generic($td, $data);
  mcrypt_generic_deinit($td);
  mcrypt_module_close($td);
  return strtoupper(bin2hex($data));
}

/**
 * 解密用到的临时函数
 */
function des_pkcs5_unpad($text){
  $pad = ord($text{strlen($text) - 1});
  if($pad > strlen($text)){
    return false;
  }
  if(strspn($text, chr($pad), strlen($text) - $pad) != $pad){
    return false;
  }
  return substr($text, 0, -1 * $pad);
}

/**
 * des解密字符串
 * @param string $key 加密密钥
 * @param string $data 待解密字符串
 * @return 解密后的数据
 * @author chunkuan <urcn@qq.com>
 */
function des_decrypt($key, $data){
  $data = hex2bin(strtolower($data));
  $td = mcrypt_module_open('des', '', 'ecb', '');
  $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
  $ks = mcrypt_enc_get_key_size($td);
  @mcrypt_generic_init($td, $key, $iv);
  $decrypted = mdecrypt_generic($td, $data);
  mcrypt_generic_deinit($td);
  mcrypt_module_close($td);
  $result = des_pkcs5_unpad($decrypted);
  return $result;
}

/* * **** DES 加密解密库 End ******** */
/**
 * json数据强制转字符串类型
 * @param array $array 待转换的数组
 * @return string 转换后的json字符串
 * @author chunkuan <urcn@qq.com>
 */
function json_encode_s($array){
  foreach($array as &$v){
    $v = (string) $v;
  }
  return json_encode($array);
}

/**
 * RSA 公钥私钥格式化
 * @param string $str 无序公钥、私钥
 * @param string $type public OR private
 * @return string 格式化之后的公钥私钥
 * @author chunkuan <urcn@qq.com>
 */
function rsa_ges($str, $type = 'public'){
  $publicKeyString = "-----BEGIN ".strtoupper($type)." KEY-----\n";
  $publicKeyString .= wordwrap($str, 64, "\n", true);
  $publicKeyString .= "\n-----END ".strtoupper($type)." KEY-----\n";
  return $publicKeyString;
}

function encrypt_format($str){
  return mb_convert_encoding($str, 'gbk', 'utf-8');
  //return $str;
}

/**
 * 支付后同步返回验签结果
 * @param array $data 返回的post数组
 * @param string $pk 公钥
 * @param string $rk 私钥
 * @return array 解密后的支付数据
 * @author chunkuan <urcn@qq.com>
 */
function decryptReqData($data, $pk, $rk){
  $return_data = array('status' => 0, 'data' => array(), 'error' => '');
  $retCode = $data['retCode'];
  $retMsg = $data['retMsg'];
  $merId = $data['merId'];
  $ver = $data['ver'];
  $ts = $data['ts'];
  $reqType = $data['reqType'];
  $reqEncData = $data['encData'];
  $reqMac = $data['mac'];
  $macData = sha1(mb_convert_encoding($retCode.$retMsg.$merId.$ver.$ts.$reqType.$reqEncData, 'gbk', 'utf-8'));
  $reqMacStr = '';
  openssl_public_decrypt(hex2bin(strtolower($reqMac)), $reqMacStr, $pk);
  if($macData != $reqMacStr){
    $return_data['error'] = 'MAC校验失败';
    return $return_data;
  }
  $merKey = md5($merId);
  $reqData = des_decrypt($merKey, $reqEncData);
  if(empty($reqData)){
    $return_data['error'] = '解密业务参数失败';
    return $return_data;
  }
  $return_data['status'] = 1;
  $return_data['data'] = json_decode($reqData, true);
  return $return_data;
}

/**
 * 获取IP地址
 * @return string IP地址
 * @author chunkuan <urcn@qq.com>
 */
function getIP(){
  $ip = false;
  if(!empty($_SERVER["HTTP_CLIENT_IP"])){
    $ip = $_SERVER["HTTP_CLIENT_IP"];
  }
  if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
    if($ip){
      array_unshift($ips, $ip);
      $ip = FALSE;
    }
    for($i = 0; $i < count($ips); $i++){
      if(!eregi("^(10|172\.16|192\.168)\.", $ips[$i])){
        $ip = $ips[$i];
        break;
      }
    }
  }
  return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/**
 * 十六进制转二进制
 * @param string $data 待转换的十六进制数据
 * @return string 返回转换后的二进制数据
 * @author chunkuan <urcn@qq.com>
 */
if(!function_exists('hex2bin')){
  function hex2bin($data){
    $len = strlen($data);
    $newdata = '';
    for($i = 0; $i < $len; $i+=2){
      $newdata .= pack("C", hexdec(substr($data, $i, 2)));
    }
    return $newdata;
  }
}
/**
 * 创建&提交FORM表单
 * @param string $url 需要提交到的地址
 * @param array $data 需要提交的数据
 * @return void
 * @author chunkuan <urcn@qq.com>
 */
function build_form($url, $data){
  $sHtml = "<!DOCTYPE html><html><head><title>Waiting...</title>";
  $sHtml.= "<meta http-equiv='content-type' content='text/html;charset=utf-8'></head>
	  <body><form id='lakalasubmit' name='lakalasubmit' action='".$url."' method='POST'>";
  foreach($data as $key => $value){
    $sHtml.= "<input type='hidden' name='".$key."' value='".$value."' style='width:90%;'/>";
  }
  $sHtml .= "</form>正在提交订单信息...";
  $sHtml .= "<script>document.forms['lakalasubmit'].submit();</script></body></html>";
  exit($sHtml);
}

/**
 * 生成文件、读取文件
 * @param string $fname 文件名
 * @param array $data 需要保存的数据
 */
function F($fname, $data = ''){
  if(empty($fname)){
    return '';
  }
  $file = dirname(__DIR__).'/data/'.$fname.'.php';
  if(empty($data) && file_exists($file)){
    $c = file_get_contents($file);
    return json_decode($c, true);
  }else{
    @mkdir(dirname($file), 0777, true);
    file_put_contents($file, json_encode($data));
  }
}

/**
 * 读取文件
 */
function read_file($url, $timeout = 10){
  if(extension_loaded('curl')){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $info = curl_exec($ch);
    curl_close($ch);
  }else{
    $info = file_get_contents($url);
  }
  return $info;
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo = true, $label = null, $strict = true){
  $label = ($label === null) ? '' : rtrim($label).' ';
  if(!$strict){
    if(ini_get('html_errors')){
      $output = print_r($var, true);
      $output = '<pre>'.$label.htmlspecialchars($output, ENT_QUOTES).'</pre>';
    }else{
      $output = $label.print_r($var, true);
    }
  }else{
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    if(!extension_loaded('xdebug')){
      $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
      $output = '<pre>'.$label.htmlspecialchars($output, ENT_QUOTES).'</pre>';
    }
  }
  if($echo){
    echo($output);
    return null;
  }else{
    return $output;
  }
}

/**
 * 获取文件夹内容列表
 */
function get_dir_list($path){
  $fn = scandir($path);
  $list = array();
  foreach($fn as $v){
    if(substr($v, 0, 1) != '.'){
      $list[] = $v;
    }
  }
  return $list;
}

/**
 * zip文件内容摘要（对文件内容加盐做MD5,盐值拼接在内容之前）
 */
function fileDigestWithSalt($salt, $fileName){
	 $context = readFileStr($fileName);
	 $value = $salt.$context;
     return md5($value);
}

/**
 * 读取文件(zip中只含有一个文件)
 */
function readFileStr($filePath){
    $zip = zip_open($filePath);
    $buf = "";
    if ($zip){
        while($zip_entry = zip_read($zip)){
            if (zip_entry_open($zip, $zip_entry, "r")) {
			 $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			 zip_entry_close($zip_entry);
			}
        }
    }
    zip_close($zip);
    return $buf;
}


//转码方法
function auto_read($str, $charset='UTF-8') {
    $item = mb_detect_encoding($str,'auto');
    return mb_convert_encoding($str,$charset,$item);
}

/** 
* 转换一个String字符串为byte数组 
*/
function getBytes($str) { 
  $bytes = array(); 
  for($i = 0; $i < strlen($str); $i++){ 
     $bytes[] = ord($str[$i]); 
  } 
  return $bytes; 
}

function toStr($bytes) { 
    $str = ''; 
    foreach($bytes as $ch) { 
        $str .= chr($ch); 
    } 
    return $str; 
} 