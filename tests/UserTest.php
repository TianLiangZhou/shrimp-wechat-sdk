<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/22
 * Time: 16:49
 */

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
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
    public function testCreateLabel()
    {
        $this->assertArrayHasKey('tag', $this->sdk->user->createLabel("Test"));
    }

    /**
     * @dataProvider createUpdateTagProvider
     * @throws Exception
     */
    public function testUpdateLabel($id, $name)
    {
        $this->assertArrayHasKey('errcode', $this->sdk->user->updateLabel($id, $name));
    }

    /**
     * @throws Exception
     */
    public function testGetLabel()
    {
        $tags = $this->sdk->user->getLabel();
        $this->assertArrayHasKey('tags', $tags);
    }

    /**
     * @return array
     */
    private function createUpdateTagProvider()
    {
        return [
            [100, 'updateTag']
        ];
    }
}