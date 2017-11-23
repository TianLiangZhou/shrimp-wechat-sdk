<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/21
 * Time: 16:57
 */

use PHPUnit\Framework\TestCase;

class MaterialTest extends TestCase
{
    private $sdk = null;
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->sdk = Shrimp\MpSDK::getInstance('wx983dd48be764e9ce','26b8ccf343bddeecd0402e1b864d2dd4');
    }

    public function testUploadFile()
    {
        $file = new \Shrimp\MediaFile(dirname(__DIR__) . '/example.png');
        $result = $this->sdk->material->uploadMaterialImage($file);
        $this->assertCount(1, $result);
    }
}