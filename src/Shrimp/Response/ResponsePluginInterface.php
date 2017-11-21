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
    public function getResponse($package): ResponseInterface;
    public function type(): string;
    public function name(): string;
}