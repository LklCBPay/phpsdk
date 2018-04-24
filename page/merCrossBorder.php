<?php
include '../common/common.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>单笔实时收款cross border PC gateway simulate merchant make order web page</title>
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
    <p>单笔实时收款.</p>
    <form action="../ctrl/merCrossBorder.php" method="post">
      <p>协议版本号contract version number:<input type="text" name="ver" value="1.0.0" />
      </p>
      <p>业务类型business type:<input type="text" name="reqType" value="B0013" />
      </p>
      <p>payTypeId:<input type="text" name="payTypeId" value="4" />
      </p>
      <p>商户号merchant ID:<input type="text" name="merId" value="<?php echo $config['mid']; ?>" />
      </p>
      <p>
        时间戳timestamp:
        <input type="text" name="ts" value="<?php echo date('YmdHis').mt_rand(100000, 999999) ?>" />
      </p>
      <p>
        商户请求流水号:
        <input type="text" id="merOrderId" name="merOrderId" value="PH<?php echo date('YmdHis').mt_rand(10000, 99999) ?>" />
      </p>
      <p>
        交易币种(标价币种)order currency:
        <input type="text" name="currency" value="CNY" />
      </p>
      <p>
        交易金额order amount:
        <input type="text" name="orderAmount" value="81.00" />
      </p>
      <p>
        主收款方应收额:
        <input type="text" name="payeeAmount" value="10.00"  />
      </p>
      <p>
        银行卡号:
        <input type="text" name="cardNo" value="6217001210067001888">
      </p>
      <p>
        持卡人姓名:
        <input type="text" name="clientName" value="测试">
      </p>
      <p>
        证件类型:
        <input type="text" name="certType" value="01">
      </p>
      <p>
        证件号码:
        <input type="text" name="clientId"  value="341221199001122345">
      </p>
      <p>
        银行预留手机号码:
        <input type="text" name="mobile" value="18004123581">
      </p>
      <p>
        后台应答地址:
        <input type="text" name="bgUrl" value="http://test:8080/">
      </p>
      <p>
        业务类型:
        <input type="text" name="busiCode" value="122030">
      </p>
      <p>
        商品描述:
        <input type="text" name="orderSummary" value="商品描述">
      </p>
      <p>
        商户客户会员ID:
        <input type="text" name="customNumberId" value="111">
      </p>
      <p>
        海关地区编码:
        <input type="text" name="cuId" value="1">
      </p>
      <p>
        海关备案号:
        <input type="text" name="customRegNo" value="1">
      </p>
      <p>
        海关备案名称:
        <input type="text" name="customName" value="2">
      </p>
      <p>
        商户交易时间:
        <input type="text" name="orderTime" value="<?php echo date('YmdHis'); ?>">
      </p>
      <p>
        交易有效时间:
        <input type="text" name="orderEffTime" value="<?php echo date('YmdHis', time() + 24 * 3600 * 3); ?>">
      </p>
      <p>
        扩展字段1Extension field1:
        <input type="text" name="ext1" value="111">
      </p>
      <p>
        扩展字段2Extension field2:
        <input type="text" name="ext2" value="2222">
      </p>
      <input type="submit" value="提交订单">
    </form>
    <p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>
  </body>
</html>