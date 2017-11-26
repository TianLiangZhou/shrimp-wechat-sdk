<?php

namespace Shrimp\Api;

class Qrcode extends Base;
{

    /**
     * 创建一个二维码
     * @param string|int $content
     * @param int $expire 过期时间以分为单位
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
}