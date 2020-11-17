<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 9:12
 */

declare(strict_types=1);

namespace Shrimp\Response;

/**
 * Class AbstractResponse
 * @package Shrimp\Response
 */
abstract class Response implements ResponseInterface
{
    protected $source = null;

    protected $content = null;

    protected $currentTime = null;

    /**
     * AbstractResponse constructor.
     * @param $source
     * @param $content
     */
    public function __construct(\SimpleXMLElement $source, $content)
    {
        $this->source = $source;

        $this->currentTime = time();

        $this->setContent($content);
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
