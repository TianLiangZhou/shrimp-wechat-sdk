<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 22:16
 */

declare(strict_types=1);

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
    const EVENT_SCANCODE_PUSH = self::EVENT . '.scancode_push';
    const EVENT_SCANCODE_WAIT = self::EVENT . '.scancode_waitmsg';
    const EVENT_PIC_SYSPHOTO = self::EVENT . '.pic_sysphoto';
    const EVENT_PIC_PHOTO_OR_ALBUM = self::EVENT . '.pic_photo_or_album';
    const EVENT_PIC_WEIXIN = self::EVENT . '.pic_weixin';
    const EVENT_LOCATION_SELECT = self::EVENT . '.location_select';
    const EVENT_VIEW_MINIPROGRAM = self::EVENT . '.view_miniprogram';
    const EVENT_MASSSENDJOBFINISH = self::EVENT . '.masssendjobfinish';
    const EVENT_LOCATION = self::EVENT . '.' . self::LOCATION;
    const EVENT_TEMPLATESENDJOBFINISH = self::EVENT . '.templatesendjobfinish';
}
