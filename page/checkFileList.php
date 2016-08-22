<?php
include '../common/common.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>查看对账文件列表query merchant download file list</title>
    <script src="../js/jquery-1.7.1.js" type="text/javascript"></script>
    <style>
      *{list-style: none;padding:0;margin:0;}
      body{padding: 10px;}
      body table td{line-height:35px;}
    </style>
  </head>
  <body>
    <h4>对账文件列表</h4>
    <p>&nbsp;</p>
    <?php 
    $path = _PATH_."/download";
    $list = get_dir_list($path);
    foreach($list as $li){
      if($li != '.'  && $li != '..'){
         $li = iconv("gb2312","UTF-8",$li); 
        echo "<p><a href='../download/$li' target='_blank'>$li</a></p>";
      }
    }
    if(empty($list)){
      echo "<p>暂时没有对账文件！</p>";
    }
    ?>
    <p></p>
    <p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>
  </body>
</html>