<?php
//error_reporting(E_ERROR);
require_once 'qrcode.class.php';
$url = urldecode($_GET["data"]);
QRcode::png($url);
