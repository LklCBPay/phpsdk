<?php
include '../common/common.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>批量交易状态查询 页面</title>
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
    <h4>批量交易状态查询</h4>
    <form action="../ctrl/batTradeQuery.php" method="post">
      <p>协议版本号contract version number:<input type="text" name="ver" value="3.0.0" />
      </p>
      <p>商户号merchant ID:<input type="text" name="merId" value="<?php echo $config['mid']; ?>" />
      </p>
      <p>
        批量交易令牌(文件上传的bizToken):
        <input type="text" id="bizToken" name="bizToken" value="e52f8a52248336a28e0dd3678244e19a5d595ff650b7fae0bacb96151d53285d" />
      </p>
      <p>
        批量文件名(文件名与bizToken可任填其一， 或同时填写):
        <input type="text" name="fileName" value="DOPCHN000678_DS_20161117_000014_SRC.zip" />
      </p>
      <input type="submit" value="提交">
    </form>
    <p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>
  </body>
</html>