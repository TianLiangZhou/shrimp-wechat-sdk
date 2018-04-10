<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2018/1/5
 * Time: 11:17
 */

namespace Shrimp;

use Shrimp\Response\Response;
use Shrimp\Response\TextResponse;
use Symfony\Component\EventDispatcher\Event;

class GetResponseEvent extends Event
{
    /**
     * @var null|\SimpleXMLElement
     */
    private $xml = null;

    /**
     * @var null|Response
     */
    private $response = null;

    /**
     * GetResponseEvent constructor.
     * @param \SimpleXMLElement $xml
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    /**
     * @return null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param $response
     */
    public function setResponse($response)
    {
        if ($response instanceof Response) {
            $this->response = $response;
        }
        if (is_array($response)) {
            $type = null;
            $content = null;
            if (isset($response['type'])) {
                $type = $response['type'];
                $content = $response['content'];
            } else {
                list($type, $content) = $response;
            }
            $class = '\\Shrimp\\Response\\' . ucfirst($type) . 'Response';
            if (class_exists($class)) {
                $this->response = new $class($this->xml, $content);
            }
        }
        if (is_string($response)) {
            $this->response = new TextResponse($this->xml, $response);
        }
    }

    /**
     * @return bool
     */
    public function hasResponse()
    {
        return null !== $this->response;
    }

    /**
     * @return null|\SimpleXMLElement
     */
    public function getMessageSource()
    {
        return $this->xml;
    }

    /**
     * @param $name
     * @return \SimpleXMLElement
     */
    public function getAttribute($name)
    {
        return $this->xml->$name;
    }
}
