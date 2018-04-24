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
$dateStr = $post['ts']; //商户号
$reqType = $post["reqType"]; // 业务类型 暂时默认为""
$payTypeId = $post["payTypeId"];; //业务值 暂时默认为空

$bizType = $post["bizType"]; // 业务类型：DS-代收
	

// 参数
$merOrderId = $post["merOrderId"];
$currency = $post["currency"];
$orderAmount = $post["orderAmount"]; 
$payeeAmount = $post["payeeAmount"];
$cardNo = $post["cardNo"];
$clientName = $post["clientName"];
$certType = $post["certType"];
$clientId = $post["clientId"];
$mobile = $post["mobile"]; 
$bgUrl = $post["bgUrl"];
$busiCode = $post["busiCode"];
$orderSummary = $post["orderSummary"]; 
$customNumberId = $post["customNumberId"];
$cuId = $post["cuId"];
$customRegNo = $post["customRegNo"]; 

$customName = $post["customName"]; 
$orderTime = $post["orderTime"]; 
$orderEffTime = $post["orderEffTime"]; 
$ext1 = $post["ext1"]; 
$ext2 = $post["ext2"]; 

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
  'merOrderId' => $merOrderId,
  'currency' => $currency,
  'orderAmount' => $orderAmount,
  'payeeAmount' => $payeeAmount,
  'cardNo' => $cardNo,
  'clientName' => $clientName,
  'certType' => $certType,
    'clientId' => $clientId,
    'mobile' => $mobile,
    'bgUrl' => $bgUrl,
    'busiCode' => $busiCode,
    'orderSummary' => $orderSummary,
    'customNumberId' => $customNumberId,
    'cuId' => $cuId,
    'customRegNo' => $customRegNo,
    'customName' => $customName,
    'orderTime' => $orderTime,
    'orderEffTime' => $orderEffTime,
    'ext1' => $ext1,
    'ext2' => $ext2
);

$json = json_encode_s($map);

$json1="";
if("3.0.0" == $ver){
	// AES对称密钥加密以后的json1
	$aes = new AESUtil();
	$json1 = $aes->encrypt($json, $merDesStr);
} else {
	// DES对称密钥加密以后的json1
	$json1 = des_encrypt($merDesStr, $json);
}

$macStr = "";
if ("1.0.0" == $ver) {
    if (empty($payTypeId)) {
        $macStr = $merId.$ver.$dateStr.$reqType.$json1;
    } else {
        $macStr = $merId.$ver.$dateStr.$reqType.$json1.$payTypeId;
    }
} else if ("2.0.0" == $ver || "3.0.0" == $ver) {
    $macStr = $merId.$ver.$dateStr.$json1;
}


if ("3.0.0" == $ver) {
	//商户256 hash加密保持不变.
	$macStr = hash('sha256', $macStr, true);
	$macStr = bin2hex($macStr);
} else {
	//商户sha1 hash加密保持不变.
	$macStr = sha1($macStr);
}

//私钥加密
$maced="";
openssl_private_encrypt($macStr, $maced, $merPrivateKey); //私钥加密  
$mac = strtoupper(bin2hex($maced));

//需要请求的数据   'reqType' => $reqType,
$http_data = array(
  'ver' => $ver,
  'merId' => $merId,
  'reqType' => $reqType,
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
$request = HttpClient::restCall($config['batchTradeUrl']."/ppayGate/merCrossBorderAction.do", $jsonhttp_data,"POST", $headermsg);
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
  $retReqType = $retMap["reqType"];
  $retPayTypeId = $retMap["payTypeId"];
  
  $retMacStr = "";
  // sha256 加密
  if ("1.0.0" == $retVer){
        if (empty($retPayTypeId)) {
            $retMacStr = $retCode.$retMsg.$retMerId.$retVer.$retTs.$retReqType.$retEncData;
        } else {
            $retMacStr = $retCode.$retMsg.$retMerId.$retVer.$retTs.$retReqType.$retEncData.$retPayTypeId;
        }
  } else if ("2.0.0" == $retVer) {
        $retMacStr = $retCode.$retMsg.$retMerId.$retVer.$retTs.$retEncData;
  } else if ("3.0.0"  == $retVer) {
        $retMacStr = $retCode.$retMsg.$retMerId.$retVer.$retTs.$retEncData;
  }
  //$retMacStr = $retCode.$retMsg.$merId.$ver.$retTs.$retEncData;
	if ("3.0.0" == $retVer) {
	  $retMacStr = hash('sha256', mb_convert_encoding($retMacStr, 'gbk', 'utf-8'), true);
	  $retMacStr = bin2hex($retMacStr);
	} else {
		//商户sha1 hash加密保持不变.
		$retMacStr = sha1(mb_convert_encoding($retMacStr, 'gbk', 'utf-8'));
	}


  //SHA-1加密响应返回的mac
  $reqMacStr1 = '';
  openssl_public_decrypt(hex2bin(strtolower($retMac)), $reqMacStr1, $pingtaiPublicKey);
  if($retMacStr != $reqMacStr1){
    echo 'MAC校验失败';
  }else{
  	$reqData = "";
  	// 解密效果如下
    if ("3.0.0" == $retVer) {
        $aes2 = new AESUtil();
        $reqData = $aes2->decrypt($retEncData, $merDesStr);
    } else {
        $reqData = des_decrypt($merDesStr, $retEncData);
    }
    if(empty($reqData)){
      echo '解密业务参数失败';
    }else{
      $reqData = json_decode($reqData, true);
      echo "<br/>----------以下为交易令牌的结果 ---------<br/>";
      dump($reqData);
    }
  }
}
?>
<p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>