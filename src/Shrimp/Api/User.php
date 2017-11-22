<?php
namespace Shrimp\Api;
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/21
 * Time: 16:56
 */
use Exception;
use Shrimp\MpSDK;

class User extends Base
{
    /**
     * 创建用户标签
     * @param string $name
     * @return array
     */
    public function createLabel($name)
    {
        $uri = $this->format('create', true, 'tags');
        $data['tag']['name'] = $name;
        try {
            $response = $this->sdk->http($uri, $data, 'POST', 'json');
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取已创建的标签
     * @return array|mixed
     * @throws Exception
     */
    public function getLabel()
    {
        $uri = $this->format('get', true, 'tags');
        try {
            $response = $this->sdk->http($uri);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 更新标签
     * @param $id
     * @param $name
     * @return array|mixed
     * @throws Exception
     */
    public function updateLabel($id, $name)
    {
        $uri = $this->format('update', true, 'tags');

        try {
            $response = $this->sdk->http(
                $uri, ['tag' => ['id' => $id, 'name' => $name]], 'POST', 'json'
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 删除标签
     * @param $id
     * @return array|mixed
     * @throws Exception
     */
    public function deleteLabel($id)
    {
        $uri = $this->format('delete', true, 'tags');
        try {
            $response = $this->sdk->http(
                $uri, ['tag' => ['id' => $id]], 'POST', 'json'
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取标签下的粉丝列表
     * @param $id
     * @param null $openId
     * @return array|mixed
     * @throws Exception
     */
    public function getFollow($id, $openId = null)
    {
        $uri = $this->format('tag/get');
        try {
            $response = $this->sdk->http(
                $uri, ['tagid' => $id, 'next_openid' => $openId], 'POST', 'json'
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 批量给用户打上标签
     * @param array $openId
     * @param $id
     * @return array|mixed
     * @throws Exception
     */
    public function applyLabel(array $openId, $id)
    {
        $uri = $this->format('members/batchtagging', true, 'tags');
        try {
            $response = $this->sdk->http(
                $uri, ['openid_list' => $openId, 'tagid' => $id], 'POST', 'json'
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 批量取消用户标签
     * @param array $openId
     * @param $id
     * @return array|mixed
     * @throws Exception
     */
    public function cancelLabel(array $openId, $id)
    {
        $uri = $this->format('members/batchuntagging', true, 'tags');
        try {
            $response = $this->sdk->http(
                $uri, ['openid_list' => $openId, 'tagid' => $id], 'POST', 'json'
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取用户的标签列表
     * @param $openId
     * @return array|mixed
     * @throws Exception
     */
    public function getUserLabel($openId)
    {
        $uri = $this->format('getidlist', true, 'tags');
        try {
            $response = $this->sdk->http(
                $uri, ['openid' => $openId], 'POST', 'json'
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取用户列表
     * @param null $nextOpenId
     * @return array|mixed
     * @throws Exception
     */
    public function get($nextOpenId = null)
    {
        $uri = $this->format('get') . '&next_openid=' . $nextOpenId;
        try {
            $response = $this->sdk->http($uri);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取用户信息基本信息
     * @param $openId
     * @param string $lang
     * @return array|mixed
     * @throws Exception
     */
    public function info($openId, $lang = 'zh_CN')
    {
        $uri = $this->format('info') . '&openid=' . $openId . '&lang=' . $lang;
        try {
            $response = $this->sdk->http($uri);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 批量获取用户信息
     * @param array $openId
     * @param string $lang
     * @return array|mixed
     * @throws Exception
     */
    public function batchInfo(array $openId, $lang = 'zh_CN')
    {
        $uri = $this->format('info/batchget');
        try {
            $list = array_map(function($id) use ($lang) {
                return [
                    'openid' => $id, 'lang' => $lang
                ];
            }, $openId);
            $response = $this->sdk->http($uri, ['user_list' => $list], 'POST', 'json');
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 设置用户的备注名
     * @param $openId
     * @param $mark
     * @return array|mixed
     * @throws Exception
     */
    public function mark($openId, $mark)
    {
        $uri = $this->format('info/updateremark');
        try {
            $response = $this->sdk->http($uri, ['openid' => $openId, 'remark' => $mark], 'POST', 'json');
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }
}