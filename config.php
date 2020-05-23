<?php
/*
 * @Author: yumusb
 * @Date: 2019-08-19 17:35:15
 * @LastEditors: yumusb
 * @LastEditTime: 2019-08-19 17:50:00
 * @Description: 
 */

header("Content-type: text/html; charset=utf-8");
function exitt($a = "错误", $b = "../")
{

	echo "<script>alert('{$a}');window.location.href='{$b}'</script>";
	exit();
}
const NeedTakeNote = 'no';
//需要记录,则改为 yes
//不需要 值为 no
//如果需要记录 需要正确的数据库配置


$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
$url = dirname($http_type . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]) . "/notify.php";

//echo "{$url}?check"; //访问这个输出的url检测回调可用性
$alipay_config = array(
	//签名方式,默认为RSA2(RSA2048)
	'sign_type' => "RSA2",

	//支付宝公钥
	'alipay_public_key' => "",

	//商户私钥
	'merchant_private_key' => "",

	//编码格式
	'charset' => "UTF-8",

	//支付宝网关
	'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

	//应用ID
	'app_id' => "",
	//最大查询重试次数
	'MaxQueryRetry' => "10",
	'notify_url' => $url,

	//查询间隔
	'QueryDuration' => "3"
);

if ($alipay_config['alipay_public_key'] == '' || $alipay_config['merchant_private_key'] == '' || $alipay_config['app_id'] == '') {
	exit("alipay_public_key/merchant_private_key/app_id must not be null");
}
/* 创建订单表。直接复制以下内容，然后选中数据库 执行就可成功创建 
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `f2f_order` (
  `id` varchar(50) NOT NULL COMMENT '订单号',
  `mark` varchar(50) NOT NULL COMMENT '备注',
  `mount` varchar(20) NOT NULL COMMENT '订单金额',
  `notify_time` varchar(20) DEFAULT NULL COMMENT '订单验证时间',
  `trade_no` varchar(30) DEFAULT NULL COMMENT '支付宝订单号',
  `buyer_logon_id` varchar(30) DEFAULT NULL COMMENT '付款账号',
  `status` varchar(10) NOT NULL COMMENT '订单状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `f2f_order` (`id`, `mark`, `mount`, `notify_time`, `trade_no`, `buyer_logon_id`, `status`) VALUES
('933a092fa70f488af8fc5e559ba840f6', '好好学习', '0.01', NULL, NULL, NULL, 'nopay');


ALTER TABLE `f2f_order`
  ADD PRIMARY KEY (`id`);
COMMIT;


*/
// $database=array(
// 	'dbname'=>'f2f',
// 	'host'=>'localhost',
// 	'port'=>3306,
// 	'user'=>'root',
// 	'pass'=>'root',
// );
//数据库配置信息。
if (NeedTakeNote == "yes") {
	$database = array(
		'dbname' => 'f2f',
		'host' => 'localhost',
		'port' => 3306,
		'user' => 'root',
		'pass' => 'root',
	);

	try {
		$db = new PDO("mysql:dbname=" . $database['dbname'] . ";host=" . $database['host'] . ";" . "port=" . $database['port'] . ";", $database['user'], $database['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"));
	} catch (PDOException $e) {
		die("数据库出错，请检查 config.php中的database配置项.<br> " . $e->getMessage() . "<br/>");
	}

	$table = 'f2f_order'; //表名字
}
