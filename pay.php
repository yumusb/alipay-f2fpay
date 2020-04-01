<?php
/*
 * @Author: yumusb
 * @Date: 2019-08-19 17:14:30
 * @LastEditors: yumusb
 * @LastEditTime: 2019-08-19 17:50:24
 * @Description: 
 */

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exitt();
}

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'f2fpay/model/builder/AlipayTradePrecreateContentBuilder.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'f2fpay/service/AlipayTradeService.php';

$outTradeNo = md5(time() . "91445646fsd6fsdfs4564544"); //生成订单账号。要保证唯一性。可以改用其他更符合自己的算法。
//$totalAmount = round(trim($_POST['amount']), 2); //错误写法 这样并不会对整数以及一位小数后面补0
$totalAmount=sprintf('%.2f',trim($_POST['amount']));//支付宝金额只支持两位小数

if (!is_numeric($totalAmount) || $totalAmount == 0) {
    exitt("订单金额不合法!");
}
$body = htmlentities(trim($_POST['body']));
if (strlen($body) > 20) {
    exitt("备注太长");
}


/*记录订单信息*/
if (NeedTakeNote == "yes") {
    $tmp = $db->prepare("INSERT INTO `{$table}`(`id`, `mark`, `mount`,`status`) VALUES (?,?,?,?)");
    $tmp->execute(array($outTradeNo, $body, $totalAmount, 'nopay'));
}


// 创建请求builder，设置请求参数
$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
$qrPayRequestBuilder->setOutTradeNo($outTradeNo);
$qrPayRequestBuilder->setTotalAmount($totalAmount);
$qrPayRequestBuilder->setSubject($body);
#print_r(get_class_methods($qrPayRequestBuilder));
// 调用qrPay方法获取当面付应答
$qrPay = new AlipayTradeService($alipay_config);
$qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);

if ($qrPayResult->getTradeStatus() === "SUCCESS") {
    $qr = $qrPayResult->getResponse()->qr_code; //SUCCESS 是官方SDK给的结果。如果想看详细的介绍，去找SDK
} else {
    exitt('生成订单失败,错误原因：' . $qrPayResult->getTradeStatus());
}

$form['body'] = $body;
$form['no'] = $outTradeNo;
$form['money'] = $totalAmount;
$form['qr'] = $qr;

$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='./pay/' method='POST'>";

while (list($key, $val) = each($form)) {
    if ($val!=null) {
        //$val = $this->characet($val, $this->postCharset);
        $val = str_replace("'", "&apos;", $val);
        //$val = str_replace("\"","&quot;",$val);
        $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
    }
}

//submit按钮控件请不要含有name属性
$sHtml = $sHtml . "<input type='submit' value='ok' style='display:none;''></form>";

$sHtml = $sHtml . "<script>document.forms['alipaysubmit'].submit();</script>";

exit($sHtml);
