<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 16:57
 */
declare(strict_types=1);

namespace Shrimp;

use Closure;
use CURLFile;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use ReflectionClass;
use RuntimeException;
use Shrimp\Api\Card;
use Shrimp\Api\CustomService;
use Shrimp\Api\Datacube;
use Shrimp\Api\Material;
use Shrimp\Api\Menu;
use Shrimp\Api\Message;
use Shrimp\Api\Template;
use Shrimp\Event\ResponseEvent;
use Shrimp\Message\Event;
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
 * @property CustomService $service
 * @property Template $template
 * Class ShrimpWechat
 * @package Shrimp
 */
class ShrimpWechat
{
    const VERSION = 'v2.0.0';

    /**
     * @var string
     */
    private $gateway = 'https://api.weixin.qq.com/';
    /**
     * @var string
     */
    private $appId;
    /**
     * @var string
     */
    private $secret;

    /**
     * @var int
     */
    private $timeout = 3;

    /**
     * @var string
     */
    private $userAgent = 'shrimp-wechat-sdk ' . self::VERSION;


    private $accessToken = null;

    /**
     * @var array
     */
    private $modules = [];

    /**
     * @var null|EventDispatcher
     */
    private static $dispatcher = null;

    /**
     * @var null|Closure
     */
    private $writeAccessTokenCallable = null;

    /**
     * @var null|Closure
     */
    private $readAccessTokenCallable = null;

    /**
     * @var string
     */
    private $defaultCacheDir;

    /**
     * @var ClientInterface|\GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * ShrimpWechat constructor.
     * @param string $appId
     * @param string $secret
     * @param array $options
     */
    public function __construct(string $appId, string $secret, array $options = [])
    {
        $this->appId = $appId;
        $this->secret = $secret;
        if (empty($options['cacheDir'])) {
            $this->defaultCacheDir = __DIR__ . '/../';
        }
        self::$dispatcher = new EventDispatcher();
        if (empty($options['client'])) {
            $options['client'] = new Client($options['config'] ?? []);
        }
        $this->setClient($options['client']);
        $this->registerWriteAccessTokenCallback(Closure::fromCallable([$this, 'defaultAccessTokenWriteCallback']));
        $this->registerReadAccessTokenCallback(Closure::fromCallable([$this, 'defaultAccessTokenReadCallback']));
    }

    /**
     * 获取事件调度器
     *
     * @return EventDispatcher|null
     */
    public function getDispatcher(): ?EventDispatcher
    {
        return self::$dispatcher;
    }

    /**
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    /**
     * @param string $accessToken
     * @param int $expire
     * @return bool
     */
    private function defaultAccessTokenWriteCallback(string $accessToken, int $expire)
    {
        if (!is_writable($this->defaultCacheDir)) {
            throw new RuntimeException(sprintf("Directory is not writable '%s'", realpath(__DIR__ . '/../../')));
        }
        $filename = $this->defaultCacheDir . DIRECTORY_SEPARATOR . '.shrimp.cache';
        file_put_contents($filename, serialize([$accessToken, $expire, time()]));
        return true;
    }

    /**
     * @return string
     */
    private function defaultAccessTokenReadCallback()
    {
        $filename = $this->defaultCacheDir . DIRECTORY_SEPARATOR . '.shrimp.cache';
        if (file_exists($filename)) {
            list($accessToken, $expire, $time) = unserialize(file_get_contents($filename));
            if (time() > $time + $expire) {
                return null;
            }
            return $accessToken;
        }
        return null;
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
        $sign = sha1(implode($params));
        if ($sign == $query['signature']) {
            return true;
        }
        return false;
    }

