<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/24
 * Time: 9:33
 */

namespace Shrimp\Test\Message;

use Exception;
use Shrimp\Api\Message;
use Shrimp\Test\TestCase;

class MessageTest extends TestCase
{

    /**
     * @dataProvider massForTagProvider
     *
     * @param $mediaId
     * @throws Exception
     */
    public function testMassSendAll($mediaId)
    {
        $this->expectException(Exception::class);
        $this->sdk->message->massSendAll(Message::TYPE_MPNEWS, ['media_id' => $mediaId]);
    }

    /**
     * @dataProvider massForTagProvider
     *
     * @param $mediaId
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testMassPreview($mediaId)
    {
        $openId = 'oCxkb5jWa2E05Cix81mWqfj5PObk';
        $result = $this->sdk->message->massPreview(Message::TYPE_MPNEWS, ['media_id' => $mediaId], $openId);
        self::assertEquals('preview success', $result['errmsg']);
    }

    /**
     * @return array
     */
    public function massForTagProvider()
    {
        return [
            ['oTri6FpR8VXeRukzyRkMkwEkysb6Xk7fh4Xox_UUM0U']
        ];
    }
}
