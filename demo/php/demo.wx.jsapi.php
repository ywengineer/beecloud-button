<?php
include_once('dependency/WxPayPubHelper/WxPayPubHelper.php');

$jsApi = new JsApi_pub();
//网页授权获取用户openid
//通过code获得openid
$openid = "";
try {
    if (!isset($_GET['code'])) {
        //触发微信返回code码
        $url = $jsApi->createOauthUrlForCode("你的微信网页地址");
        Header("Location: $url");
    } else {
        //获取code码，以获取openid
        $code = $_GET['code'];
        $jsApi->setCode($code);
        $openid = $jsApi->getOpenId();
    }
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="telephone=no" name="format-detection">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>demo js pay</title>
    <link rel="stylesheet" href="../src/css/api.css" >
</head>
<body>
<button id="test" style="position: absolute; left: 10px; top: 10px; z-index: 99999;">test</button>
<div id="qr"></div>
<script id='spay-script' type='text/javascript' src='https://jspay.beecloud.cn/1/pay/jsbutton/returnscripts?appId=c5d1cba1-5e3f-4ba0-941d-9b0a371fe719'></script>
<?php
$data = array(
    "app_id" =>  "c5d1cba1-5e3f-4ba0-941d-9b0a371fe719",
    "title" => "test",
    "amount" => "1",
    "out_trade_no" => "test".time(),
    "openid" => $openid,
    "trace_id" => "testcustomer"
);

$app_secret = "39a7a518-9ac8-4a9e-87bc-7885f33cf18c";
$sign = md5($data['app_id'] . $data['title'] . $data['amount'] . $data['out_trade_no'] . $app_secret);
$data["sign"] = $sign;
$data["optional"] = json_decode(json_encode(array("hello" => "1")));
//    $data["openid"] ="o3kKrjlUsMnv__cK5DYZMl0JoAkY";   //o3kKrjlUsMnv__cK5DYZMl0JoAkY   oOCyauJ6nKcXiIIQ_bixiQpaL6PQ(me)
?>

<div><?php echo json_encode($data) ?></div>
<script>
    document.getElementById("test").onclick = function() {
        asyncPay();
    };

    function bcPay() {
        BC.click(<?php echo json_encode($data) ?>, {
            wxJsapiFinish : function(res) {
                //jsapi接口调用完成后
                alert(JSON.stringify(res));
            }
        });
    }
    
    // 这里不直接调用BC.click的原因是防止用户点击过快，BC的JS还没加载完成就点击了支付按钮。
    // 实际使用过程中，如果用户不可能在页面加载过程中立刻点击支付按钮，就没有必要利用asyncPay的方式，而是可以直接调用BC.click。
    function asyncPay() {
        if (typeof BC == "undefined") {
            if (document.addEventListener) { // 大部分浏览器
                document.addEventListener('beecloud:onready', bcPay, false);
            } else if (document.attachEvent) { // 兼容IE 11之前的版本
                document.attachEvent('beecloud:onready', bcPay);
            }
        } else {
            bcPay();
        }
    }
</script>
</body>
</html>
