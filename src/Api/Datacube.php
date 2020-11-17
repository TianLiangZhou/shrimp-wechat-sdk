<?php

declare(strict_types=1);

namespace Shrimp\Api;

use Exception;

class Datacube extends Base
{

    /**
     * 获取用户增减数据
     *
     * @param string $begin 开始时间
     * @param string $end 结束时间
     * @return array
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141082
     */
    public function getUserSummary(string $begin, string $end)
    {
        $uri = $this->format("datacube/getusersummary");
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取用户累计数据
     *
     * @param string $begin 开始时间
     * @param string $end 结束时间
     * @return array
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141082
     */
    public function getUserCumulate(string $begin, string $end)
    {
        $uri = $this->format("datacube/getusercumulate");
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文群发每日数据
     *
     * @param string $begin 开始时间
     * @param string $end 结束时间
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     */
    public function getArticleSummary(string $begin, string $end)
    {
        $uri = $this->format('datacube/getarticlesummary');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文群发总数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getArticleTotal(string $begin, string $end)
    {
        $uri = $this->format('datacube/getarticletotal');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文阅读统计数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUserRead(string $begin, string $end)
    {
        $uri = $this->format('datacube/getuserread');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文阅读统计分时数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUserReadHour(string $begin, string $end)
    {
        $uri = $this->format('datacube/getuserreadhour');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文分享转发数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUserShare(string $begin, string $end)
    {
        $uri = $this->format('datacube/getusershare');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取图文分享转发分时数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUserShareHour(string $begin, string $end)
    {
        $uri = $this->format('datacube/getusersharehour');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送概况数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     */
    public function getUpstreamMessage(string $begin, string $end)
    {
        $uri = $this->format('datacube/getupstreammsg');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息分送分时数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUpstreamMessageHour(string $begin, string $end)
    {
        $uri = $this->format('datacube/getupstreammsghour');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送周数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUpstreamMessageWeek(string $begin, string $end)
    {
        $uri = $this->format('datacube/getupstreammsgweek');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送月数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUpstreamMessageMonth(string $begin, string $end)
    {
        $uri = $this->format('datacube/getupstreammsgmonth');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送分布数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUpstreamMessageDist(string $begin, string $end)
    {
        $uri = $this->format('datacube/getupstreammsgdist');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送分布周数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUpstreamMessageDistWeek(string $begin, string $end)
    {
        $uri = $this->format('datacube/getupstreammsgdistweek');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取消息发送分布月数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUpstreamMessageDistMonth(string $begin, string $end)
    {
        $uri = $this->format('datacube/getupstreammsgdistmonth');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取接口分析数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141086
     * @param string $begin
     * @param $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getInterfaceSummary(string $begin, string $end)
    {
        $uri = $this->format('datacube/getinterfacesummary');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取接口分析分时数据
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141086
     * @param string $begin
     * @param string $end
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getInterfaceSummaryHour(string $begin, string $end)
    {
        $uri = $this->format('datacube/getinterfacesummaryhour');
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => ['begin_date' => $begin, 'end_date' => $end]]);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }
}
