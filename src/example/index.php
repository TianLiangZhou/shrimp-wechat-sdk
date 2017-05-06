<?php
header("Content-MessageType:text/xml");
include dirname(dirname(__DIR__)) . '/vendor/autoload.php';


$string = <<<END
<xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName>
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[event]]></MsgType>
 <Event><![CDATA[subscribe]]></Event>
 <MsgId>1234567890123456</MsgId>
 </xml>
END;

class TextPlugin implements \Bmwxin\Response\ResponsePluginInterface
{

    public function getResponse($package): \Bmwxin\Response\ResponseInterface
    {
        // TODO: Implement getResponse() method.
        return (new \Bmwxin\Response\TextResponse($package))->setContent("Hello world");
    }

    public function type(): string
    {
        // TODO: Implement type() method.
        return \Bmwxin\Message\MessageType::TEXT;
    }

    public function name(): string
    {
        // TODO: Implement name() method.
        return "";
    }
}
class EventSubPlugin implements \Bmwxin\Response\ResponsePluginInterface
{

    public function getResponse($package): \Bmwxin\Response\ResponseInterface
    {
        // TODO: Implement getResponse() method.
        return (new \Bmwxin\Response\TextResponse($package))->setContent("Hello world");
    }

    public function type(): string
    {
        // TODO: Implement type() method.
        return \Bmwxin\Message\MessageType::EVENT;
    }

    public function name(): string
    {
        // TODO: Implement name() method.
        return \Bmwxin\Message\MessageType::SUBSCRIBE;
    }
}


$message = new \Bmwxin\MessageDispatcher(new SimpleXMLElement($string));

$message->addPlugin(new TextPlugin());
$message->addPlugin(new EventSubPlugin());

echo $message->dispatch();