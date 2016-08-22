<?php

/**
 * 模拟商户下单逻辑处理
 * @author chunkuan <urcn@qq.com>
 */
include('../common/common.php');
//3Des密钥
$mid = $post['merId'];
$merDesStr = md5($mid);
$encKeyStr = $post['ts'].$merDesStr;
$encrypted = $maced = '';
//公钥加密  
openssl_public_encrypt($encKeyStr, $encrypted, $config['pk']);
//密钥密文
$encKey = strtoupper(bin2hex($encrypted));
$ver = $post['ver'];
$reqType = $post['reqType'];
$_orderData = array(
  'merOrderId' => $post['merOrderId'],
  'currency' => $post['currency'],
  'ts' => $post['ts'],
  'orderAmount' => $post['orderAmount'],
  'payeeAmount' => $post['orderAmount'],
  'orderTime' => $post['orderTime'],
  'bizCode' => $post['busiRange'],
  'orderSummary' => $post['orderSummary'],
  'timeZone' => $post['timeZone'],
  'pageUrl' => $post['pageUrl'],
  'bgUrl' => $post['bgUrl'],
  'ext1' => $post['ext1'],
  'ext2' => $post['ext2']
);
$json_init = json_encode_s($_orderData);
$json = des_encrypt($merDesStr, $json_init);
$macStr = $mid.$ver.$post['ts'].$reqType.$json;
$macStr = sha1($macStr);
openssl_private_encrypt($macStr, $maced, $config['rk']); //私钥加密  
$mac = strtoupper(bin2hex($maced));
//表单提交
$data = array(
  'ver' => $ver,
  'merId' => $mid,
  'ts' => $post['ts'],
  'pageUrl' => $post['pageUrl'],
  'reqType' => $reqType,
  'encKey' => $encKey,
  'encData' => $json,
  'mac' => $mac
);
//表单提交
build_form($config['ppayGateUrl'], $data);
