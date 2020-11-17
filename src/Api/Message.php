<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/23
 * Time: 18:19
 */

declare(strict_types=1);

namespace Shrimp\Api;

/**
 * 消息接口
 *
 * Class Message
 * @package Shrimp\Api
 */
class Message extends Base
{
    const TYPE_MPNEWS = 'mpnews';
    const TYPE_TEXT = 'text';
    const TYPE_VOICE = 'voice';
    const TYPE_IMAGES = 'images';
    const TYPE_MPVIDEO = 'mpvideo';
    const TYPE_CARD  = 'wxcard';

    const TYPE_VIDEO = 'video';
    const TYPE_IMAGE = 'image';
    const TYPE_MUSIC = 'music';
    const TYPE_NEWS = 'news';
    const TYPE_MENU = 'msgmenu';

    const TYPE_DATA = [
        self::TYPE_TEXT => [
            'content' => '',
        ],
        self::TYPE_IMAGE => [
            'media_id' => ''
        ],
        self::TYPE_IMAGES => [
            "media_ids" => [],
            "recommend" => '',
            "need_open_comment" => 1,
            "only_fans_can_comment" => 0
        ],
        self::TYPE_VOICE => [
            'media_id' => ''
        ],
        self::TYPE_MPVIDEO => [
            "media_id" => '',
            "title" => '',
            "description" => '',
        ],
        self::TYPE_VIDEO => [
            "media_id" => '',
            "thumb_media_id" => '',
            "title" => '',
            "description" => '',
        ],
        self::TYPE_MPNEWS => [
            'media_id' => ''
        ],
        self::TYPE_CARD  => [
            'card_id' => '',
         ],
        self::TYPE_MUSIC => [
            "media_id" => '',
            "thumb_media_id" => '',
            "title" => '',
            "description" => '',
        ],
        self::TYPE_NEWS => [
            "articles" => [
                [
                    "title" => "",
                    "description" => "",
                    "url" => "",
                    "picurl" => "",
                ]
            ]
        ],
        self::TYPE_MENU => [
            "head_content" => '',
            "list" => [
                [
                    "id" => '',
                    "content" => '',
                ]
            ],
            "tail_content" => '',
        ],
    ];


    /**
     * 根据tag群发消息
     *
     * @param string $type
     * @param array $data
     * @param int $tagId
     * @return array
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Exception
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Batch_Sends_and_Originality_Checks.html
     */
    public function massSendAll(string $type, array $data, $tagId = 0)
    {
        if (!isset(self::TYPE_DATA[$type])) {
            throw new \Exception("错误的消息类型");
        }
        $data = [
            'filter' => [
                'is_to_all' => $tagId ? false : true,
                'tag_id'    => $tagId,
            ],
            'msgtype' => $type === self::TYPE_IMAGES ? self::TYPE_IMAGE : $type,
            $type => array_merge(self::TYPE_DATA[$type], $data)
        ];
        $uri = $this->format('cgi-bin/message/mass/sendall');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => $data])
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 通过openId群发
     *
     * @param array $openId
     * @param array $data
     * @param string $type
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Exception
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Batch_Sends_and_Originality_Checks.html
     */
    public function massSendOpenId(string $type, array $data, array $openId)
    {
        if (!isset(self::TYPE_DATA[$type])) {
            throw new \Exception("错误的消息类型");
        }
        $data = [
            'touser' => $openId,
            'msgtype' => $type === self::TYPE_IMAGES ? self::TYPE_IMAGE : $type,
            $type => array_merge(self::TYPE_DATA[$type], $data)
        ];
        $uri = $this->format('cgi-bin/message/mass/send');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => $data])
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 获取群发信息状态
     *
     * @param $mid
     * @return array|mixed
     * @throws \Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Batch_Sends_and_Originality_Checks.html
     */
    public function massGet(int $mid)
    {
        $uri = $this->format('cgi-bin/message/mass/get');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => ['msg_id' => $mid]])
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 删除群发消息
     *
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Batch_Sends_and_Originality_Checks.html#4
     * @param $mid
     * @param int $aid 要删除的文章在图文消息中的位置，第一篇编号为1，该字段不填或填0会删除全部文章
     * @return array|mixed
     * @throws \Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function massDelete(int $mid, int $aid = 0)
    {
        $uri = $this->format('cgi-bin/message/mass/delete');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => ['msg_id' => $mid, 'article_idx' => $aid]])
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }


    /**
     * 预览群发消息
     *
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Batch_Sends_and_Originality_Checks.html#4
     * @param string $openId
     * @param array $data
     * @param string $type
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function massPreview(string $type, array $data, string $openId)
    {
        $uri = $this->format('cgi-bin/message/mass/preview');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => [
                    'touser' => $openId,
                    'msgtype' => $type === self::TYPE_IMAGES ? self::TYPE_IMAGE : $type,
                    $type => array_merge(self::TYPE_DATA[$type], $data)
                ]])
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }
    /**
     * 设置群发速度
     *
     * @param $speed
     * @return array|mixed
     * @throws \Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Batch_Sends_and_Originality_Checks.html
     */
    public function massSpeedSet(int $speed = 2)
    {
        $uri = $this->format('cgi-bin/message/mass/speed/set');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => ['speed' => $speed]])
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 获取群发速度
     *
     * @return array|mixed
     * @throws \Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Batch_Sends_and_Originality_Checks.html
     */
    public function massSpeedGet()
    {
        $uri = $this->format('cgi-bin/message/mass/speed/get');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST')
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }
}
