Shrimp-wechat-sdk
=================

小虾米微信SDK是一个针对微信公众平台接口的封装。目前已实现了用户、素材、消息、菜单相关的接口，还有自动回复的处理。

#### Installation

```shell
composer require meshell/shmirp-wechat-sdk
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

//添加一个消息为text类型的处理
class Text implements \Shrimp\Response\ResponsePluginInterface
{
    
    //返回内容
    public function getResponse($package)
    {
        // TODO: Implement getResponse() method.
        return (new \Shrimp\Response\TextResponse($package))->setContent("Hello world");
    }
    
    //处理的消息类型
    public function type()
    {
        // TODO: Implement type() method.
        return \Shrimp\Message\MessageType::TEXT;
    }
    
    //事件名
    public function name()
    {
        // TODO: Implement name() method.
        return "";
    }
}

//添加一个消息为事件的类型处理
class Subscriber implements \Shrimp\Response\ResponsePluginInterface
{
    
    public function getResponse($package)
    {
        // TODO: Implement getResponse() method.
        return (new \Shrimp\Response\TextResponse($package))->setContent("谢谢你的关注");
    }

    public function type()
    {
        // TODO: Implement type() method.
        return \Shrimp\Message\MessageType::EVENT;
    }

    public function name()
    {
        // TODO: Implement name() method.
        return \Shrimp\Message\MessageType::SUBSCRIBE;
    }
}

$message = new \Shrimp\MessageDispatcher(new SimpleXMLElement($xmlData));
$message->addPlugin(new Text());
$message->addPlugin(new Subscriber());
echo $message->dispatch();

```

#### License

MIT
