<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 11:22
 */

namespace Shrimp\Response;


class ImageResponse extends Response
{
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return <<<EOF
<xml>
    <ToUserName><![CDATA[{$this->source->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->source->ToUserName}]]></FromUserName>
    <CreateTime>{$this->currentTime}</CreateTime>
    <MsgType><![CDATA[image]]></MsgType>
    <Image>
        <MediaId><![CDATA[{$this->content}]]></MediaId>
    </Image>
</xml>
EOF;

    }
}