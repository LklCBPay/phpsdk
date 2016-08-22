<?php

/**
 * 下载对账文件
 * @author chunkuan <urcn@qq.com>
 */
include('../common/common.php');
//文件地址
$downLoadUrl = $_REQUEST['downLoadUrl'];
//保存文件
if(@fopen($downLoadUrl, 'r')){
  $info = read_file($downLoadUrl);
  $path_ = explode('/', $downLoadUrl);
  $file = _PATH_.'/download/'.$path_[count($path_)-1];
  @mkdir(dirname($file), 0777, true);
  if(strlen($info) > 100){
    if(file_put_contents($file, $info)){
      exit("保存成功");
    }
  }
  echo "保存失败";
}else{
  echo "远端文件不存在！";
}