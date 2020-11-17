<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/21
 * Time: 17:19
 */

declare(strict_types=1);

namespace Shrimp\Api;

use Shrimp\ShrimpWechat;

class Base
{
    /**
     * @var null|ShrimpWechat
     */
    protected $sdk = null;

    /**
     * @param ShrimpWechat $sdk
     */
    public function setSdk(ShrimpWechat $sdk)
    {
        $this->sdk = $sdk;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function format(string $name)
    {
        $gateway = $this->sdk->getGateway();
        $accessToken = $this->sdk->getAccessToken();
        return sprintf('%s%s?access_token=%s', $gateway, $name, $accessToken);
    }
}
