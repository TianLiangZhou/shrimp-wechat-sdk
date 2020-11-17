<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 11:42
 */

declare(strict_types=1);

namespace Shrimp\Response;

class NewsResponse extends Response
{
    public function __toString()
    {
        // TODO: Implement __toString() method.
        if (!is_array($this->content)) {
            return "success";
        }
        $items = [];
        if (!isset($this->content[0])) {
            $items[0] = $this->content;
        } else {
            $items = $this->content;
        }
        $count = count($items);
        $item = [];
        foreach ($items as $value) {
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
    <ToUserName><![CDATA[{$this->source->FromUserName}]]></ToUserName>
    <FromUserName><![CDATA[{$this->source->ToUserName}]]></FromUserName>
    <CreateTime>{$this->currentTime}</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount>{$count}</ArticleCount>
    <Articles>
    {$items}
    </Articles>
</xml>
EOF;
    }
}
