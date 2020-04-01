<?php
//error_reporting(E_ERROR);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'qrcode.class.php';
$url = urldecode($_GET["data"]);
QRcode::png($url);