    /**
     * @see https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Get_access_token.html
     * @return $this
     * @throws \Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function requestAccessToken()
    {
        $uri = $this->gateway  . 'cgi-bin/token';
        $data = [
            'grant_type' => 'client_credential',
            'appid' => $this->appId,
            'secret'=> $this->secret,
        ];
        try {
            $response = $this->returnResponseHandler($this->http($uri, 'GET', ['query' => $data]));
        } catch (Exception $e) {
            throw $e;
        }
        call_user_func(
            $this->writeAccessTokenCallable ?? Closure::fromCallable([$this, 'setAccessToken']),
            $response['access_token'],
            $response['expires_in']
        );
        return $this;
    }


    /**
     * @see https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Get_the_WeChat_server_IP_address.html
     * @return mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getCallbackIp()
    {
        if (empty($this->getAccessToken())) {
            throw new Exception('AccessToken is empty');
        }
        $uri = $this->gateway . 'cgi-bin/getcallbackip?access_token=' . $this->accessToken;
        return $this->returnResponseHandler($this->http($uri));
    }

    /**
     * 清理调用次数
     *
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws Exception
     */
    public function clearQuota()
    {
        if (empty($this->getAccessToken())) {
            throw new Exception('AccessToken is empty');
        }
        $uri = $this->gateway . 'cgi-bin/clear_quota?access_token=' . $this->accessToken;
        return $this->returnResponseHandler($this->http($uri, 'POST', [
            'json' => ['appid' => $this->appId]
        ]));
    }

    /**
     * @param $name
     * @param $listener
     * @param int $priority
     * @return ShrimpWechat
     */
    public function bind($listener, $name = Event::TEXT, $priority = 0)
    {
        if (!is_callable($listener)) {
            throw new \InvalidArgumentException("$listener is not a Closure or invokable object.");
        }
        self::$dispatcher->addListener($name, $listener, $priority);
        return $this;
    }

    /**
     * 注册请求的accessToken之后写入的回调
     *
     * @param callable $callback
     * @return $this
     */
    public function registerWriteAccessTokenCallback(callable $callback)
    {
        $this->writeAccessTokenCallable = $callback;

        return $this;
    }

    /**
     * 注册获取accessToken的回调
     *
     * @param callable $callback
     * @return $this
     */
    public function registerReadAccessTokenCallback(callable $callback)
    {
        $this->readAccessTokenCallable = $callback;

        return $this;
    }


    /**
     * 自动回复
     *
     * @return string
     */
    public function send()
    {
        $xmlMessage = Support\Xml::simple($this->getCurrentStream());
        if (empty($xmlMessage)) {
            return 'success';
        }
        if (!property_exists($xmlMessage, 'MsgType')) {
            return 'success';
        }
        $type = (string) $xmlMessage->MsgType;
        $eventName = $type;
        if ($type === Event::EVENT) {
            $eventName= Event::EVENT . '.' . (string) $xmlMessage->Event;
        }
        $event = new ResponseEvent($xmlMessage);
        self::$dispatcher->dispatch($event, $eventName);
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
        $fd = php_sapi_name() === 'cli' ? STDIN : fopen('php://input', 'r');
        $stream = stream_get_contents($fd);
        fclose($fd);
        return $stream;
    }

    /**
     * @param array $file
     * @return CURLFile|string
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
        if ($this->accessToken) {
            return $this->accessToken;
        }
        if ($this->readAccessTokenCallable) {
            $this->accessToken = call_user_func($this->readAccessTokenCallable);
        }
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
     * @return ShrimpWechat
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
     * @throws \Psr\Http\Client\ClientExceptionInterface
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
     * @throws \Psr\Http\Client\ClientExceptionInterface
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
     * @param string $uri
     * @param string $method
     * @param array $options
     * @return array
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws Exception
     */
    public function http(string $uri, string $method = 'GET', array $options = [])
    {
        if (empty($options['timeout'])) {
            $options['timeout'] = $this->timeout;
        }
        if (empty($options['headers'])) {
            $options['headers'] = [];
        }
        if (!empty($options['json'])) {
            $options['body'] = json_encode($options['json'], JSON_UNESCAPED_UNICODE);
            $options['headers']['content-type'] = 'application/json';
            unset($options['json']);
        }
        $options['headers']['User-Agent'] = $this->userAgent;
        $response = $this->client->request($method, $uri, $options);
        if ($response->getStatusCode() !== 200) {
            throw new Exception($response->getBody()->getContents());
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function getModule($name)
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
            case 'service':
                $module = new CustomService();
                break;
            case 'template':
                $module = new Template();
                break;
        }
        return $module;
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->getModule($name);
    }

    /**
     *
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
}
