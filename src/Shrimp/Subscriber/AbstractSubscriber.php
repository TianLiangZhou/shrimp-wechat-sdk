<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 12:47
 */

namespace Shrimp\Subscriber;


use Shrimp\Response\Response;
use Shrimp\Response\ResponseInterface;
use Shrimp\Response\ResponsePluginInterface;

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
            $responseContent = $plugin->getResponse($package);
            if (!($responseContent instanceof ResponseInterface)) {
                continue;
            }
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