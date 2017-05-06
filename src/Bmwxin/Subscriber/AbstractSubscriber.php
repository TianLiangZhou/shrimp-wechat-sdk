<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 12:47
 */

namespace Bmwxin\Subscriber;


use Bmwxin\Response\Response;
use Bmwxin\Response\ResponsePluginInterface;

abstract class AbstractSubscriber implements SubscriberInterface
{
    public function response(Response $response, \SimpleXMLElement $package, array $plugins = [])
    {
        // TODO: Implement response() method.
        $type = $this->type();
        foreach ($plugins as $plugin) {
            /**
             * @var $plugin ResponsePluginInterface
             */
            if ($type == $plugin->type() && $type !== 'event') {
                $response->setContent($plugin->getResponse($package));
            }
            if ($type == 'event' && $plugin->name() == strtolower((string)$package->Event)) {
                $response->setContent($plugin->getResponse($package));
            }
        }
        return $response;
    }

    public function getSubscriberType()
    {
        return [
            $this->type() => ['response', 0]
        ];
    }
}