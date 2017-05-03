<?php
header("Content-MessageType:text/xml");
include dirname(dirname(__DIR__)) . '/vendor/autoload.php';

$bmwxin = new \Bmwxin\Bmwxin('appId', 'secret');

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

class Reply extends \Bmwxin\AbstractReceive
{
    public function text($xml)
    {
        //TODO
        return $this->formatMessage('text', [
            'toUser' => $xml->ToUserName,
            'fromUser' => $xml->FromUserName,
            'content' => 'Ok'
        ]);

    }
    
    public function image($xml)
    {
        //TODO
    }
    
    public function voice($xml)
    {
        //TODO    
    }
    
    public function shortVideo($xml)
    {   
        //TODO
    }
    
    public function location($xml)
    {
        //TODO
    }
}

$xml = new SimpleXMLElement($string);

echo $bmwxin->registerReceiveMessage($xml, new Reply());