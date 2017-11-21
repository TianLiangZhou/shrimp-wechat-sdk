<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 9:12
 */

namespace Shrimp\Response;


abstract class AbstractResponse
{
    protected $package = null;

    protected $content = null;

    protected $requestTime = null;
    public function __construct($package)
    {
        $this->package = $package;

        $this->requestTime = $_SERVER['REQUEST_TIME'];
    }
}