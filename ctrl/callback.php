<?php

/**
 * 模拟用户支付后同步回调页面
 * @author chunkuan <urcn@qq.com>
 */
include('../common/common.php');


/**
 * ****！！！请注意 ！！！****
 * 以下两行代码引入的目的，是为了让您更方便的了解接口返回的数据格式
 * 请在正式项目中屏蔽此行代码即可
 **/
//$postdata = file_get_contents('../data/callback_data.php');
//$post = json_decode($postdata, true);
/**测试数据引入 结束 */



if($post['retCode'] != '0000'){
  echo $post['retMsg']; //支付失败 & 输出支付失败原因
}else{
  $res = decryptReqData($post, $config['pk'], $config['rk']);
  echo "----------以下为支付后同步返回的信息 解签后的结果 ---------<br/>";
  dump($res);
  echo "<div style='color:red;'>请在此处加入您的订单处理代码</div>";
  
}
?>
<p style="margin-top: 30px;"><a href="../">返回首页back to front page</a></p>