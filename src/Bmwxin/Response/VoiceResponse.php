<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 11:25
 */

namespace Bmwxin\Response;


class VoiceResponse extends AbstractResponse implements ResponseInterface
{

    public function setContent($content)
    {
        // TODO: Implement setContent() method.
        $this->content = $content;
        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return <<<EOF
<xml>
    <ToUserName><![CDATA[{$this->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->ToUserName}]]></FromUserName>
    <CreateTime>{$this->requestTime}</CreateTime>
    <MsgType><![CDATA[voice]]></MsgType>
    <Voice>
    <MediaId><![CDATA[{$this->content}]]></MediaId>
    </Voice>
</xml>
EOF;

    }
}