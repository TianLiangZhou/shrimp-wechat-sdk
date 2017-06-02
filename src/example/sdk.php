<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/15
 * Time: 15:35
 */
include dirname(dirname(__DIR__)) . '/vendor/autoload.php';


$sdk  = new \Bmwxin\MpSDK( 'wx983dd48be764e9ce',
        '26b8ccf343bddeecd0402e1b864d2dd4');

try {
    $sdk->requestAccessToken();
}catch (Exception $e) {

}
echo $sdk->createMenu([

          "type" => "click",
          "name" => "测试",
          "key"  => "V1001_TODAY_MUSIC"

]);
print_r($sdk->menuQuery());
