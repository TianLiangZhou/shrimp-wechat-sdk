<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 11:42
 */

namespace Bmwxin\Response;


class NewsResponse extends AbstractResponse implements ResponseInterface
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
        if (!is_array($this->content) || !isset($this->content[0])) {
            return "";
        }
        $count = count($this->content);
        $item = [];
        foreach ($this->content as $value) {
            $item[] = <<<EOF
<item>
    <Title><![CDATA[{$value['title']}]]></Title> 
    <Description><![CDATA[{$value['description']}]]></Description>
    <PicUrl><![CDATA[{$value['pic_url']}]]></PicUrl>
    <Url><![CDATA[{$value['url']}]]></Url>
</item>
EOF;
        }
        $items = implode('', $item);
        return <<<EOF
<xml>
    <ToUserName><![CDATA[{$this->package->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->package->ToUserName}]]></FromUserName>
    <CreateTime>{$this->requestTime}</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount>{$count}</ArticleCount>
    <Articles>
    {$items}
    </Articles>
</xml>
EOF;

    }
}