<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 12:36
 */

namespace Shrimp\Subscriber;


use Shrimp\Message\MessageType;

class TextSubscriber extends AbstractSubscriber
{
    public function type()
    {
        // TODO: Implement type() method.
        return MessageType::TEXT;
    }
}