##秒支付Button集成

### 使用前准备
1. BeeCloud[注册](http://beecloud.cn/register/)账号
2. BeeCloud中创建应用，[填写支付渠道所需参数](http://beecloud.cn/doc/payapply)

###激活秒支付button功能
进入APP->设置->秒支付button项：
![支付设置前](http://7xavqo.com1.z0.glb.clouddn.com/spay-button-before.png)

点选支付渠道使能对应支付功能后调整你需要的显示顺序，点击”保存“后会生成appid对应的script标签,目前只提供支付宝PC网页和银联PC网页功能
![支付设置后](http://7xavqo.com1.z0.glb.clouddn.com/spay-button-after.png)

###在支付页面添加代码

~~~
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>demo js button</title>
</head>
<body>
<button id="test">test online</button>


<!--1.添加控制台获得的script标签-->
<script id='spay-script' type='text/javascript' src='https://jspay.beecloud.cn/1/pay/jsbutton/returnscripts?appId=c5d1cba1-5e3f-4ba0-941d-9b0a371fe719'></script>
<script>
    document.getElementById("test").onclick = function() {
        asyncPay();
    };
    function bcPay() {
        /**
         * click调用错误返回：默认行为console.log(err)
         */
        BC.err = function(data) {
            //注册错误信息接受
            alert(data["ERROR"]);
        }
        /**
         * 3. 调用BC.click 接口传递参数
         */
        BC.click({
            "title": "test",
            "amount": "1",
            "out_trade_no": "<?php echo $out_trade_no;?>", //唯一订单号
            "trace_id" : "testcustomer",
            "sign" : "<?php echo $sign;?>",
            /**
             * optional 为自定义参数对象，目前只支持基本类型的key ＝》 value, 不支持嵌套对象；
             * 回调时如果有optional则会传递给webhook地址，webhook的使用请查阅文档
             */
            "optional": {"test": "willreturn"}
        });

    }
    function asyncPay() {
        if (typeof BC == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('beecloud:onready', bcPay, false);
            }else if (document.attachEvent){
                document.attachEvent('beecloud:onready', bcPay);
            }
        }else{
            bcPay();
        }
    }
</script>
</body>
</html>
~~~

样例html中用户点击button后会出现渠道选择项，点选相应button直接进入到支付页面。

传递的参数中sign比较特殊，主要用来保证订单的信息的完整性，需要集成者自行在服务器端生成；

生成规则 : 依次将以下字段（注意是UTF8编码）连接BeeCloud appId、 title、 amount、 out_trade_no、 BeeCloud appSecret, 然后计算连接后的字符串的MD5, 该签名用于验证价格，title 和订单的一致

~~~
PHP示例：
	md5（$appId.$title.$amount.$out_trade_no.$appSecret）;
~~~

###微信jsapi示例

微信内网页支付比较特殊，需要自行获取用户的openid，微信提供了各语言的封装的[函数库(点击查看)](https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=11_1)，以下为php在微信网页内使用秒支付button的示例：

~~~
<?php
include_once('dependency/WxPayPubHelper/WxPayPubHelper.php');
$jsApi = new JsApi_pub();
//网页授权获取用户openid============
//通过code获得openid
$openid = "";
if (!isset($_GET['code'])){
    //触发微信返回code码
    $url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
    Header("Location: $url");
} else {
    //获取code码，以获取openid
    $code = $_GET['code'];
    $jsApi->setCode($code);
    $openid = $jsApi->getOpenId();
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
        asyncPay();
    };
    function bcPay() {
        /**
         * click调用错误返回：默认行为console.log(err)
         */
        BC.err = function(err) {
            //err 为object, 例 ｛”ERROR“ : "xxxx"｝;
            console.log(err);
        }
        BC.click(<?php echo json_encode($data) ?>);
    }
    function asyncPay() {
        if (typeof BC == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('beecloud:onready', bcPay, false);
            }else if (document.attachEvent){
                document.attachEvent('beecloud:onready', bcPay);
            }
        }else{
            bcPay();
        }
    }
</script>
</body>
</html>
~~~


###处理支付结果
各支付渠道通常会提供多种方式获取支付结果：
1. 客户的支付页面在支付成功后跳转到商户指定的的url(秒支付 Button统一包装为return_url参数), 但是此方式受到客户操作影响,可能不成功。
2. 商户向指定渠道查询订单支付状态, 但是各个渠道支持情况不一样，有的有查询接口有的没有。
3. 支付渠道在支付成功后，将相关通知（俗称’回调‘）商户指定的url（BeeCloud统一封装为webhook并且统一支持用户自定义回调参数’optional‘）

建议使用webhook作为处理支付结果方式，使用请参考[webhook指南](https://github.com/beecloud/beecloud-webhook)



