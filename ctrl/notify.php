<?php
/**
 * 模拟用户支付后同步回调页面
 * @author chunkuan <urcn@qq.com>
 */
include('../common/common.php');
//接受通知数据
$postdata = file_get_contents("php://input");
if(is_array($postdata)){$postdata = json_encode($postdata);}


/**
 * ****！！！请注意 ！！！****
 * 本行引入的目的，是为了让您更方便的了解接口返回的数据格式
 * 请在正式项目中屏蔽此行代码即可
 **/
//$postdata = file_get_contents('../data/notify_data.php');



//将JSON数据转化为数组
$post = json_decode($postdata, true);
$merId = $post['merId'];
$merPrivateKey = $config['rk']; //私钥
$platPublicKey = $config['pk']; //公钥
//解密请求数据
$ts = $post['ts'];
$reqType = $post['reqType'];
$reqEncKey = $post['encKey'];
$reqEncData = $post['encData'];
$reqMac = $post['mac'];
echo "----------以下为支付后异步通知的信息 解签后的结果 ---------<br/>";
//用请求方公钥解签
$reqmacStr = sha1($ts.$reqType.$reqEncData);
$reqMacStr = '';
openssl_public_decrypt(hex2bin(strtolower($reqMac)), $reqMacStr, $platPublicKey);
if($reqMacStr != $reqmacStr){
  echo 'mac校验失败';
}else{
  $merKey = ""; //商户对称密钥
  openssl_private_decrypt(hex2bin(strtolower($reqEncKey)), $merKey, $merPrivateKey);
  $merKey = substr($merKey, strlen($merKey) - 32, 32);
  $reqData = des_decrypt($merKey, $reqEncData);
  if($reqData){
    $reqData = json_decode($reqData, true);
    //echo '解密验签成功！<br/>';
    //echo '返回处理信息:<br/>';
    //print_r($reqData);
    //echo "<div style='color:red;'>请在此处加入您的订单处理代码</div>";

    //以下处理返回响应
    $map = array(
      'merOrderId' => $reqData['merOrderId'],
      'transactionId' => $reqData['transactionId'],
    );
    $resData = json_encode_s($map);
    $jsonMap = array(
      'merId' => $merId,
      'ver' => $post['ver'],
      'ts' => $ts,
      'reqType' => $reqType,
    );
    $encData = des_encrypt($merKey, $resData);
    $mac_tmp = sha1($ts.$reqType.$encData);
    $mac = '';
    openssl_private_encrypt($mac_tmp, $mac, $merPrivateKey);
    $mac = strtoupper(bin2hex($mac));
    $jsonMap['encData'] = $encData;
    $jsonMap['mac'] = $mac;
    $jsonMap['retCode'] = '0000';
    $jsonMap['retMsg'] = '操作成功';
    //echo json_encode_s($jsonMap);
	echo 'OK';
  }
}

?>
