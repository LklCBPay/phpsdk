<?php

/**
 * 批量交易客户端
 * @author user
 */
include('../common/common.php');

//公用请求字段
$merId = $post['merchantId']; //商户号
//时间戳
$dateStr = date('YmdHis').mt_rand(100000, 999999);
$secCode = $dateStr; //YYYYMMDDHHMISS+6位随机字符串，保证永远唯一

$bizToken = $post["bizToken"]; // 批量交易令牌(bizToken)
$fileName = $post["fileName"]; //批量文件名
$filePath = $post["filePath"]; //文件本地全路径
$secCode = $post["secCode"]; //安全码

$sign = md5(secCode.$merId.$bizToken.$fileName, false); //zip文件内容摘要（对文件内容加盐做MD5,盐值拼接在内容之前）

$filePathName = $filePath.$fileName;
//业务数据
$file_upload_data = file_get_contents($filePathName);

$headermsg = array(
  'Content-Type:binary/octet-stream;charset=UTF-8',
  'Connection:Keep-Alive',
  'Content-Length: ' . strlen($file_upload_data)
);

$url = $config['batchTradeUrl']."/gate/batchBizFileUpload"."/".$merId."/".$fileName."/".$bizToken."/".$sign;

/* * ********************发送查询请求************** */
$request = HttpClient::restCall($url, $file_upload_data,"UPLOAD", $headermsg);
if($request == null){
    echo '请求失败，请联系管理员';
}
$retMap = json_decode($request, true);
var_dump($retMap);

$retCode = $retMap["retCode"];
$retMsg = $retMap["retMsg"];

echo $retCode."<br/>";

if($retCode != '0000'){
  echo $retMsg; //查询失败 & 输出查询失败原因
}else{
  $sign = $retMap["sign"];
  
  echo "<strong>sign value：". $sign ."</strong>";
}
?>
<p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>