<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 12:40
 */

namespace Shrimp\Subscriber;


use Shrimp\Message\MessageType;

class LinkSubscriber extends AbstractSubscriber
{
    public function type()
    {
        // TODO: Implement type() method.
        return MessageType::LINK;
    }
}