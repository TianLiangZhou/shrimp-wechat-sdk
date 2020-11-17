<?php

declare(strict_types=1);

namespace Shrimp\Api;

/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/21
 * Time: 16:56
 */
use Exception;

/**
 * Class User
 * @package Shrimp\Api
 * @see https://developers.weixin.qq.com/doc/offiaccount/User_Management/User_Tag_Management.html
 */
class User extends Base
{
    /**
     * 创建用户标签
     *
     * @param string $name
     * @return array
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function createTag(string $name)
    {
        $uri = $this->format('cgi-bin/tags/create');
        $data['tag']['name'] = $name;
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => $data]);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取已创建的标签
     *
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getTags()
    {
        $uri = $this->format('cgi-bin/tags/get');
        try {
            $response = $this->sdk->http($uri);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 更新标签
     *
     * @param $id
     * @param $name
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function updateTag(int $id, string $name)
    {
        $uri = $this->format('cgi-bin/tags/update');

        try {
            $response = $this->sdk->http(
                $uri,
                'POST',
                ['json' => ['tag' => ['id' => $id, 'name' => $name]]],
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 删除标签
     *
     * @param $id
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function deleteTag(int $id)
    {
        $uri = $this->format('cgi-bin/tags/delete');
        try {
            $response = $this->sdk->http(
                $uri,
                'POST',
                ['json' => ['tag' => ['id' => $id]]]
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取标签下的粉丝列表
     *
     * @param $id
     * @param string|null $openId
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getTagUser(int $id, string $openId = null)
    {
        $uri = $this->format('cgi-bin/user/tag/get');
        try {
            $response = $this->sdk->http(
                $uri,
                'POST',
                ['json' => ['tagid' => $id, 'next_openid' => $openId]]
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 批量给用户打上标签
     *
     * @param array $openId
     * @param $id
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function batchTagging(array $openId, int $id)
    {
        $uri = $this->format('cgi-bin/tags/members/batchtagging');
        try {
            $response = $this->sdk->http(
                $uri,
                'POST',
                ['json' => ['openid_list' => $openId, 'tagid' => $id]]
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 批量取消用户标签
     *
     * @param array $openId
     * @param $id
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function batchUnTagging(array $openId, $id)
    {
        $uri = $this->format('cgi-bin/tags/members/batchuntagging');
        try {
            $response = $this->sdk->http(
                $uri,
                'POST',
                ['json' => ['openid_list' => $openId, 'tagid' => $id]],
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取用户的标签列表
     *
     * @param $openId
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUserTags(string $openId)
    {
        $uri = $this->format('cgi-bin/tags/getidlist');
        try {
            $response = $this->sdk->http(
                $uri,
                'POST',
                ['json' => ['openid' => $openId]]
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取用户列表
     *
     * @param string|null $nextOpenId
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function get(string $nextOpenId = null)
    {
        $uri = $this->format('cgi-bin/user/get') . '&next_openid=' . $nextOpenId;
        try {
            $response = $this->sdk->http($uri);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取用户信息基本信息
     *
     * @param $openId
     * @param string $lang
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function info(string $openId, string $lang = 'zh_CN')
    {
        $uri = $this->format('cgi-bin/user/info') . '&openid=' . $openId . '&lang=' . $lang;
        try {
            $response = $this->sdk->http($uri);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 批量获取用户信息
     *
     * @param array $openId
     * @param string $lang
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function batchInfo(array $openId, string $lang = 'zh_CN')
    {
        $uri = $this->format('cgi-bin/user/info/batchget');
        try {
            $list = array_map(function ($id) use ($lang) {
                return [
                    'openid' => $id, 'lang' => $lang
                ];
            }, $openId);
            $response = $this->sdk->http($uri, 'POST', ['json' => ['user_list' => $list]]);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 设置用户的备注名
     *
     * @param $openId
     * @param $mark
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function mark($openId, $mark)
    {
        $uri = $this->format('cgi-bin/user/info/updateremark');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['openid' => $openId, 'remark' => $mark]]);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取黑名单
     *
     * @param string $nextOpenId 上个openid
     * @return array
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function blackList(string $nextOpenId = '')
    {
        $uri = $this->format("cgi-bin/tags/members/getblacklist");
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_openid' => $nextOpenId]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }
}
