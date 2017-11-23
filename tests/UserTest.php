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
        $this->sdk = Shrimp\MpSDK::getInstance('wx983dd48be764e9ce','26b8ccf343bddeecd0402e1b864d2dd4');
    }

    /**
    public function testCreateLabel()
    {
        $this->assertArrayHasKey('tag', $this->sdk->user->createLabel("Test"));
    }

    public function testUpdateLabel()
    {

    }
     */

    public function testGetLabel()
    {
        $tags = $this->sdk->user->getLabel();
        $this->assertArrayHasKey('tags', $tags);
    }
}