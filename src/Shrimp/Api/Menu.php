<?php
namespace Shrimp\Api;

/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/21
 * Time: 16:56
 */
use Exception;

class Menu extends Base
{
    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function createMenu(array $data)
    {
        $requestData = [];
        if (isset($data[0])) {
            $requestData['button'] = $data;
        } else {
            $requestData['button'][] = $data;
        }
        $uri = $this->format('create');
        try {
            $response = $this->sdk->http($uri, $requestData, 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function menuQuery()
    {
        $uri = $this->format('get');
        try {
            $response = $this->sdk->http($uri);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleteMenu()
    {
        $uri = $this->format('delete');
        try {
            $response = $this->http($uri);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }
}
