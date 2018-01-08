<?php

/**
 * 批量交易客户端
 * @author user
 */
include('../common/common.php');

//公用请求字段
$ver = $post['ver']; // 版本号
$merId = $post['merId']; //商户号
//时间戳
$dateStr = date('YmdHis').mt_rand(100000, 999999);
$reqType = $post["reqType"]; // 业务类型 默认为""

$orderNo = $post["orderNo"]; // 商户订单号
$bizType = $post["bizType"]; //认证类型
$name = $post["name"]; //姓名
$certNo = $post["certNo"]; //证件号
$certType = $post["certType"]; //证件类型:目前支持00-身份证
$bankCardNo = $post["bankCardNo"]; //银行卡号
$bankMobile = $post["bankMobile"]; //银行预留手机号

$secCode = $dateStr; //YYYYMMDDHHMISS+6位随机字符串，保证永远唯一

//公钥
$pingtaiPublicKey = $config['pk'];
//私钥
$merPrivateKey = $config['rk'];
//商户Rand32
$merDesStr = md5(time());

//时间戳拼接对称密钥
$encKeyStr = $dateStr.$merDesStr;

//用公钥加密后数据，网上传输
$encrypted = "";
//PHP代码进行RSA的加密解密(用公钥加密)=Java RSAUtil.encryptByPublicKey method
openssl_public_encrypt($encKeyStr,$encrypted, $pingtaiPublicKey);
//密钥密文 换成Hex字符串
$encKey = strtoupper(bin2hex($encrypted));


//业务数据
$map = array(
  'orderNo' => $orderNo,
  'bizType' => $bizType,
  'name' => $name,
  'certNo' => $certNo,
  'certType' => $certType,
  'bankCardNo' => $bankCardNo,
  'bankMobile' => $bankMobile
);

$json = json_encode_s($map);

// AES对称密钥加密以后的json1
$aes = new AESUtil();
$json1 = $aes->encrypt($json, $merDesStr);

$macStr = $merId.$ver.$dateStr.$reqType.$json1;

//商户256 hash加密保持不变.
$macStr = hash('sha256', $macStr, true);
$macStr = bin2hex($macStr);

//私钥加密
$maced="";
openssl_private_encrypt($macStr, $maced, $merPrivateKey); //私钥加密  
$mac = strtoupper(bin2hex($maced));

//需要请求的数据   'reqType' => $reqType,
$http_data = array(
  'ver' => $ver,
  'merId' => $merId,
  'ts' => $dateStr,
  'encKey' => $encKey,
  'encData' => $json1,
  'mac' => $mac
);

$jsonhttp_data = json_encode_s($http_data);

$headermsg = array(
  'Content-Type:application/json;charset=UTF-8',
  'Connection:Keep-Alive',
  'Content-Length: ' . strlen($jsonhttp_data)
);

/* * ********************发送查询请求************** */
$request = HttpClient::restCall($config['batchTradeUrl']."/gate/auth", $jsonhttp_data,"POST", $headermsg);
if($request == null){
    echo '请求失败，请联系管理员';
}
$retMap = json_decode($request, true);
var_dump($retMap);

$retCode = $retMap["retCode"];
$retMsg = $retMap["retMsg"];
if($retCode != '0000'){
  echo $retMsg; //查询失败 & 输出查询失败原因
}else{
  $retVer = $retMap["ver"];
  $retMerId = $retMap["merId"];
  $retTs = $retMap["ts"];
  $retEncData = $retMap["encData"];
  $retMac = $retMap["mac"];

  // sha256 加密
  $retMacStr = $retCode.$retMsg.$merId.$ver.$retTs.$retEncData;
  $retMacStr = hash('sha256', mb_convert_encoding($retMacStr, 'gbk', 'utf-8'), true);
  $retMacStr = bin2hex($retMacStr);

  //SHA-1加密响应返回的mac
  $reqMacStr1 = '';
  openssl_public_decrypt(hex2bin(strtolower($retMac)), $reqMacStr1, $pingtaiPublicKey);
  if($retMacStr != $reqMacStr1){
    echo 'MAC校验失败';
  }else{
  	// 解密效果如下
  	$aes2 = new AESUtil();
    $reqData = $aes2->decrypt($retEncData, $merDesStr);
    if(empty($reqData)){
      echo '解密业务参数失败';
    }else{
      echo "<br/>----------以下为认证的结果 ---------<br/>";
      echo mb_convert_encoding($reqData, 'utf-8', 'gbk');
    }
  }
}
?>
<p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>