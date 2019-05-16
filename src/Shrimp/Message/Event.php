<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 22:16
 */

namespace Shrimp\Message;

final class Event
{
    const TEXT = 'text';
    const IMAGE = 'image';
    const VOICE = 'voice';
    const VIDEO = 'video';
    const MUSIC = 'music';
    const LINK  = 'link';
    const NEWS  = 'news';
    const SHORT_VIDEO = 'shortvideo';
    const EVENT = 'event';

    const LOCATION = 'location'; //可以是事件类型，也可以是消息类型

    /**
     * 事件类型
     */
    const EVENT_SUBSCRIBE = self::EVENT . '.subscribe';
    const EVENT_SCAN = self::EVENT . '.scan';
    const EVENT_CLICK = self::EVENT . '.click';
    const EVENT_VIEW  = self::EVENT . '.view';
    const EVENT_LOCATION = self::EVENT . '.' . self::LOCATION;
}
