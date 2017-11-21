<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 12:38
 */

namespace Shrimp\Subscriber;


use Shrimp\Message\MessageType;

class VideoSubscriber extends AbstractSubscriber
{
    public function type()
    {
        // TODO: Implement type() method.
        return MessageType::VIDEO;
    }
}