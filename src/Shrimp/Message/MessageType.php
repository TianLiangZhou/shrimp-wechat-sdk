<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 22:16
 */

namespace Shrimp\Message;


class MessageType
{
    const TEXT = 'text';
    const IMAGE = 'image';
    const VOICE = 'voice';
    const VIDEO = 'video';
    const MUSIC = 'music';
    const LINK  = 'link';
    const NEWS  = 'news';
    const SHORTVIDEO = 'shortvideo';
    const EVENT = 'event';

    const LOCATION = 'location'; //可以是事件类型，也可以是消息类型
    /**
     * 事件类型
     */
    const SUBSCRIBE = 'subscribe';
    const SCAN = 'scan';
    const CLICK = 'click';
    const VIEW  = 'view';
}