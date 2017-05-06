<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 11:35
 */

namespace Bmwxin\Response;


class MusicResponse extends AbstractResponse implements ResponseInterface
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
    <ToUserName><![CDATA[{$this->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->ToUserName}]]></FromUserName>
    <CreateTime>{$this->requestTime}</CreateTime>
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