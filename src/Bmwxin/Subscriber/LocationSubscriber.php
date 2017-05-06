<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 12:39
 */

namespace Bmwxin\Subscriber;


use Bmwxin\Message\MessageType;

class LocationSubscriber extends AbstractSubscriber
{
    public function type()
    {
        // TODO: Implement type() method.
        return MessageType::LOCATION;
    }
}