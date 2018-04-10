<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/23
 * Time: 18:19
 */

namespace Shrimp\Api;

/**
 * 消息接口
 *
 * Class Message
 * @package Shrimp\Api
 */
class Message extends Base
{
    const TYPE_NEWS = 'mpnews';
    const TYPE_TEXT = 'text';
    const TYPE_VOICE = 'voice';
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'mpvideo';
    const TYPE_CARD  = 'wxcard';

    const MESSAGE_TYPE = [
        self::TYPE_NEWS => 'media_id',
        self::TYPE_TEXT => 'content',
        self::TYPE_VOICE => 'media_id',
        self::TYPE_IMAGE => 'media_id',
        self::TYPE_VIDEO => 'media_id',
        self::TYPE_CARD  => 'card_id',
    ];


    /**
     * 根据tag群发消息
     * @param $content
     * @param $type
     * @param int $tagId
     * @return array|mixed
     * @throws \Exception
     */
    public function massForTag($content, $type, $tagId = 0)
    {
        if (!isset(self::MESSAGE_TYPE[$type])) {
            throw new \Exception("错误的消息类型");
        }
        $json = [
            'filter' => [
                'is_to_all' => $tagId ? false : true,
                'tag_id'    => $tagId,
            ],
            'msgtype' => $type,
            $type => [
                self::MESSAGE_TYPE[$type] => $content
            ]
        ];
        $uri = $this->format('mass/sendall');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, $json, 'POST', 'json')
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 通过openId群发
     * @param array $openId
     * @param $content
     * @param $type
     * @return array|mixed
     * @throws \Exception
     */
    public function massForOpenId(array $openId, $content, $type)
    {
        if (!isset(self::MESSAGE_TYPE[$type])) {
            throw new \Exception("错误的消息类型");
        }
        $json = [
            'touser' => $openId,
            'msgtype' => $type,
            $type => [
                self::MESSAGE_TYPE[$type] => $content
            ]
        ];
        $uri = $this->format('mass/send');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, $json, 'POST', 'json')
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 获取群发信息状态
     * @param $mid
     * @return array|mixed
     * @throws \Exception
     */
    public function state($mid)
    {
        $uri = $this->format('mass/get');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, ['msg_id' => $mid], 'POST', 'json')
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 删除群发消息
     * @param $mid
     * @param int $aid 要删除的文章在图文消息中的位置，第一篇编号为1，该字段不填或填0会删除全部文章
     * @return array|mixed
     * @throws \Exception
     */
    public function delete($mid, $aid = 0)
    {
        $uri = $this->format('mass/delete');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, ['msg_id' => $mid, 'article_idx' => $aid], 'POST', 'json')
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 设置群发速度
     * @param $speed
     * @return array|mixed
     * @throws \Exception
     */
    public function speed($speed = 2)
    {
        $uri = $this->format('mass/speed/set');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, ['speed' => $speed], 'POST', 'json')
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }
}
