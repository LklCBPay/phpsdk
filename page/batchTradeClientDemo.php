<?php
include '../common/common.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>申请批量交易令牌页面</title>
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
    <h4>申请批量交易令牌</h4>
    <form action="../ctrl/batTradeClient.php" method="post">
      <p>协议版本号contract version number:<input type="text" name="ver" value="3.0.0" />
      </p>
      <p>商户号merchant ID:<input type="text" name="merId" value="<?php echo $config['mid']; ?>" />
      </p>
      <p>
        业务类型:
        <input type="text" id="bizType" name="bizType" value="DS" />
      </p>
      <p>
        zip包中文件数量
        <input type="text" name="fileCount" value="1" />
      </p>
      <p>
        Zip包中交易(不超过5000笔):
        <input type="text" name="bizCount" value="5" />
      </p>
      <p>
        Zip包中交易总金额，单位：分
        <input type="text" name="bizAmount" value="15000">
      </p>
      <input type="submit" value="提交">
    </form>
    <p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>
  </body>
</html>