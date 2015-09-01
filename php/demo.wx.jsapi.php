<?php
include_once('dependency/WxPayPubHelper/WxPayPubHelper.php');

$jsApi = new JsApi_pub();
//网页授权获取用户openid============
//通过code获得openid
$openid = "";
try {
    if (!isset($_GET['code'])){
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
    "appId" =>  "c5d1cba1-5e3f-4ba0-941d-9b0a371fe719",
    "title" => "test",
    "amount" => "1",
    "out_trade_no" => "test".time(),
    "openid" => $openid,
    "trace_id" => "testcustomer"
);

$appSecret = "39a7a518-9ac8-4a9e-87bc-7885f33cf18c";
$sign = md5($data['appId'].$data['title'].$data['amount'].$data['out_trade_no'].$appSecret);
$data["sign"] = $sign;
$data["optional"] = json_decode(json_encode(array("hello" => "1")));
//    $data["openid"] ="o3kKrjlUsMnv__cK5DYZMl0JoAkY";   //o3kKrjlUsMnv__cK5DYZMl0JoAkY   oOCyauJ6nKcXiIIQ_bixiQpaL6PQ(me)
?>
<div><?php echo json_encode($data) ?></div>
<script>
    document.getElementById("test").onclick = function() {
        /**
         * click调用错误返回：默认行为console.log(err)
         */
        BC.err = function(err) {
            //err 为object, 例 ｛”ERROR“ : "xxxx"｝;
            console.log(err);
        }
        BC.click(<?php echo json_encode($data) ?>);
    };

</script>
</body>
</html>
