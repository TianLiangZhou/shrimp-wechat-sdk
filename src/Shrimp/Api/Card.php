<?php

namespace Shrimp\Api;

use Exception;

class Card extends Base
{
    const CARD_TYPE_GROUPON = "GROUPON";
    const CARD_TYPE_CASH = "CASH";
    const CARD_TYPE_DISCOUNT= "DISCOUNT";
    const CARD_TYPE_GIFT = "GIFT";
    const CARD_TYPE_GENERAL_COUPON = "GENERAL_COUPON";

    /**
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025056
     * @param string $type
     * @param array $baseInfo
     * @return array|mixed
     * @throws \HttpRequestException
     * @throws Exception
     */
    public function create($type, array $baseInfo)
    {
        $uri = $this->format('create');
        $data = [
             'card' => [
                 'card_type' => $type,
                 strtolower($type) => [
                     'base_info' => $baseInfo
                 ]
             ]
        ];
        try {
            $response = $this->sdk->http($uri, $data, 'POST', 'json');
        } catch (\HttpRequestException $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }
}