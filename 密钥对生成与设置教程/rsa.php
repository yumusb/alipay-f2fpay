<?php

/**
 * @Author: yumu
 * @Date:   2019-08-29
 * @Email:   yumusb@foxmail.com
 * @Last Modified by:   yumu
 * @Last Modified time: 2019-11-12
 */
$config = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);
    
// Create the private and public key
$res = openssl_pkey_new($config);

// Extract the private key from $res to $privKey
openssl_pkey_export($res, $privKey);
// Extract the public key from $res to $pubKey
$pubKey = openssl_pkey_get_details($res);
$pubKey = ($pubKey["key"]);

?>
<!DOCTYPE html>
<html>
<head>
	<title>密钥对生成</title>
	<meta charset="utf-8">
</head>
<body style="text-align: center;">
<h2>公钥用法:把公钥填入支付宝商户后台，支付宝会返回一个“支付宝公钥”,然后把“支付宝公钥”填入config.php<br>私钥用法:直接把私钥填入插件后台对应处<br>注意:每次刷新会改变,请自行留存备份</h2>
<h3>以下为公钥</h3>
<textarea rows="7" cols="63">
<?php echo trim(str_replace("\n","",str_replace("-----END PUBLIC KEY-----","",str_replace("-----BEGIN PUBLIC KEY-----", "", $pubKey))));?>
</textarea>
<br>
<h3>以下为私钥</h3>
<textarea rows="26" cols="63">
<?php echo trim(str_replace("\n","",str_replace("-----BEGIN PRIVATE KEY-----","",str_replace("-----END PRIVATE KEY-----", "", $privKey))));?>
</textarea>


</textarea>
</body>
</html>