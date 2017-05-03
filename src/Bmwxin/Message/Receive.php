<?php
namespace Bmwxin\Message;

use SimpleXMLElement;

/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 16:38
 */
class Receive
{
    private $package = null;

    private $type = null;

    private $toUser = null;

    private $fromUser = null;

    public function __construct(SimpleXMLElement $package)
    {
        $this->package = $package;
        $this->analyze();
    }


    private function analyze()
    {
        $this->type = $this->package->MsgType;

        $this->toUser = $this->package->ToUserName;

        $this->fromUser = $this->package->FromUserName;

        if ($this->type == 'event') {

        }
    }


}