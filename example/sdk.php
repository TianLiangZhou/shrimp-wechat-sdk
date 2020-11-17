<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/15
 * Time: 15:35
 */

use Shrimp\ShrimpWechat;

include __DIR__ . '/../vendor/autoload.php';


$sdk  = new ShrimpWechat(
    'wxed1cc1b0e241ff74',
    '434ca4dfc791853b9ef36ebf24a3ce02'
);

$response = $sdk->menu->create([
          "type" => "click",
          "name" => "测试三",
          "key"  => "V1001_TODAY_VIEW"
]);

var_dump($response);
