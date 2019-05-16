<?php

use Shrimp\Event\ResponseEvent;
use Shrimp\ShrimpWechat;

header("Content-Type:text/xml");

include dirname(dirname(__DIR__)) . '/vendor/autoload.php';

$sdk  = new ShrimpWechat('wx983dd48be764e9ce', '26b8ccf343bddeecd0402e1b864d2dd4');


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
