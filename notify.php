<?php
/*
 * @Author: yumusb
 * @Date: 2019-08-19 17:50:15
 * @LastEditors: yumusb
 * @LastEditTime: 2019-08-19 17:50:18
 * @Description: 
 */

error_reporting(0);
require_once './f2fpay/model/builder/AopClient.php';
require_once './config.php';
if (isset($_GET['check'])) {
	exit("notify is OK");
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	exit();
}

$aop = new AopClient;
$aop->alipayrsaPublicKey = $alipay_config['alipay_public_key'];
$flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");

if ($flag) {
	echo 'success'; //接口必须返回success 不然阿里会一直发送校验验证。
	//异步SIGN验证成功， 可以进行下一步动作。例如验证订单金额 然后完成订单。之类的。。
	//需要验证的就是 订单号 与 订单金额是否一致，验证成功 就可以对数据库中的订单进行操作了。
	//TRADE_SUCCESS 对于当面付来说，已经到账了。详情可以看这里 https://www.cnblogs.com/tdalcn/p/5956690.html
	if ($_POST['trade_status'] === "TRADE_SUCCESS") {
		//订单处理模板

		if (NeedTakeNote == "yes") {
			$no = $_POST['out_trade_no'];
			$mount = $_POST['total_amount'];
			$trade_no  = $_POST['trade_no'];
			$notify_time = $_POST['notify_time'];
			$buyer_logon_id = $_POST['buyer_logon_id'];
			$tmp = $db->query("SELECT `status` FROM `{$table}` WHERE `id`= '{$no}' and `mount` = '{$mount}'");

			$res = $tmp->fetch(PDO::FETCH_ASSOC)['status'];

			if ($res === "nopay") {
				$db->exec("UPDATE `{$table}` SET `notify_time`='{$notify_time}',`trade_no`='{$trade_no}',`buyer_logon_id`='{$buyer_logon_id}',`status`='success' WHERE `id`='{$no}' and `mount`='{$mount}'");
			}
		}
	}
} else {
	echo 'error';
}
