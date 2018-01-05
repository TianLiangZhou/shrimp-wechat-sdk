<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 8:39
 */

namespace Shrimp\Response;


class TextResponse extends Response
{
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return <<<EOF
<xml>
    <ToUserName><![CDATA[{$this->source->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->source->ToUserName}]]></FromUserName>
    <CreateTime>{$this->currentTime}</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[{$this->content}]]></Content>
</xml>
EOF;
    }
}