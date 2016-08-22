<?php
include '../common/common.php';
$startTime = '20150424';
$endTime = '20150610';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>商户对帐</title>
    <script src="../js/jquery-1.7.1.js" type="text/javascript"></script>
    <style>
      *{list-style: none;padding:0;margin:0;}
      body{padding: 10px;}
      body p{line-height:35px;}
    </style>
  </head>
  <body>
    <h4>商户对帐</h4>
    <form action="../ctrl/checkAccounts.php" method="post">
      <table>
        <tr>
          <td>商户标识：</td>
          <td><input type="text" name="merId" value="<?php echo $config['mid'];?>" size="50"></td>
          <td><span>不为空，支付平台存在并已开通</span></td>
        </tr>
        <tr>
          <td>版本号</td>
          <td><input type="text" name="ver" value="1.0.0" size="50" readonly="readonly"></td>
          <td><span>不为空</span></td>
        </tr>
        <tr>
          <td>业务类型：</td>
          <td><input type="text" name="reqType" value="B0008" size="50" readonly="readonly" /></td>
          <td><span>不能为空</span></td>
        </tr>
        <tr>
          <td>对账开始日期：</td>
          <td><input type="text" name="startDate" value="<?php echo $startTime;?>" size="50"></td>
          <td><span>不为空，yyyyMMdd</span></td>
        </tr>
        <tr>
          <td>对账结束日期：</td>
          <td><input type="text" name="endDate" value="<?php echo $endTime;?>" size="50"></td>
          <td><span>不为空，yyyyMMdd</span></td>
        </tr>
        <tr>
          <td>通知下载地址：</td>
          <td><input type="text" name="notifyAddr" value="<?php echo $config['notifyAddrUrl'];?>" size="50"></td>
          <td><span>不为空</span></td>
        </tr>
      </table>
      <input type="submit" value="提交">
    </form>
    <p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>
  </body>
</html>