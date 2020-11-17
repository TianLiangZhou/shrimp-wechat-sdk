<?php

use Shrimp\Event\ResponseEvent;
use Shrimp\ShrimpWechat;

header("Content-Type:text/xml");

include __DIR__ . '/../vendor/autoload.php';

$sdk  = new ShrimpWechat('wxed1cc1b0e241ff74', '434ca4dfc791853b9ef36ebf24a3ce02');


$sdk->bind(function (ResponseEvent $response) {
    $response->setResponse("Hello world");
});

echo $sdk->send();

/**
<xml>
    <ToUserName><![CDATA[fromUser]]></ToUserName>
    <FromUserName><![CDATA[toUser]]></FromUserName>
    <CreateTime>1515134824</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[Hello world]]></Content>
</xml>
*/
