<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/22
 * Time: 16:49
 */
namespace Shrimp\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use Shrimp\ShrimpWechat;

class UserTest extends TestCase
{
    private $sdk = null;

    /**
     * UserTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->sdk = new ShrimpWechat('wx983dd48be764e9ce', '26b8ccf343bddeecd0402e1b864d2dd4');
    }


    /**
     * @throws Exception
     */
    public function testCreateLabel()
    {
        $name = "Test" . range('a', 'z')[mt_rand(0, 25)] . mt_rand(1, 100);
        $label = $this->sdk->user->createLabel($name);
        $this->assertArrayHasKey('tag', $label);
        $name = "Update" . range('a', 'z')[mt_rand(0, 25)] . mt_rand(1, 100);
        $this->assertArrayHasKey('errcode', $this->sdk->user->updateLabel($label['tag']['id'], $name));
    }


    /**
     * @throws Exception
     */
    public function testGetLabel()
    {
        $tags = $this->sdk->user->getLabel();
        $this->assertArrayHasKey('tags', $tags);
    }
}
