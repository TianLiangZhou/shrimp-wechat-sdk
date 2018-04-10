<?php

namespace Shrimp\Api;

use Exception;

class Datacube extends Base
{

    /**
     * 获取用户增减数据
     * @param string $begin 开始时间
     * @param string $end 结束时间
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141082
     * @return array
     * @throws
     */
    public function grow($begin, $end)
    {
        $uri = $this->format("getusersummary");
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取用户累计数据
     * @param string $begin 开始时间
     * @param string $end 结束时间
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141082
     * @return array
     * @throws
     */
    public function count($begin, $end)
    {
        $uri = $this->format("getusercumulate");
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文群发每日数据
     * @param string $begin
     * @param string $end
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @return array|mixed
     * @throws
     */
    public function getTodayArticleMass($begin, $end)
    {
        $uri = $this->format('getarticlesummary');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文群发总数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     */
    public function getArticleMassCount($begin, $end)
    {
        $uri = $this->format('getarticletotal');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文统计数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     */
    public function getArticleCount($begin, $end)
    {
        $uri = $this->format('getuserread');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文统计分时数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     */
    public function getArticleHour($begin, $end)
    {
        $uri = $this->format('getuserreadhour');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文分享转发数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     */
    public function getArticleShare($begin, $end)
    {
        $uri = $this->format('getusershare');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文分享转发分时数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     */
    public function getArticleShareHour($begin, $end)
    {
        $uri = $this->format('getusersharehour');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送概况数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     */
    public function getUpstreamMessage($begin, $end)
    {
        $uri = $this->format('getupstreammsg');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息分送分时数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     */
    public function getUpstreamMessageHour($begin, $end)
    {
        $uri = $this->format('getupstreammsghour');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送周数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     */
    public function getUpstreamMessageWeek($begin, $end)
    {
        $uri = $this->format('getupstreammsgweek');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送月数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     */
    public function getUpstreamMessageMonth($begin, $end)
    {
        $uri = $this->format('getupstreammsgmonth');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送分布数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     */
    public function getUpstreamDist($begin, $end)
    {
        $uri = $this->format('getupstreammsgdist');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送分布周数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     */
    public function getUpstreamDistWeek($begin, $end)
    {
        $uri = $this->format('getupstreammsgdistweek');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送分布月数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     */
    public function getUpstreamDistMonth($begin, $end)
    {
        $uri = $this->format('getupstreammsgdistmonth');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取接口分析数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141086
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     */
    public function getInterface($begin, $end)
    {
        $uri = $this->format('getinterfacesummary');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取接口分析分时数据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141086
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     */
    public function getInterfaceHour($begin, $end)
    {
        $uri = $this->format('getinterfacesummaryhour');
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }
}
