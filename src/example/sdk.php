<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/15
 * Time: 15:35
 */
include dirname(dirname(__DIR__)) . '/vendor/autoload.php';


$sdk  = \Shrimp\MpSDK::getInstance( 'wx983dd48be764e9ce',
        '26b8ccf343bddeecd0402e1b864d2dd4');
print_r($sdk->createMenu([

          "type" => "click",
          "name" => "测试三",
          "key"  => "V1001_TODAY_VIEW"

]));
