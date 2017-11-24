<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/24
 * Time: 9:33
 */

class MessageTest extends \PHPUnit\Framework\TestCase
{

    private $sdk = null;
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->sdk = Shrimp\MpSDK::getInstance('wx983dd48be764e9ce','26b8ccf343bddeecd0402e1b864d2dd4');
    }


    /**
     * @dataProvider massForTagProvider
     *
     * @param $mediaId
     */
    public function testMassForTag($mediaId)
    {
        $result = $this->sdk->message->massForTag($mediaId, \Shrimp\Api\Message::TYPE_NEWS);
        $this->assertArrayHasKey('msg_id', $result);
    }

    /**
     * @return array
     */
    public function massForTagProvider()
    {
        return [
            ['oxYTmqZ_fqeXmivUS1lK4r3xoXWlMxK9xep-P92_Q7I'],
            ['oxYTmqZ_fqeXmivUS1lK4hWRLx-aJL5KnLltds65t_Q']
        ];
    }
}