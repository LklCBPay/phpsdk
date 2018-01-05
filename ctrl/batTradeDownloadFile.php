<?php

/**
 * 批量交易客户端
 * @author user
 */
include('../common/common.php');

$downLoadToken = $post["downLoadToken"]; // 批量交易令牌(downLoadToken)
$resFile = $post["resFile"];
$fileName = $post["fileName"];
$localFilePath = $post["localFilePath"];
$secCode = $post["secCode"];
$lklDigest = $post["lklDigest"];

$filePathName = $localFilePath.$fileName;

$headermsg = array(
  'Content-Type:binary/octet-stream;charset=UTF-8',
  'Content-Disposition:  attachment'
);

$url = "";
if($resFile != "true"){
	// 补回盘
	$url = $config['batchTradeUrl']."/fileService/downLoadPlusBck/".$downLoadToken;
} else {
	// 回盘
	$url = $config['batchTradeUrl']."/fileService/downLoadBckFileTest/".$downLoadToken;
}


/* * ********************发送查询请求************** */
$response = HttpClient::restCall($url, "","DOWNLOAD", $headermsg);

if($response == null){
	echo "http请求错误，请联系管理员";
}
  // retCode返回值
  $matches=array();
  if(preg_match('/retCode:\s+?(.+?)\s+?/', $response,$matches)){
    echo $matches[1];
  }
  if($matches[1] == null){
  	  echo "返回结果不正确";
  	  return;
  }
  if($matches[1] != '0000'){
  	  echo "返回结果不正确";
  	  return;
  }

// 保存文件到制定路径
file_put_contents($filePathName, $response);
echo "<br/><strong>文件下载成功：". $filePathName ."</strong>";

?>
<p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>