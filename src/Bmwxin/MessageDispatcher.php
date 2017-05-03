<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 21:48
 */

namespace Bmwxin;


use Bmwxin\Response;
use Bmwxin\Message\MessageSubscriberInterface;

class MessageDispatcher
{
    private $package = null;

    private $subscribers = [];

    public function __construct(\SimpleXMLElement $package)
    {
        $this->package = $package;
    }

    public function dispatch($response = null)
    {
        if (null == $response) {
            $response = new Response();
        }
        if (($subscribers = $this->getSubscriber($this->package->MsgType))) {
            $this->doDispatch($subscribers, $response);
        }
        return $response;
    }

    private function doDispatch($subscribers, Response $response)
    {
        foreach ($subscribers as $callback) {
            call_user_func($callback, $response, $this->package);
        }
    }

    public function getSubscriber($type)
    {
        if (!isset($this->subscribers[$type])) {
            return [];
        }
        krsort($this->subscribers[$type]);
        return call_user_func_array('array_merge', $this->subscribers[$type]);
    }
    /**
     * @param $messageType
     * @param $callback
     * @param int $priority
     */
    private function addSubscriber($messageType, $callback, $priority = 0)
    {
        $this->subscribers[$messageType][$priority][] = $callback;
    }

    /**
     * @param MessageSubscriberInterface $subscriber
     */
    public function addSubscribers(MessageSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscriberType() as $messageType => $callback) {
            if (is_string($callback)) {
                $this->addSubscriber($messageType, [$subscriber, $callback]);
            } elseif (is_string($callback[0])) {
                $this->addSubscriber(
                    $messageType, [$subscriber, $callback[0]], isset($callback[1]) ? $callback[1] : 0
                );
            }
        }
    }
}