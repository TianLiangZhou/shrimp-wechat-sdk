Shrimp-wechat-sdk
=================

[![Build Status](https://travis-ci.org/TianLiangZhou/shrimp-wechat-sdk.svg?branch=master)](https://travis-ci.org/TianLiangZhou/shrimp-wechat-sdk)
[![Coverage Status](https://coveralls.io/repos/github/TianLiangZhou/shrimp-wechat-sdk/badge.svg?branch=master)](https://coveralls.io/github/TianLiangZhou/shrimp-wechat-sdk?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/eb4a04fc8f43da3ccef0/maintainability)](https://codeclimate.com/github/TianLiangZhou/shrimp-wechat-sdk/maintainability)
[![License](https://img.shields.io/badge/license-mit-blue.svg)](LICENSE)

小虾米微信SDK是一个针对微信公众平台接口的封装。目前已实现了用户、素材、消息、菜单相关的接口，还有自动回复的处理。

#### Installation

```shell
composer require meshell/shrimp-wechat-sdk
```

#### Usage

所有`api`的返回结果，都使用微信官方结果为标准。具体可以查看<https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1445241432>

##### 创建菜单

```php
<?php

use Shrimp\ShrimpWechat;

$sdk  = new ShrimpWechat('wx983dd48be764e9ce', '26b8ccf343bddeecd0402e1b864d2dd4');
try {
    $array = $sdk->menu->createMenu(["type" => "click", "name" => "测试三", "key"  => "V1001_TODAY_VIEW"]);
} catch(Exception $e) {
    throw $e;
}

```

##### 上传图片

```php
<?php

use Shrimp\ShrimpWechat;
use Shrimp\File\MediaFile;

$sdk  = new ShrimpWechat('wx983dd48be764e9ce', '26b8ccf343bddeecd0402e1b864d2dd4');
try {
    $file = $sdk->material->uploadPermanentMaterial(new MediaFile(dirname(__DIR__) . '/content-image.png'));
} catch(Exception $e) {
    throw $e;
}

```


#### 自动回复

自动回复目前支持，文本，语音，视频，图片，图文类型。

- 文本消息

```php

<?php

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shrimp\Message\Event;
use Shrimp\ShrimpWechat;
use Shrimp\Event\ResponseEvent;

class TestController implements EventSubscriberInterface
{
    static $test = null;
    
    /**
     * @var null | ShrimpWechat 
     */
    public $shrimp = null;
    
    public function __construct() 
    {
        
        self::$test = $this;
        
        $this->shrimp  = new ShrimpWechat('wx983dd48be764e9ce', '26b8ccf343bddeecd0402e1b864d2dd4');
        
        $this->shrimp->getDispatcher()->addSubscriber(self::$test);
    }
    
    /**
     * 自动回复
     * 
     * @return string
     */
    public function auto()
    {
        return $this->shrimp->send();
    }
    
    private function autoRespond(ResponseEvent $responseEvent)
    {
        $responseEvent->setResponse("hello world");
    }
    
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.

        return [
            Event::TEXT => Closure::fromCallable([self::$test, 'autoRespond']),
        ];
    }
}

(new TestController())->auto();

// 输出标准的XML

```

- 回复图片

```php
<?php

...
    function (\Shrimp\Event\ResponseEvent $responseEvent) {
        $mediaId = 123;
        $responseEvent->setResponse(
            new \Shrimp\Response\ImageResponse(
                $responseEvent->getMessageSource(), $mediaId
            )
        );
    }
...

```

- 订阅关注

```php
<?php
    ...
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.

        return [
            Event::EVENT_SUBSCRIBE => Closure::fromCallable([self::$test, 'autoSubscribeRespond']),
        ];
    }
    ...

```


#### License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
