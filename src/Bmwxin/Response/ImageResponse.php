<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 11:22
 */

namespace Bmwxin\Response;


class ImageResponse extends AbstractResponse implements ResponseInterface
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
    <ToUserName><![CDATA[{$this->package->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->package->ToUserName}]]></FromUserName>
    <CreateTime>{$this->requestTime}</CreateTime>
    <MsgType><![CDATA[image]]></MsgType>
    <Image>
        <MediaId><![CDATA[{$this->content}]]></MediaId>
    </Image>
</xml>
EOF;

    }
}