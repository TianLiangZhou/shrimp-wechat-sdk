<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/6
 * Time: 13:28
 */

namespace Shrimp\Response;


interface ResponsePluginInterface
{
    /**
     * @param $package
     * @return ResponseInterface
     */
    public function getResponse($package);
    public function type();
    public function name();
}