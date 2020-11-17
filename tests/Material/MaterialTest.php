<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/21
 * Time: 16:57
 */

namespace Shrimp\Test\Material;

use Exception;
use Shrimp\File\MediaFile;
use Shrimp\Test\TestCase;

class MaterialTest extends TestCase
{
    /**
     * 测试上传图片
     *
     * @throws Exception
     */
    public function testMediaUploadImg()
    {
        $file = new MediaFile(realpath(__DIR__ . '/../../example.png'));
        $result = $this->sdk->material->mediaUploadImg($file);
        self::assertCount(1, $result);
    }

    public function testAdd()
    {
        $cover = new MediaFile(realpath(__DIR__ . '/../../content-image.png'));

        $response = $this->sdk->material->add($cover);

        self::assertArrayHasKey('media_id', $response);
    }

    /**
     * @dataProvider addNewsProvider
     * @param array $article
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testAddNews(array $article)
    {
        $result = $this->sdk->material->addNews($article);
        self::assertArrayHasKey('media_id', $result);
    }


    /**
     * @return array
     */
    public function addNewsProvider()
    {
        return [
            [
                [
                    "title" => "webpack深入浅出使用教程",
                    "author" => "meShell",
                    "digest" => "webpack深入浅出使用教程",
                    "show_cover_pic" => 1,
                    "content" => "Webpack是一个模块打包器。其主要目的是将JavaScript文件捆绑在浏览器中使" .
                        "用，但它也能够转换，捆绑或打包任何资源或资产，使用依赖关系的模块并生成代表这些模块的静态形式(.js, .css, .png)，" .
                        "Webpack是运行在Node.js环境中，它是模块化解决方案的一种。",
                    "content_source_url" => "http://loocode.com",
                    'thumb_media_id' => 'oTri6FpR8VXeRukzyRkMk_mbeFK6WKlM-mdKAT72lpc',
                ]
            ]
        ];
    }
}
