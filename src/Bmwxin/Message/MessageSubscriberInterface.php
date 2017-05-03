<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 22:00
 */

namespace Bmwxin\Message;


interface MessageSubscriberInterface
{
    public function getSubscriberType();
}