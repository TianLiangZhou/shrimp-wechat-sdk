<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 11:25
 */

declare(strict_types=1);

namespace Shrimp\Response;

class VoiceResponse extends Response
{
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return <<<EOF
<xml>
    <ToUserName><![CDATA[{$this->source->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->source->ToUserName}]]></FromUserName>
    <CreateTime>{$this->currentTime}</CreateTime>
    <MsgType><![CDATA[voice]]></MsgType>
    <Voice>
    <MediaId><![CDATA[{$this->content}]]></MediaId>
    </Voice>
</xml>
EOF;
    }
}
