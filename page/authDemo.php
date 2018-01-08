<?php
include '../common/common.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>认证接口页面</title>
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
    <h4>认证接口</h4>
    <form action="../ctrl/auth.php" method="post">
      <p>协议版本号contract version number:<input type="text" name="ver" value="3.0.0" />
      </p>
      <p>商户号merchant ID:<input type="text" name="merId" value="<?php echo $config['mid']; ?>" />
      </p>
      <p>
        商户订单号:
        <input type="text" id="orderNo" name="orderNo" value="123123123" />
      </p>
      <p>
        认证类型(00：身份认证,01：三要素,02：四要素):
        <input type="text" name="bizType" value="00" />
      </p>
      <p>
        姓名:
        <input type="text" name="name" value="测试XX" />
      </p>
      <p>
        证件号:
        <input type="text" name="certNo" value="321111111111111111">
      </p>
      <p>
        证件类型:
        <input type="text" name="certType" value="00">
      </p>
      <p>
        银行卡号(当bizType为01和02时必填):
        <input type="text" name="bankCardNo" value="">
      </p>
      <p>
        银行预留手机号(当bizType为02时必填):
        <input type="text" name="bankMobile" value="">
      </p>
      <input type="submit" value="提交">
    </form>
    <p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>
  </body>
</html>