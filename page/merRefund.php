<?php
include '../common/common.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>商户退款</title>
    <script src="../js/jquery-1.7.1.js" type="text/javascript"></script>
    <style>
      *{list-style: none;padding:0;margin:0;}
      body{padding: 10px;}
      body table td{line-height:35px;}
    </style>
  </head>
  <body>
    <h4>商户退款</h4>
    <form id="form" action="../ctrl/refund.php" method="post">
		<table>
			<tr>
				<td>协议版本号</td>
				<td><input type="text" name="ver" value="1.0.0" readonly="readonly" /></td>
				<td></td>
			</tr>
			<tr>
				<td>业务类型</td>
				<td><input type="text" name="reqType" value="B0006" readonly="readonly" /></td>
				<td>(不为空)</td>
			</tr>
			<tr>
				<td>商户号</td>
				<td><input type="text" id="merId" name="merId" value="<?php echo $config['mid'];?>" ></td>
				<td>商户标识(不为空，商户必须存在并开通)</td>
			</tr>
			<tr>
				<td>时间戳</td>
				<td><input type="text" name="ts" value="<?php echo date('YmdHis').mt_rand(100000, 999999) ?>" readonly="readonly"></td>
				<td></td>
			</tr>
			<tr>
				<td>商户原订单号</td>
				<td><input type="text" name="merOrderId" value="20150518224132723316"></td>
				<td>（不为空）</td>
			</tr>
			<tr>
				<td>退款订单号</td>
                <td><input type="text" name="seqId" value="224132723316_RE<?php echo time();?>"></td>
				<td>（不为空）</td>
			</tr>
			<tr>
				<td>退款请求提交时间</td>
				<td><input type="text" name="retTime" value="<?php echo date('YmdHis');?>"></td>
				<td>（不为空）</td>
			</tr>
			<tr>
				<td>退款金额</td>
				<td><input type="text" name="retAmt" value="2"></td>
				<td>（不为空）</td>
			</tr>
			<tr>
				<td>退款币种</td>
				<td><input type="text" name="retCny" value="CHF"></td>
				<td>（不为空）</td>
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