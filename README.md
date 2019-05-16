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

基本接口的使用

```php
<?php

$sdk  = new \Shrimp\ShrimpWechat('wx983dd48be764e9ce', '26b8ccf343bddeecd0402e1b864d2dd4');
try {
    //直接使用方法名
    $array = $sdk->createMenu(["type" => "click", "name" => "测试三", "key"  => "V1001_TODAY_VIEW"]);
    //使用模块名, 建议使用这种方式
    $array = $sdk->menu->createMenu(["type" => "click", "name" => "测试三", "key"  => "V1001_TODAY_VIEW"]);
    
} catch(Exception $e) {
    throw $e;
}

//返回

Array
(
    [errcode] => 0
    [errmsg] => ok
)



```

#### 自动回复

```php

<?php

$sdk  = new \Shrimp\ShrimpWechat('wx983dd48be764e9ce', '26b8ccf343bddeecd0402e1b864d2dd4');

$sdk->bind(function(\Shrimp\Event\ResponseEvent $response) {
    $response->setResponse("Hello world"); //回给用户Hello world
}); //默认绑定text消息

echo $sdk->send();


//判断消息是不是等于activity, 等于就返回新闻消息
$sdk->bind(function(\Shrimp\Event\ResponseEvent  $response) use ($sdk) {
    $text = (string) $response->getAttribute('Content');
    if ($text === 'activity') {
        $response->setResponse([
            'type' => \Shrimp\Message\Event::NEWS,
            'content' => [
                'title' => '抽奖活动',
                'pic_url'   => 'http://activity.example/images/activity.jpg',
                'url'   => 'http://activity.example.com/888',
                'description' => 'test'
            ],
        ]);
    } else if ($text === 'code') { //等于code 就回复一个图片给用户
        $file = $sdk->material->uploadPermanentMaterial(new \Shrimp\File\MediaFile(dirname(__DIR__) . '/content-image.png'));
        $response->setResponse(
            new \Shrimp\Response\ImageResponse($response->getMessageSource(), $file['media_id'])
        );
        // or 
        $response->setResponse([
            'type' => \Shrimp\Message\Event::IMAGE, 'content' => $file['media_id']
        ]);
        
    }
}, \Shrimp\Message\Event::TEXT);

echo $sdk->send();

```

#### License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
