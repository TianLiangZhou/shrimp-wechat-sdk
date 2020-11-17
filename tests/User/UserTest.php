<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/22
 * Time: 16:49
 */
namespace Shrimp\Test\User;

use Exception;
use Shrimp\Test\TestCase;

class UserTest extends TestCase
{
    /**
     * @throws Exception
     *
     */
    public function testCreateTag()
    {
        $name = "Test" . range('a', 'z')[mt_rand(0, 25)] . mt_rand(1, 100);
        $label = $this->sdk->user->createTag($name);
        self::assertArrayHasKey('tag', $label);
        $name = "Update" . range('a', 'z')[mt_rand(0, 25)] . mt_rand(1, 100);
        self::assertArrayHasKey('errcode', $this->sdk->user->updateTag($label['tag']['id'], $name));
    }


    /**
     * @throws Exception
     *
     */
    public function testGetTags()
    {
        $tags = $this->sdk->user->getTags();
        self::assertArrayHasKey('tags', $tags);
    }


    /**
     *
     */
    public function testGet()
    {
        $response = $this->sdk->user->get(null);
        self::assertArrayHasKey('total', $response);
    }
}
