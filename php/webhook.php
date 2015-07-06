<?php

$jsonStr = file_get_contents("php://input");

$webhookObj = json_decode($jsonStr);


// webhook字段文档: http://beecloud.cn/doc/php.php#webhook

var_dump($webhookObj);
