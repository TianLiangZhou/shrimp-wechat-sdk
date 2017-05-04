<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 8:39
 */

namespace Bmwxin\Response;


class TextResponse extends AbstractResponse implements ResponseInterface
{
    public function setContent($content)
    {
        // TODO: Implement setContent() method.
        $this->content = $content;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return <<<EOF
<xml>
    <ToUserName><![CDATA[{$this->package->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->package->ToUserName}]]></FromUserName>
    <CreateTime>{$_SERVER['REQUEST_TIME']}</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[{$this->content}]]></Content>
</xml>
EOF;
    }
}