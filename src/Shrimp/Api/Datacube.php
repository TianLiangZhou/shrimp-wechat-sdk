<?php

namespace Shrimp\Api;

class Datacube extends Base
{

    /**
     * 获取用户增减数据
     * @param string $begin 开始时间
     * @param string $end 结束时间
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141082
     * @return array 
     */
    public function grow($begin, $end)
    {
        $uri = $this->format("getusersummary");
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch(Exception $e) {
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
     */
    public function count($begin, $end)
    {
        $uri = $this->format("getusercumulate");
        try {
            $response = $this->sdk->http($uri, ['begin_date' => $begin, 'end_date' => $end], 'POST', 'json');
        } catch(Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     */
    public function getTodayArticleMass()
    {
        $this->format('getarticlesummary');
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     */
    public function getArticleMassCount()
    {
        $this->format('getarticletotal');
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     */
    public function getArticleCount()
    {
        $this->format('getuserread');
    }
    
    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     */
    public function getArticleHour()
    {
        $this->format('getuserreadhour');
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     */
    public function getArticleShare()
    {
        $this->format('getusershare');
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
     */
    public function getArticleShareHour()
    {
        $this->format('getusersharehour');
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     */
    public function getUpstreamMessage()
    {

        $this->format('getupstreammsg');
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     */
    public function getUpstreamMessageHour()
    {

        $this->format('getupstreammsghour');
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     */
    public function getUpstreamMessageWeek()
    {

        $this->format('getupstreammsgweek');
    }
    
    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     */
    public function getUpstreamMessageMonth()
    {
        $this->format('getupstreammsgmonth');
    }
    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     */
    public function getUpstreamDist()
    {

        $this->format('getupstreammsgdist');
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     */
    public function getUpstreamDistWeek()
    {

        $this->format('getupstreammsgdistweek');
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
     */
    public function getUpstreamDistMonth()
    {
        $this->format('getupstreammsgdistmonth');
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141086
     */
    public function getInterface()
    {
        $this->format('getinterfacesummary');
        
    }

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141086
     */
    public function getInterfaceHour()
    {
        $this->format('getinterfacesummaryhour');
    }
}