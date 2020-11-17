<?php
declare(strict_types=1);

namespace Shrimp\Api;

/**
 * Class CustomService
 * @package Shrimp\Api
 */
class CustomService extends Base
{
    /**
     * 添加客服账号速度
     *
     * @param string $account
     * @param string $nickname
     * @param string $password
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Service_Center_messages.html#0
     */
    public function add(string $account, string $nickname, string $password)
    {
        $uri = $this->format('customservice/kfaccount/add');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http(
                    $uri,
                    'POST',
                    [
                        'json' => [
                            'kf_account' => $account, 'nickname' => $nickname, 'password' => $password,
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
     * 更新客服账号速度
     *
     * @param string $account
     * @param string $nickname
     * @param string $password
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Service_Center_messages.html#0
     */
    public function update(string $account, string $nickname, string $password)
    {
        $uri = $this->format('customservice/kfaccount/update');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http(
                    $uri,
                    'POST',
                    [
                        'json' => [
                            'kf_account' => $account, 'nickname' => $nickname, 'password' => $password,
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
     * 删除客服账号速度
     *
     * @param string $account
     * @return array
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Service_Center_messages.html#0
     */
    public function delete(string $account)
    {
        $uri = $this->format('customservice/kfaccount/del');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => [
                    'kf_account' => $account,
                ]])
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 客服账号列表
     *
     * @return array
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Service_Center_messages.html#0
     */
    public function list()
    {
        $uri = $this->format('cgi-bin/customservice/getkflist');
        try {
            $response = $this->sdk->returnResponseHandler($this->sdk->http($uri));
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

    /**
     * 发送客服消息
     *
     * @param string $openId
     * @param string $type
     * @param array $data
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Service_Center_messages.html#7
     */
    public function send(string $openId, string $type, array $data)
    {
        $uri = $this->format('cgi-bin/message/custom/send');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http(
                    $uri,
                    'POST',
                    [
                        'json' => [
                            "touser" => $openId,
                            "msgtype"=> $type,
                            $type => array_merge(Message::TYPE_DATA[$type], $data),
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
