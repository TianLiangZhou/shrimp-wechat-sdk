<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 22:00
 */

namespace Shrimp\Subscriber;


use Shrimp\Response\Response;

interface SubscriberInterface
{
    public function response(Response $response, \SimpleXMLElement $package, array $plugin = []);
    public function type();
}