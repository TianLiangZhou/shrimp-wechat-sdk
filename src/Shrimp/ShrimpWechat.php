<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 16:57
 */

namespace Shrimp;

use Exception;
use ReflectionClass;
use Shrimp\Api\Card;
use Shrimp\Api\Datacube;
use Shrimp\Api\Material;
use Shrimp\Api\Menu;
use Shrimp\Api\Message;
use Shrimp\Message\Type;
use Shrimp\Api\Qrcode;
use Shrimp\Api\User;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @property Material $material
 * @property Menu $menu
 * @property User $user
 * @property Message $message
 * @property Card $card
 * @property Qrcode $qrcode
 * @property Datacube $datacube
 * Class ShrimpWechat
 * @package Shrimp
 */
class ShrimpWechat
{
    /**
     * @var string
     */
    private $gateway = 'https://api.weixin.qq.com/cgi-bin/';
    /**
     * @var string
     */
    private $appId = '';
    /**
     * @var string
     */
    private $secret = '';

    /**
     * @var int
     */
    private $timeout = 3;

    /**
     * @var string
     */
    private $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36';


    private $accessToken = null;

    /**
     * @var array
     */
    private $modules = [];

    private static $dispatcher = null;
    /**
     * ShrimpWechat constructor.
     * @param $appId
     * @param $secret
     */
    public function __construct($appId, $secret)
    {
        $this->appId = $appId;
        $this->secret = $secret;
        self::$dispatcher = new EventDispatcher();
    }

