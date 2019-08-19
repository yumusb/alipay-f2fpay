<?php
require_once("../config.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exitt();
}

$body = htmlentities(trim($_POST['body']));
$no = htmlentities(trim($_POST['no']));
$money = htmlentities(trim($_POST['money']));
$qr = htmlentities(trim($_POST['qr']));

?>

<html>

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $body; ?>_支付宝支付</title>
    <link rel='stylesheet' href='../static/erphpdown.css' type='text/css' media='all' />
</head>

<body>
    <div class="wppay-custom-modal-box mobantu-wppay">
        <section class="wppay-modal">

            <section class="erphp-wppay-qrcode mobantu-wppay wppay-net">
                <section class="tab">
                    <a href="javascript:;" class="active">
                        <div class="payment"><img src="../static/payment-alipay.png"></div>扫一扫支付 <span class="price"><?php echo $money ?></span> 元
                    </a>
                </section>
                <section class="tab-list" style="background-color: #00a3ee !important;">
                    <section class="item">
                        <section class="qr-code">
                            <img src="<?php echo './qrcode.php?data=' . urlencode($qr); ?>" class="img" alt="">
                        </section>
                        <p class="account">支付完成后请等待5秒左右，期间请勿关闭此页面</p>
                        <p class="desc">手机端可&nbsp;<a href="alipayqr://platformapi/startapp?saId=10000007&clientVersion=3.7.0.0718&qrcode=<?php echo urlencode($qr); ?>">点击打开支付宝付款</a></p>
                    </section>
                </section>
            </section>

        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/combine/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script>
        setOrder = setInterval(function() {
            $.ajax({
                type: 'post',
                url: '../query.php',
                data: {
                    no: '<?php echo $no; ?>',
                    t: Math.random()
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status.toLowerCase() == "success") {
                        alert("支付成功!");
                        location.href = "../";
                    }

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });

        }, 5000);
    </script>
</body>

</html>