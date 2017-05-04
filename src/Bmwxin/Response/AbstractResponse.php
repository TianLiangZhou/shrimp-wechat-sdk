<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 9:12
 */

namespace Bmwxin\Response;


abstract class AbstractResponse
{
    protected $package = null;

    protected $content = null;
    public function __construct($package)
    {
        $this->package = $package;
    }
}