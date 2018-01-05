<?php
include '../common/common.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>上传批量文件页面</title>
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
    <h4>上传批量文件令牌</h4>
    <form action="../ctrl/batTradeUploadFile.php" method="post">
      <p>商户号merchant ID:<input type="text" name="merchantId" value="<?php echo $config['mid']; ?>" />
      </p>
      <p>
        批量交易令牌(bizToken):
        <input type="text" id="bizToken" name="bizToken" value="e52f8a52248336a28e0dd3678244e19a5d595ff650b7fae0bacb96151d53285d" />
      </p>
      <p>
        批量文件名:
        <input type="text" name="fileName" value="DOPCHN000678_DS_20161117_000014_SRC.zip" />
      </p>
      <p>
        文件本地全路径:
        <input type="text" name="filePath" value="C:\xampp\htdocs\phpsdk\test\" />
      </p>
      <p>
        安全码,此secCode为申请批量交易令牌时所用(该secCode为申请批量交易令牌时商户生成的secCode一致):
        <input type="text" name="secCode" value="20161031153500000026">
      </p>
      <input type="submit" value="提交">
    </form>
    <p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>
  </body>
</html>