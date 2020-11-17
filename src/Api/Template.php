<?php
declare(strict_types=1);

namespace Shrimp\Api;

/**
 * Class Template
 * @package Shrimp\Api
 */
class Template extends Base
{
    /**
     * 设置所属行业
     *
     * @param int $id1
     * @param int $id2
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#0
     */
    public function setIndustry(int $id1, int $id2 = 0)
    {
        $uri = $this->format('cgi-bin/template/api_set_industry');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http(
                    $uri,
                    'POST',
                    [
                        'json' => [
                            "industry_id1" => $id1,
                            "industry_id2" => $id2,
                        ]
                    ]
                )
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 所属行业
     *
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#1
     */
    public function getIndustry()
    {
        $uri = $this->format('cgi-bin/template/get_industry');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri)
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 添加模板id
     *
     * @param string $id
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#2
     */
    public function add(string $id)
    {
        $uri = $this->format('cgi-bin/template/api_add_template');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => ['template_id_short' => $id]])
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 模板列表
     *
     * @param string $id
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#4
     */
    public function getAll(string $id)
    {
        $uri = $this->format('cgi-bin/template/get_all_private_template');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri)
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 删除模板
     *
     * @param string $id
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#5
     */
    public function delete(string $id)
    {
        $uri = $this->format('cgi-bin/template/del_private_template');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => ['template_id' => $id]])
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 发送模板消息
     *
     * @param string $openId
     * @param string $tid
     * @param array $data
     * @param string $url
     * @param array $miniprogram
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#6
     */
    public function send(string $openId, string $tid, array $data, string $url = '', array $miniprogram = [])
    {
        $uri = $this->format('cgi-bin/message/template/send');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http(
                    $uri,
                    'POST',
                    [
                        'json' => [
                            'touser' => $openId,
                            'url' => $url,
                            'template_id' => $tid,
                            'miniprogram' => $miniprogram,
                            'data' => $data,
                        ]
                    ]
                )
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }
}
