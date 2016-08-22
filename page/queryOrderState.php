<?php
include '../common/common.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>模拟商户交易订单查询 simulate merchant trade query</title>
    <script src="../js/jquery-1.7.1.js" type="text/javascript"></script>
    <style>
      *{list-style: none;padding:0;margin:0;}
      body{padding: 10px;}
      body table td{line-height:35px;}
    </style>
  </head>
  <body>
    <h4>模拟商户交易订单查询</h4>
    <form action="../ctrl/queryOrder.php" method="post">
      <table>
        <tr>
          <td>协议版本号</td>
          <td><input type="text" name="ver" value="1.0.0" readonly="readonly"/></td>
          <td></td>
        </tr>
        <tr>
          <td>业务类型</td>
          <td><input type="text" name="reqType" value="B0007" readonly="readonly" /></td>
          <td>不能为空</td>
        </tr>
        <tr>
          <td>商户号</td>
          <td><input type="text" id="merId" name="merId" value="<?php echo $config['mid'];?>"></td>
          <td>商户标识(不为空，商户必须存在并开通)</td>
        </tr>
        <tr>
          <td>拉卡拉流水号</td>
          <td><input type="text" name="transactionId" value="2015051800036651" /></td>
          <td>不能为空</td>
        </tr>
        <tr>
          <td>商户订单号</td>
          <td><input type="text" id="merOrderId" name="merOrderId"  value="20150518224132723316"></td>
          <td>不能为空</td>
        </tr>
        <tr>
          <td>扩展字段1</td>
          <td><input type="text" name="ext1" value=""></td>
          <td>扩展字段1(不能大于512字节)</td>
        </tr>
        <tr>
          <td>扩展字段2</td>
          <td><input type="text" name="ext2" value=""></td>
          <td>扩展字段2(不能大于512字节)</td>
        </tr>
      </table>
      <input type="submit" value="提交">
    </form>
    <p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>
  </body>
</html>