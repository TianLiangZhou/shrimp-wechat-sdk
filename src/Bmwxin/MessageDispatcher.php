<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 21:48
 */

namespace Bmwxin;


use Bmwxin\Response\Response;
use Bmwxin\Response\ResponsePluginInterface;
use Bmwxin\Subscriber\SubscriberInterface;
use Bmwxin\Subscriber\EventSubscriber;
use Bmwxin\Subscriber\ImageSubscriber;
use Bmwxin\Subscriber\LinkSubscriber;
use Bmwxin\Subscriber\LocationSubscriber;
use Bmwxin\Subscriber\ShortVideoSubscriber;
use Bmwxin\Subscriber\TextSubscriber;
use Bmwxin\Subscriber\VideoSubscriber;
use Bmwxin\Subscriber\VoiceSubscriber;

class MessageDispatcher
{
    private $package = null;

    private $subscribers = [];

    private $defaultSubscribers = [
        TextSubscriber::class,
        ImageSubscriber::class,
        LinkSubscriber::class,
        LocationSubscriber::class,
        ShortVideoSubscriber::class,
        VoiceSubscriber::class,
        VideoSubscriber::class,
        EventSubscriber::class
    ];

    private $plugins = [];
    /**
     * MessageDispatcher constructor.
     * @param \SimpleXMLElement $package
     */
    public function __construct(\SimpleXMLElement $package)
    {
        $this->package = $package;
        foreach ($this->defaultSubscribers as $class) {
            $this->addSubscribers(new $class);
        }
    }

    /**
     * @param null $response
     * @return Response|null
     */
    public function dispatch($response = null)
    {
        if (null == $response) {
            $response = new Response();
        }
        $messageType = (string) $this->package->MsgType;
        if (($subscribers = $this->getSubscriber($messageType))) {
            $this->doDispatch($subscribers, $response);
        }
        return $response;
    }

    /**
     * @param $subscribers
     * @param \Bmwxin\Response $response
     */
    private function doDispatch($subscribers, Response $response)
    {
        foreach ($subscribers as $callback) {
            call_user_func($callback, $response, $this->package, $this->plugins);
        }
    }

    public function addPlugins(array $plugins)
    {
        foreach ($plugins as $plugin) {
            $this->plugins[] = $plugin;
        }
    }

    public function addPlugin(ResponsePluginInterface $plugin)
    {
        $this->plugins[] = $plugin;
    }
    /**
     * @param $type
     * @return array|mixed
     */
    private function getSubscriber($type)
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
     * @param SubscriberInterface $subscriber
     */
    private function addSubscribers(SubscriberInterface $subscriber)
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