<?php
header("Content-MessageType:text/xml");
include dirname(dirname(__DIR__)) . '/vendor/autoload.php';


$string = <<<END
<xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName>
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[this is a test]]></Content>
 <MsgId>1234567890123456</MsgId>
 </xml>
END;

class TextSubscriber implements \Bmwxin\Message\MessageSubscriberInterface
{
    public function onMessageText(\Bmwxin\Response $response, $package)
    {
        $textResponse = new \Bmwxin\Response\TextResponse($package);
        $textResponse->setContent("aaaa");
        $response->setContent($textResponse);
    }

    public function getSubscriberType()
    {
        // TODO: Implement getSubscriberType() method.
        return [
            \Bmwxin\Message\MessageType::TEXT => ['onMessageText', 100]
        ];
    }
}

$message = new \Bmwxin\MessageDispatcher(new SimpleXMLElement($string));
$message->addSubscribers(new TextSubscriber());
echo $message->dispatch();