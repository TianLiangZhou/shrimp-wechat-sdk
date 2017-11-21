<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 11:28
 */

namespace Shrimp\Response;


class VideoResponse extends AbstractResponse implements ResponseInterface
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
    <ToUserName><![CDATA[{$this->package->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->package->ToUserName}]]></FromUserName>
    <CreateTime>{$this->requestTime}</CreateTime>
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