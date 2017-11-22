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

class TextPlugin implements \Shrimp\Response\ResponsePluginInterface
{

    public function getResponse($package)
    {
        // TODO: Implement getResponse() method.
        return (new \Shrimp\Response\TextResponse($package))->setContent("Hello world");
    }

    public function type()
    {
        // TODO: Implement type() method.
        return \Shrimp\Message\MessageType::TEXT;
    }

    public function name()
    {
        // TODO: Implement name() method.
        return "";
    }
}
class EventSubPlugin implements \Shrimp\Response\ResponsePluginInterface
{

    public function getResponse($package)
    {
        // TODO: Implement getResponse() method.
        return (new \Shrimp\Response\TextResponse($package))->setContent("谢谢你的关注");
    }

    public function type()
    {
        // TODO: Implement type() method.
        return \Shrimp\Message\MessageType::EVENT;
    }

    public function name()
    {
        // TODO: Implement name() method.
        return \Shrimp\Message\MessageType::SUBSCRIBE;
    }
}


$message = new \Shrimp\MessageDispatcher(new SimpleXMLElement($string));

$message->addPlugin(new TextPlugin());
$message->addPlugin(new EventSubPlugin());

echo $message->dispatch();