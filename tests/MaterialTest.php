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

    /**
     * MaterialTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     * @throws Exception
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->sdk = new Shrimp\ShrimpWechat('wx983dd48be764e9ce','26b8ccf343bddeecd0402e1b864d2dd4');
    }

    /**
     * 测试上传图片
     * @throws Exception
     */
    public function testUploadFile()
    {
        $file = new \Shrimp\MediaFile(dirname(__DIR__) . '/example.png');
        $result = $this->sdk->material->uploadMaterialImage($file);
        $this->assertCount(1, $result);
    }

    /**
     * @dataProvider createPictureContentProvider
     *
     * @param array $content
     * @param \Shrimp\MediaFile $file
     * @throws Exception
     */
    public function testCreatePictureContent(array $content, \Shrimp\MediaFile $file)
    {
        $result = $this->sdk->material->createPictureContent($content, $file);
        $this->assertArrayHasKey('media_id', $result);
    }



    /**
     * @return array
     */
    public function createPictureContentProvider()
    {
        $content = [
            "title" => "webpack深入浅出使用教程",
            "author" => "meShell",
            "digest" => "webpack深入浅出使用教程",
            "show_cover_pic" => 1,
            "content" => "Webpack是一个模块打包器。其主要目的是将JavaScript文件捆绑在浏览器中使用，但它也能够转换，捆绑或打包任何资源或资产，使用依赖关系的模块并生成代表这些模块的静态形式(.js, .css, .png)，Webpack是运行在Node.js环境中，它是模块化解决方案的一种。",
            "content_source_url" => "http://loocode.com"
        ];
        $cover = new \Shrimp\MediaFile(dirname(__DIR__) . '/content-image.png');
        return [
            [$content, $cover]
        ];
    }


}