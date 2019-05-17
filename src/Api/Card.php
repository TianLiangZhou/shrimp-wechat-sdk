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
     * 创建卡片
     *
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025056
     * @param string $type
     * @param array $baseInfo
     * @param $exclusive
     * @param array $advanceInfo
     * @return array|mixed
     * @throws Exception
     */
    public function create($type, array $baseInfo, $exclusive, array $advanceInfo = [])
    {
        $uri = $this->format('create');
        $property = [
            'base_info' => $baseInfo,
            'advanced_info' => $advanceInfo,
        ];

        switch ($type) {
            case static::CARD_TYPE_GENERAL_COUPON:
                $property['deal_detail'] = $exclusive;
                break;
            case static::CARD_TYPE_CASH:
                $property['least_cost'] = $exclusive['least_cost'];
                $property['reduce_cost']= $exclusive['reduce_cost'];
                break;
            case static::CARD_TYPE_DISCOUNT:
                $property['discount'] = (int)$exclusive;
                break;
            case static::CARD_TYPE_GIFT:
                $property['gift'] = $exclusive;
                break;
            case static::CARD_TYPE_GROUPON:
                $property['default_detail'] = $exclusive;
                break;
        }

        $data = [
             'card' => [
                 'card_type' => $type,
                 strtolower($type) => $property
             ]
        ];
        try {
            $response = $this->sdk->http($uri, $data, 'POST', 'json');
        } catch (Exception $e) {
            throw $e;
        }
        return $this->sdk->returnResponseHandler($response);
    }
}
