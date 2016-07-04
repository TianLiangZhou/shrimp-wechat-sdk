<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2016/7/4
 * Time: 16:09
 */

namespace Bmwxin;

/**
 * Interface ReceiveInterface
 * @package Bmwxin
 */

abstract class AbstractReceive
{
    /**
     * @param $xml
     * @return mixed
     */
    abstract public function text($xml);

    /**
     * @param $xml
     * @return mixed
     */
    abstract public function image($xml);

    /**
     * @param $xml
     * @return mixed
     */
    abstract public function voice($xml);

    /**
     * @param $xml
     * @return mixed
     */
    abstract public function shortVideo($xml);

    /**
     * @param $xml
     * @return mixed
     */
    abstract public function location($xml);

    /**
     * @param $messageType
     * @param array $xmlArary
     * @return string
     */
    protected function formatMessage($messageType, array $xmlArary)
    {
        $time = time();
        $message = <<<EOF
<xml>
<ToUserName><![CDATA[{$xmlArary['toUser']}]]></ToUserName>
<FromUserName><![CDATA[{$xmlArary['fromUser']}]]></FromUserName>
<CreateTime>{$time}</CreateTime>
<MsgType><![CDATA[{$messageType}]]></MsgType>
EOF;

        switch ($messageType) {
            case 'text':
                    $message .=<<<EOF
<Content><![CDATA[{$xmlArary['content']}]]></Content>
EOF;
                break;
            case 'image':
                    $message .=<<<EOF
<Image>
<MediaId><![CDATA[media_id]]></MediaId>
</Image>
EOF;

                break;
            case 'voice':
                    $message .=<<<EOF
<Voice>
<MediaId><![CDATA[media_id]]></MediaId>
</Voice>
EOF;
                break;
            case 'music':
                    $message .=<<<EOF
<Music>
<Title><![CDATA[TITLE]]></Title>
<Description><![CDATA[DESCRIPTION]]></Description>
<MusicUrl><![CDATA[MUSIC_Url]]></MusicUrl>
<HQMusicUrl><![CDATA[HQ_MUSIC_Url]]></HQMusicUrl>
<ThumbMediaId><![CDATA[media_id]]></ThumbMediaId>
</Music>
EOF;
                break;
            case 'news':
                     $message .=<<<EOF
<ArticleCount>2</ArticleCount>
<Articles>
<item>
<Title><![CDATA[title1]]></Title> 
<Description><![CDATA[description1]]></Description>
<PicUrl><![CDATA[picurl]]></PicUrl>
<Url><![CDATA[url]]></Url>
</item>
<item>
<Title><![CDATA[title]]></Title>
<Description><![CDATA[description]]></Description>
<PicUrl><![CDATA[picurl]]></PicUrl>
<Url><![CDATA[url]]></Url>
</item>
</Articles>
EOF;
                break;
        }
        $message .= <<<EOF
</xml>
EOF;
        return $message;
    }
}