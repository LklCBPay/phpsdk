<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>拉卡拉 跨境支付 PHP实例</title>
    <script src="js/jquery-1.7.1.js" type="text/javascript"></script>
    <style>
      *{list-style: none;padding:0;margin:0;}
      body{padding: 10px;}
      .page_ul{}
      .page_ul li{line-height: 40px;height:40px;}
      .page_ul li i{
        background: #00AA00;border-radius: 15px;display: inline-block;font-style: normal;
        width:25px;height:25px;line-height: 25px;text-align: center;color:#FFFFFF;
      }
      a{text-decoration: none; color:#0000ff}
      a:hover{text-decoration: underline;}
      a:active{color:#0000cc;}
    </style>
  </head>
  <body>
    ----------------拉卡拉 跨境支付 PHP实例 Cross border pay PHP DEMO ------------------------
    <ul class="page_ul">
      <li>
        <i>1</i> <a href="page/webMerOrderForm.php">模拟商户下单页面 Simulate merchant make order web page.</a>
      </li>
      <li>
        <i>2</i> <a href="ctrl/callback.php">支付后同步返回页面 CallBack.</a>
      </li>
      <li>
        <i>3</i> <a href="ctrl/notify.php">支付后异步通知页面 Notify.</a>
      </li>
      <li>
        <i>4</i> <a href="page/queryOrderState.php">模拟商户交易订单查询 simulate merchant trade query order.</a>
      </li>
      <li>
        <i>5</i> <a href="page/checkAccountsForm.php">模拟商户对账文件下载 simulate merchant download reconciliation file</a>
      </li>
      <li>
        <i>6</i> <a href="page/checkFileList.php">查看对账文件列表query merchant download file list</a>
      </li>
      <li>
        <i>7</i> <a href="page/merRefund.php">商户退款 merchant refund</a>
      </li>
      <li>
        <i>8</i> <a href="page/batchTradeClientDemo.php">批量交易接口-申请批量交易令牌 Batch Transaction Interface</a>
      </li>
      <li>
        <i>9</i> <a href="page/batchTradeUploadFileDemo.php">批量交易接口-上传批量文件 Batch Transaction Interface</a>
      </li>
      <li>
        <i>10</i> <a href="page/batchTradeQueryDemo.php">批量交易接口-批量交易状态查询 Batch Transaction Interface</a>
      </li>
      <li>
        <i>11</i> <a href="page/batchTradeDownloadFileDemo.php">批量交易接口-下载（补）回盘文件 Batch Transaction Interface</a>
      </li>
    </ul>
  </body>
</html>