<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 13:07
 */

namespace Bmwxin\Subscriber;


use Bmwxin\Message\MessageType;

class EventSubscriber extends AbstractSubscriber
{
    public function type()
    {
        // TODO: Implement type() method.
        return MessageType::EVENT;
    }
}