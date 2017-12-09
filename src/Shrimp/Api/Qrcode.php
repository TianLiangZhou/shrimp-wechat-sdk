<?php

namespace Shrimp\Api;

use Exception;

class Qrcode extends Base
{

    /**
     * 创建一个二维码
     * @param string|int $content
     * @param int $expire 过期时间以分为单位
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542
     * @return array
     * @throws
     */
    public function create($content, $expire = 0)
    {
        $string = 'scene_str';
        $name = 'STR_SCENE';
        if (is_numeric($content)) {
            $string = 'scene_id';
            $name = 'SCENE';
        }
        $data = [
            'action_info' => [
                'scene' => [
                    $string => $content
                ],
            ],
        ];
        if ($expire > 0) {
            $data['expire_seconds'] = $expire * 60;
            $name = 'QR_' . $name;
        } else {
            $name = 'QR_LIMIT_' . $name;
        }
        $data['action_name'] = $name;
        $uri = $this->format('create');
        try {
            $response = $this->sdk->http($uri, $data, 'POST', 'json');
        } catch(Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 生成短连接
     * @param $url
     * @return array|mixed
     * @throws Exception
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433600
     */
    public function shortUrl($url)
    {
        $uri = $this->format('shorturl', false);
        $data = [
            'action' => 'long2short',
            'long_url' => $url
        ];
        try {
            $response = $this->sdk->http($uri, $data, 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }
}