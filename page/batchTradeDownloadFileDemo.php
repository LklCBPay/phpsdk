<?php
include '../common/common.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>批量交易接口-下载（补）回盘文件 Batch Transaction Interface页面</title>
    <script src="../js/jquery-1.7.1.js" type="text/javascript"></script>
    <style>
      *{list-style: none;padding:0;margin:0;}
      body{padding: 10px;}
      body p{line-height:35px;}
    </style>
  </head>
  <script type="text/javascript">
    $(function () {
        $("#form").submit(function () {
            return checkInput();
        });
    });
  </script>
  <body>
    <h4>下载（补）回盘文件 Batch Transaction Interface</h4>
    <form action="../ctrl/batTradeDownloadFile.php" method="post">
      <p>
        补回盘文件下载令牌(downLoadToken):
        <input type="text" id="downLoadToken" name="downLoadToken" value="e52f8a52248336a28e0dd3678244e19a5d595ff650b7fae0bacb96151d53285d" />
      </p>
      <p>
        true:回盘文件;false-补回盘文件:
        <input type="text" name="resFile" value="true">
      </p>
      
      
      <p>
        文件名(请求不用，本地保存用):
        <input type="text" name="fileName" value="DOPCHN000678_DS_20161117_000015_SRC.zip" />
      </p>
      <p>
        文件本地路径(请求不用，本地保存用):
        <input type="text" name="localFilePath" value="C:\xampp\htdocs\phpsdk\test\" />
      </p>
      <input type="submit" value="提交">
    </form>
    <p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>
  </body>
</html>