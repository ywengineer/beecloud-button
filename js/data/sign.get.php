<?php
/**
 * 获取out_trade_no 对应订单参数，生成支付参数，需要预防XSS攻击
 * 此api使用任意server语言实现即可
 */

$appId = "c5d1cba1-5e3f-4ba0-941d-9b0a371fe719";
$appSecret = "39a7a518-9ac8-4a9e-87bc-7885f33cf18c";

/**
 * 以下直接构造数据，忽略了数据库查询操作
 */
$data = array(
    "title" => "test",
    "amount" => "1",
    "out_trade_no" => "test0001", //支付订单的id，需要唯一
    "trace_id" => "testcustomer"
);
//计算sign只需要appId， title， amount ，out_trade_no 和appSecret
$sign = md5($appId.$data['title'].$data['amount'].$data['out_trade_no'].$appSecret);
$data["sign"] = $sign;
/**
 * optional 为自定义参数对象，目前只支持基本类型的key ＝》 value, 不支持嵌套对象；
 * 回调时如果有optional则会传递给webhook地址，webhook的使用请查阅文档
 */
$data["optional"] = json_decode(json_encode(array("hello" => "1")));

print json_encode($data);