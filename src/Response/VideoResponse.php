<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 11:28
 */

declare(strict_types=1);

namespace Shrimp\Response;

class VideoResponse extends Response
{
    public function __toString()
    {
        // TODO: Implement __toString() method.
        $mediaId = $this->content;
        $title = $description = '';
        if (is_array($this->content)) {
            $mediaId = isset($this->content['mediaId'])
                        ? $this->content['mediaId']
                        : $this->content[0];
            $title = isset($this->content['title']) ? $this->content['title'] : '';
            $description = isset($this->content['description']) ? $this->content['description'] : '';
        }
        return <<<EOF
<xml>
    <ToUserName><![CDATA[{$this->source->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->source->ToUserName}]]></FromUserName>
    <CreateTime>{$this->currentTime}</CreateTime>
    <MsgType><![CDATA[video]]></MsgType>
    <Video>
    <MediaId><![CDATA[{$mediaId}]]></MediaId>
    <Title><![CDATA[{$title}]]></Title>
    <Description><![CDATA[{$description}]]></Description>
    </Video> 
</xml>
EOF;
    }
}
