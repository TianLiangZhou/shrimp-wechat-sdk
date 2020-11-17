<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/22
 * Time: 16:49
 */

namespace Shrimp\Test\Menu;

use Exception;
use Shrimp\Test\TestCase;

class MenuTest extends TestCase
{
    /**
     * @throws Exception
     *
     */
    public function testMenuQuery()
    {
        $menu = $this->sdk->menu->query();
        self::assertArrayHasKey('is_menu_open', $menu);
    }

    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testCreate()
    {
        $data = [
            [
                "name" => "扫码",
                "sub_button" => [
                    [
                        "type" => "scancode_waitmsg",
                        "name" => "扫码带提示",
                        "key" => "rselfmenu_0_0",
                        "sub_button" => [ ]
                    ],
                    [
                        "type" => "scancode_push",
                        "name" => "扫码推事件",
                        "key" => "rselfmenu_0_1",
                        "sub_button" => [ ]
                    ]
                ]
            ],
            [
                "name" => "发送位置",
                "type" => "location_select",
                "key" => "rselfmenu_2_0"
            ],
        ];
        $response = $this->sdk->menu->create($data);
        self::assertArrayHasKey('errmsg', $response);
    }

    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testDelete()
    {
        $response = $this->sdk->menu->delete();
        self::assertArrayHasKey('errmsg', $response);
    }
}
