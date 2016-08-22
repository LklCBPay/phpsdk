<?php

/**
 * 订单查询
 * @author chunkuan <urcn@qq.com>
 */
include('../common/common.php');

$ver = $post['ver']; // 版本号
$merId = $post['merId']; //商户号
$reqType = $post["reqType"]; // 业务类型
$merOrderId = $post["merOrderId"]; // 商户订单号
$transactionId = $post["transactionId"]; //流水号
$ext1 = $post["ext1"]; //扩展字段1
$ext2 = $post["ext2"]; //扩展字段2
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
  'merOrderId' => $merOrderId,
  'transactionId' => $transactionId,
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
//$request = '{"retCode":"0000","encData":"7454195FC02E5D0DCE9073AED00F843155031E9C5EAF09C7DE072F8678B23D452D1135CFB58043BEEA03DF2011BA4B05C0A17377424D6D8F1C2EA55E5A8E7EA43D32EDB94F8514DD154967EE965CE641BA9104486B863BBB4BAD94ECFE37BB28D276CCC28A2EE7030443E7C3468E0DC5F55D4FCC3D3FA57790CD2DC50A27AA30F174EE01CCBFBBA2912C5DA0717FD9E3ED883613E3724D8E2D56E5B7871BEBED9FB014E6D73FF27B5341A88CF254D8D442D9B8726E6375E4","ts":"20150521225725844733","retMsg":"交易查询成功","encKey":"B35427801341B181BCC88411D3A2A88C4E60002EE386494A5DF7425330D7374285B1C9FE992353C669F21F3833C51DC40C9D703052B18E9755987526F51BF2E52BCA0296C3A22B2190A92E65BBBBFEA996F7B7D1017B34D1603A8C82238B97A6CE0E9FEEAA5C955745450C0718335019334060991AD57645E9E4D03A463D4DA6","reqType":"B0007","merId":"COPMAC000001","mac":"81A3EEFA0E9CF5C49E6C9E846CDE7BEB0E5FE3BEE5B254F2760EAB612A9374DD5156F12D4CA7402D77684FE6A8AB9DD71E803A3DB7053DC992CD2F09AC997C066CDA62F7365B219902629B826A26FDC94BA51D8C65E0EDAB7BB9DC400C344B21F4040C2141CB72483271765E584E9CD0C3684FD5192854E9A3A188062E363E17","ver":"1.0.0"}';
$retMap = json_decode($request, true);
$retCode = $retMap["retCode"];
$retMsg = $retMap["retMsg"];
if($retCode != '0000'){
  echo $retMsg; //查询失败 & 输出查询失败原因
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
        0 => '未支付',
        1 => '已支付',
        2 => '失效',
        3 => '已冲正',
        4 => '已撤销',
        99 => '关闭'
      );
      echo "----------以下为订单查询 解签后的结果 ---------<br/>";
      dump($reqData);
      echo "<strong>订单状态：". $statusArray[$reqData['payResult']] ."</strong>";
    }
  }
}
?>
<p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>