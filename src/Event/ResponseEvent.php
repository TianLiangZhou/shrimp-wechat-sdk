<?php
/**
 * Created by PhpStorm.
 * User: meshell
 * Date: 2018/1/5
 * Time: 11:17
 */

declare(strict_types=1);

namespace Shrimp\Event;

use Shrimp\Response\Response;
use Shrimp\Response\TextResponse;
use SimpleXMLElement;
use Symfony\Component\EventDispatcher\GenericEvent;

class ResponseEvent extends GenericEvent
{
    /**
     * @var null|Response
     */
    private $response = null;

    /**
     * GetResponseEvent constructor.
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml, []);
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
                $this->response = new $class($this->subject, $content);
            }
        }
        if (is_string($response)) {
            $this->response = new TextResponse($this->subject, $response);
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
     * @return null|SimpleXMLElement
     */
    public function getMessageSource()
    {
        return $this->subject;
    }

    /**
     * @param $name
     * @return SimpleXMLElement
     */
    public function getAttribute($name)
    {
        return $this->subject->$name;
    }
}
