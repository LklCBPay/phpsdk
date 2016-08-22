<?php

/**
 * 商户对账请求
 * @author chunkuan <urcn@qq.com>
 */
include('../common/common.php');

$ver = $post['ver'];//协议版本号
$merId = $post['merId'];//商户号
$reqType = $post['reqType'];//请求业务类型
$startDate = $post['startDate'];//对账开始日期
$endDate = $post['endDate'];//对账结束日期
$notifyAddr = $post['notifyAddr'];//通知下载地址
$ext1 = $post['ext1'];//扩展字段1
$ext2 = $post['ext2'];//扩展字段2
//
//公钥
$pingtaiPublicKey = $config['pk'];
//私钥
$merPrivateKey = $config['rk'];

//商户随机3DES对称密钥
$merDesStr = md5(time());
//时间戳
$dateStr = date('YmdHis').mt_rand(100000, 999999);
//时间戳拼接对称密钥
$encKeyStr = $dateStr.$merDesStr;
//用公钥加密
$encrypted = "";
openssl_public_encrypt($encKeyStr, $encrypted, $pingtaiPublicKey);
//密钥密文
$encKey = strtoupper(bin2hex($encrypted));
//业务数据
$map = array(
  'startDate' => $startDate,
  'endDate' => $endDate,
  'notifyAddr' => $notifyAddr,
  'ext1' => $ext1,
  'ext2' => $ext2,
);
$json = json_encode_s($map);
//使用对称密钥加密以后的json1
$json1 = des_encrypt($merDesStr, $json);
$macStr = $merId.$ver.$dateStr.$reqType.$json1;
//商户私钥加密以后的mac
$macStr = sha1($macStr);
openssl_private_encrypt($macStr, $maced, $config['rk']); //私钥加密  
$mac = strtoupper(bin2hex($maced));

//需要请求的数据
$http_data = array(
  'ver' => $ver,
  'merId' => $merId,
  'ts' => $dateStr,
  'reqType' => $reqType,
  'encKey' => $encKey,
  'encData' => $json1,
  'mac' => $mac
);
/* * ********************发送对账请求************** */
$request = HttpClient::quickPost($config['ppayGateUrl'], $http_data);
$retMap = json_decode($request, true);
$retVer = $retMap["ver"];
$retMerId = $retMap["merId"];
$retTs = $retMap["ts"];
$retReqType = $retMap["reqType"];
$retEncData = $retMap["encData"];
$retMac = $retMap["mac"];
$retCode = $retMap["retCode"];
$retMsg = $retMap["retMsg"];
echo "----------以下为对账请求 同步返回的结果 ---------<br/>";
dump($retMap);
if($retCode != '0000'){
  echo $retMsg; //对账请求失败 & 输出请求失败原因
}else{
  
  $retMacStr = $retCode.$retMsg.$merId.$ver.$retTs.$retReqType.$retEncData;
  $retMacStr = sha1(mb_convert_encoding($retMacStr, 'gbk', 'utf-8'));
  //SHA-1加密响应返回的mac
  $reqMacStr1 = '';
  openssl_public_decrypt(hex2bin(strtolower($retMac)), $reqMacStr1, $pingtaiPublicKey);
   if($retMacStr != $reqMacStr1){
    echo 'MAC校验失败';
  }else{
    $reqData = des_decrypt($merDesStr, $retEncData);
    if(empty($reqData)){
      echo '解密业务参数失败';
    }else{
      $reqData = json_decode($reqData, true);
      echo "----------以下为对账请求 解签后的数据结果 ---------<br/>";
      dump($reqData);
    }
  }
}
?>
<p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>