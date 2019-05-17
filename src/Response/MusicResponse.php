<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 11:35
 */

namespace Shrimp\Response;

class MusicResponse extends Response
{
    public function __toString()
    {
        // TODO: Implement __toString() method.
        $mediaId = $this->content;
        $title = $description = $url = $hqUrl = '';
        if (is_array($this->content)) {
            $mediaId = isset($this->content['mediaId'])
                        ? $this->content['mediaId']
                        : $this->content[0];
            $title = isset($this->content['title']) ? $this->content['title'] : '';
            $description = isset($this->content['description']) ? $this->content['description'] : '';
            $url = isset($this->content['url']) ? $this->content['url'] : '';
            $hqUrl = isset($this->content['hq_url']) ? $this->content['hq_url'] : '';
        }
        return <<<EOF
<xml>
    <ToUserName><![CDATA[{$this->source->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->source->ToUserName}]]></FromUserName>
    <CreateTime>{$this->currentTime}</CreateTime>
    <MsgType><![CDATA[music]]></MsgType>
    <Music>
        <Title><![CDATA[{$title}]]></Title>
        <Description><![CDATA[{$description}]]></Description>
        <MusicUrl><![CDATA[{$url}]]></MusicUrl>
        <HQMusicUrl><![CDATA[{$hqUrl}]]></HQMusicUrl>
        <ThumbMediaId><![CDATA[{$mediaId}]]></ThumbMediaId>
    </Music>
</xml>
EOF;
    }
}
