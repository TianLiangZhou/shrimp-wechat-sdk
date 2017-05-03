<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 23:44
 */

namespace Bmwxin;


class Response
{
    private $content = null;

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        if ($this->content) {
            echo $this->content;
        } else {
            echo 'success';
        }
    }
}