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
 * Class Menu
 * @package Shrimp\Api
 */
class Menu extends Base
{
    /**
     * 创建菜单
     *
     * @param array $data
     * @return array
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Creating_Custom-Defined_Menu.html
     */
    public function create(array $data)
    {
        $requestData = [];
        if (isset($data[0])) {
            $requestData['button'] = $data;
        } else {
            $requestData['button'][] = $data;
        }
        $uri = $this->format('cgi-bin/menu/create');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => $requestData]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 查询菜单
     *
     * @return array
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Querying_Custom_Menus.html
     */
    public function query()
    {
        $uri = $this->format('cgi-bin/get_current_selfmenu_info');
        try {
            $response = $this->sdk->http($uri);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 删除菜单
     *
     * @return array
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Deleting_Custom-Defined_Menu.html
     */
    public function delete()
    {
        $uri = $this->format('cgi-bin/menu/delete');
        try {
            $response = $this->sdk->http($uri);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 创建个性化菜单
     *
     * @param array $data
     * @return array
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Personalized_menu_interface.html
     */
    public function addConditional(array $data)
    {
        $uri = $this->format('cgi-bin/menu/addconditional');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => $data]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 删除个性化菜单
     *
     * @param int $menuId
     * @return array
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Personalized_menu_interface.html#1
     */
    public function deleteConditional(int $menuId)
    {
        $uri = $this->format('cgi-bin/menu/delconditional');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['menuid' => $menuId]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 测试匹配
     *
     * @param array $data
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Personalized_menu_interface.html#1
     */
    public function tryMatch(array $data)
    {
        $uri = $this->format('cgi-bin/menu/trymatch');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => $data]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取个性化菜单配置
     *
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Getting_Custom_Menu_Configurations.html
     */
    public function get()
    {
        $uri = $this->format('cgi-bin/menu/get');
        try {
            $response = $this->sdk->http($uri);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }



}
