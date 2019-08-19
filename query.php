<?php
/*
 * @Author: yumusb
 * @Date: 2019-08-19 17:49:27
 * @LastEditors: yumusb
 * @LastEditTime: 2019-08-19 17:50:33
 * @Description: 
 */

header("Content-type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit();
}
require_once './f2fpay/service/AlipayTradeService.php';

$out_trade_no = trim($_POST['no']);


/*记录订单信息*/
if (NeedTakeNote == "yes") {
    //通过查询本地订单 
    $tmp = $db->prepare("SELECT `status` FROM `{$table}` where `id` = ? limit 10");
    //先prepare一下我们需要执行的SQL语句，其中需要安全处理的参数是以`?`占位的
    $tmp->execute(array($out_trade_no));
    //执行prepare的execute方法,并把参数以数组方式传入
    $res = $tmp->fetch(PDO::FETCH_ASSOC);
    exit(json_encode($res));
} elseif (NeedTakeNote == "no") {
    $queryContentBuilder = new AlipayTradeQueryContentBuilder();
    $queryContentBuilder->setOutTradeNo($out_trade_no);
    $queryResponse = new AlipayTradeService($alipay_config);
    $queryResult = $queryResponse->queryTradeResult($queryContentBuilder);
    $res['status'] = $queryResult->getTradeStatus();
    $res['buyer'] = $queryResult->getResponse()->buyer_logon_id;
    $res['amount'] = $queryResult->getResponse()->buyer_pay_amount;

    exit(json_encode($res));
}
