<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 8:41
 */

namespace Bmwxin\Response;


interface ResponseInterface
{
    public function setContent($content);

    public function __toString();
}