<?php
header("Content-Type:text/xml");
include dirname(dirname(__DIR__)) . '/vendor/autoload.php';


$string = <<<END
<xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName>
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[nihao]]></Content>
 <MsgId>1234567890123456</MsgId>
 </xml>
END;

$sdk  = new \Shrimp\ShrimpWechat('wx983dd48be764e9ce', '26b8ccf343bddeecd0402e1b864d2dd4');


$sdk->bind(function (\Shrimp\GetResponseEvent $response) {
    $response->setResponse("Hello world");
});

echo $sdk->send();

//print
/**
<xml>
    <ToUserName><![CDATA[fromUser]]></ToUserName>
    <FromUserName><![CDATA[toUser]]></FromUserName>
    <CreateTime>1515134824</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[Hello world]]></Content>
</xml>
*/
