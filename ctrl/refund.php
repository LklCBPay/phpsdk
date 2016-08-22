<?php

/**
 * 订单退款
 * @author chunkuan <urcn@qq.com>
 */
include('../common/common.php');

$ts = $post['ts'];
$ver = $post['ver']; // 版本号
$merId = $post['merId']; //商户号
$reqType = $post["reqType"]; // 业务类型

$seqId = $post["seqId"]; // 退款订单号
$merOrderId = $post["merOrderId"]; // 原订单号
$retTime = $post['retTime']; //退款提交时间
$retAmt = $post['retAmt']; //退款金额
$retCny = $post['retCny']; //退款币种
$ext1 = $post["ext1"]; //扩展字段1
$ext2 = $post["ext2"]; //扩展字段2
//
//公钥
$pingtaiPublicKey = $config['pk'];
//私钥
$merPrivateKey = $config['rk'];

//商户随机3DES对称密钥
$merDesStr = md5(time());
//时间戳
$dateStr = $ts;
//时间戳拼接对称密钥
$encKeyStr = $dateStr.$merDesStr;
//用公钥加密
$encrypted = "";
openssl_public_encrypt($encKeyStr, $encrypted, $pingtaiPublicKey);
//密钥密文
$encKey = strtoupper(bin2hex($encrypted));
//业务数据
$map = array(
  'seqId' => $seqId,
  'merOrderId' => $merOrderId,
  'retTime' => $retTime,
  'retAmt' => $retAmt,
  'retCny' => $retCny,
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

/* * ********************发送查询请求************** */
$request = HttpClient::quickPost($config['ppayGateUrl'], $http_data);
$retMap = json_decode($request, true);
$retCode = $retMap["retCode"];
$retMsg = $retMap["retMsg"];
if($retCode != '0000'){
  echo $retMsg; //退款请求失败 & 输出失败原因
}else{
  $retVer = $retMap["ver"];
  $retMerId = $retMap["merId"];
  $retTs = $retMap["ts"];
  $retReqType = $retMap["reqType"];
  $retEncData = $retMap["encData"];
  $retMac = $retMap["mac"];
  $retMacStr =sha1(mb_convert_encoding($retCode.$retMsg.$merId.$ver.$retTs.$retReqType.$retEncData, 'gbk', 'utf-8'));
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
      $statusArray = array(
        0 => '退款成功',
        1 => '处理中',
        2 => '失败 '
      );
      echo "----------以下为商户退款请求 解签后的结果 ---------<br/>";
      dump($reqData);
      echo "<strong>退款请求状态：". $statusArray[$reqData['retStatu']] ."</strong>";
    }
  }
}
?>
<p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>