<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/21
 * Time: 17:19
 */

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
     * @param $name
     * @param bool $isModule
     * @return string
     */
    protected function format($name, $isModule = true, $class = null)
    {
        $gateway = $this->sdk->getGateway();
        $accessToken = $this->sdk->getAccessToken();
        if ($isModule) {
            if (empty($class)) {
                $class = strtolower(
                    str_replace(__NAMESPACE__ . '\\', '', get_class($this))
                );
            }
            $gateway .= $class . '/';
        }
        return $gateway . $name . '?access_token=' . $accessToken;
    }
}