    /**
     * 验证微信请求
     * @param $token
     * @param array $query
     * @return bool
     */
    public static function verifyRequest($token, array $query)
    {
        if (empty($token) || empty($query)) {
            return false;
        }
        $params = [$token, $query['timestamp'], $query['nonce']];
        sort($params, SORT_STRING);
        $sign = sha1(implode($params, ''));
        if ($sign == $query['signature']) {
            return true;
        }
        return false;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function requestAccessToken()
    {
        $uri = $this->gateway  . 'token';
        $data = [
            'grant_type' => 'client_credential',
            'appid' => $this->appId,
            'secret'=> $this->secret,
        ];
        try {
            $response = $this->returnResponseHandler($this->http($uri, $data));
        } catch (Exception $e) {
            throw $e;
        }
        $this->setAccessToken($response['access_token']);
        return $this;
    }


    /**
     * @return mixed
     * @throws Exception
     */
    public function getCallbackIp()
    {
        if (empty($this->accessToken)) {
            throw new Exception('AccessToken is empty');
        }
        $uri = $this->gateway . 'getcallbackip?access_token=' . $this->accessToken;
        return $this->returnResponseHandler($this->http($uri));
    }

    /**
     * @param $name
     * @param $listener
     * @param int $priority
     */
    public function bind($listener, $name = Type::TEXT, $priority = 0)
    {
        if (!is_callable($listener)) {
            throw new \InvalidArgumentException("$listener is not a Closure or invokable object.");
        }
        self::$dispatcher->addListener($name, $listener, $priority);
    }

    /**
     * @return string
     */
    public function send()
    {
        $xmlMessage = $this->messageToXml($this->getCurrentStream());
        if (empty($xmlMessage)) {
            return 'success';
        }
        if (!property_exists($xmlMessage, 'MsgType')) {
            return 'success';
        }
        $type = (string) $xmlMessage->MsgType;
        $name = $type;
        if ($type === Type::EVENT) {
            $name = (string) $xmlMessage->Event;
        }
        $event = new GetResponseEvent($xmlMessage);
        self::$dispatcher->dispatch($name, $event);
        if ($event->hasResponse()) {
            return (string) $event->getResponse();
        }
        return 'success';
    }

    /**
     * @return bool|string
     */
    private function getCurrentStream()
    {
        if (php_sapi_name() === 'cli') {
            $fd = STDIN;
        } else {
            $fd = fopen('php://input', 'r');
        }
        $stream = stream_get_contents($fd);
        fclose($fd);
        return $stream;
    }

    /**
     * @param $input
     * @return null|\SimpleXMLElement
     */
    private function messageToXml($input)
    {
        $backup = libxml_disable_entity_loader(true);
        $backup_errors = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($input);
        libxml_disable_entity_loader($backup);
        libxml_clear_errors();
        libxml_use_internal_errors($backup_errors);
        if ($xml === false) {
            return null;
        }
        return $xml;
    }

    /**
     * @param array $file
     * @return \CURLFile|string
     */
    public function createFile(array $file)
    {
        $object = '@' . $file['tmp_name'] . ';type=' .  $file['type'] ?? '' . ';filename=' .  $file['name'] ?? '';
        if (function_exists('curl_file_create')) {
            $object = curl_file_create($file['tmp_name'], $file['type'] ?? '', $file['name'] ?? '');
        }
        return $object;
    }

    /**
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }


    /**
     * @return string
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param $uri
     */
    public function setGateway($uri)
    {
        $this->gateway = $uri;
        return $this;
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     * @throws Exception
     */
    public function refreshAccessToken(string $method, array $args)
    {
        $this->requestAccessToken();
        return call_user_func_array([
            $this, $method
        ], $args);
    }

    /**
     * @param array $response
     * @return array|mixed
     * @throws Exception
     */
    public function returnResponseHandler(array $response)
    {
        if (isset($response['errcode'])) {
            if ($response['errcode'] === 42001) {
                $trace = debug_backtrace()[1];
                return $this->refreshAccessToken($trace['function'], $trace['args']);
            }
            if ($response['errcode'] === 0) {
                return $response;
            }
            throw new Exception($response['errmsg']);
        }
        return $response;
    }

    /**
     * 设置accessToken
     * @param $accessToken
     * @return $this
     */
    private function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @param $uri
     * @param array $data
     * @param string $method
     * @return mixed
     * @throws \HttpRequestException
     */

    public function http($uri, array $data = [], $method = 'GET', $contentType = 'html', $header = [])
    {
        $curl = curl_init();
        $options = [
            CURLOPT_RETURNTRANSFER => true,         // return web page
            CURLOPT_HEADER         => false,        // don't return headers
            CURLOPT_USERAGENT      => $this->userAgent,     // who am i
            CURLOPT_AUTOREFERER    => true,         // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => $this->timeout,          // timeout on connect
            CURLOPT_TIMEOUT        => $this->timeout,          // timeout on response
            CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
            CURLOPT_SSL_VERIFYPEER => false,        //
        ];
        $requestData = null;
        switch ($contentType) {
            case 'html':
                array_push($header, "Content-Type: text/html; charset=utf-8");
                $requestData = $data ? http_build_query($data) : null;
                break;
            case 'json':
                array_push($header, "Content-Type: application/json");
                $requestData = $data ? json_encode($data, JSON_UNESCAPED_UNICODE) : null;
                break;
            case 'xml':
                array_push($header, "Content-Type: application/xml");
                if ($data) {
                    $xml = new \SimpleXMLElement('<xml/>');
                    array_walk_recursive($data, [$xml, 'addChild']);
                    $requestData = $xml->asXML();
                }
            case 'form':
                array_push($header, "multipart/form-data");
                $requestData = $data ? $data : null;
                break;
        }
        if ($header) {
            $options[CURLOPT_HTTPHEADER] = $header;
        }
        if (strtoupper($method) === 'GET' && $requestData) {
            $uri .= '?' . $requestData;
        }
        if (strtoupper($method) === 'POST') {
            $options[CURLOPT_POST] = 1;            // i am sending post data
            $options[CURLOPT_POSTFIELDS] = $requestData;    // this are my post vars]
        }
        $options[CURLOPT_URL] = $uri;
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new \HttpRequestException(curl_error($curl));
        }
        curl_close($curl);
        return json_decode($response, true);
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function getModule($name)
    {
        if (isset($this->modules[$name])) {
            return $this->modules[$name];
        }
        $module = $this->moduleFactory($name);
        if ($module === null) {
            throw new Exception("Not found '" . $name . "' module");
        }
        $module->setSdk($this);
        $this->modules[$name] = $module;
        if (empty($this->getAccessToken())) {
            $this->requestAccessToken();
        }
        return $this->modules[$name];
    }

    /**
     * @param $name
     * @return null|Material|Menu|Message|User
     */
    private function moduleFactory($name)
    {
        $module = null;
        switch ($name) {
            case 'menu':
                $module = new Menu();
                break;
            case 'user':
                $module = new User();
                break;
            case 'material':
                $module = new Material();
                break;
            case 'message':
                $module = new Message();
                break;
            case 'card':
                $module = new Card();
                break;
            case 'qrcode':
                $module = new Qrcode();
                break;
            case 'datacube':
                $module = new Datacube();
                break;
        }
        return $module;
    }
    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->getModule($name);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \ErrorException
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $module = $this->reflectionModules($name);
        if ($module === null) {
            throw new \ErrorException("调未用户定义的方法");
        }
        return call_user_func_array([$this->getModule($module), $name], $arguments);
    }

    /**
     * @param $name
     * @return null|string
     */
    private function reflectionModules($name)
    {
        $class = [
            User::class,
            Menu::class,
            Material::class,
            Card::class,
            Datacube::class,
            Menu::class,
            Message::class,
        ];
        foreach ($class as $cls) {
            $reflection = new ReflectionClass($cls);
            if ($reflection->hasMethod($name)) {
                return strtolower($reflection->getShortName());
            }
        }
        return null;
    }

    /**
     *
     */
    public function __clone()
    {
        // TODO: Implement __clone() method.
    }
}