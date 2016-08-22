<?php
include '../common/common.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>跨境PC 网关模拟商户下单页面cross border PC gateway simulate merchant make order web page</title>
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
            var pid = $("#pid").val();
            var orderamount = $("#orderamount").val();
            var sharedata = "1^" + pid + "^" + orderamount + "^0^0.00^desc";
            $("#payeeamount").val(orderamount);
            $("#sharingdata").val(sharedata);
            return checkInput();
        });
    });
  </script>
  <body>
    <h4>模拟商户下单页面 Simulate merchant make order web page.</h4>
    <p>商户订单数据 Merchant order fata.</p>
    <form action="../ctrl/createWebOrder.php" method="post">
      <p>协议版本号contract version number:<input type="text" name="ver" value="1.0.0" />
      </p>
      <p>业务类型business type:<input type="text" name="reqType" value="B0002" />
      </p>
      <p>商户号merchant ID:<input type="text" name="merId" value="<?php echo $config['mid']; ?>" />
      </p>
      <p>跨境业务类型cross-border business type:<select id="busiRange" name="busiRange">
          <option value="122030">货物贸易Trade in goods</option>
          <option value="222024">航空机票Airline ticket</option>
          <option value="223029">酒店住宿Hotel accommodation</option>
          <option value="223022">学费教育Tuition and education</option>
        </select>
      </p>
      <p>
        时间戳timestamp:
        <input type="text" name="ts" value="<?php echo date('YmdHis').mt_rand(100000, 999999) ?>" />
      </p>
      <p>
        商户订单号:
        <input type="text" id="merOrderId" name="merOrderId" value="PH<?php echo date('YmdHis').mt_rand(10000, 99999) ?>" />
      </p>
      <p>
        订单币种order currency:
        <input type="text" name="currency" value="CHF" />
      </p>
      <p>
        订单金额order amount:
        <input type="text" name="orderAmount" value="81.00" />
      </p>
      <p>
        订单概要order summary:
        <input type="text" name="orderSummary">
      </p>
      <p>
        订单日期order time:
        <input type="text" name="orderTime" value="<?php echo date('YmdHis'); ?>">
      </p>
      <p>
        订单有效期order valid time:
        <input type="text" name="orderEffTime" value="<?php echo date('YmdHis', time() + 24 * 3600 * 3); ?>">
      </p>
      <p>时区timezone:<input type="text" name="timeZone" value="GMT+8">
      </p>
      <p>
        前台跳转地址merchant website link:
        <input type="text" name="pageUrl" size="70" value="<?php echo $config['callback_url']; ?>">
      </p>
      <p>
        后台通知地址Background response address:
        <input type="text" name="bgUrl" size="70" value="<?php echo $config['notify_url']; ?>">
      </p>
      <p>
        扩展字段1Extension field1:
        <input type="text" name="ext1" value="">
      </p>
      <p>
        扩展字段2Extension field2:
        <input type="text" name="ext2" value="">
      </p>
      <input type="submit" value="提交订单">
    </form>
    <p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>
  </body>
</html>