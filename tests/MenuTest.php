<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/22
 * Time: 16:49
 */

use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    private $sdk = null;
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->sdk = new Shrimp\ShrimpWechat('wx983dd48be764e9ce','26b8ccf343bddeecd0402e1b864d2dd4');
    }

    /**
     * @throws Exception
     */
    public function testMenuQuery()
    {
        $menu = $this->sdk->menu->menuQuery();
        $this->assertArrayHasKey('menu', $menu);
    }
}