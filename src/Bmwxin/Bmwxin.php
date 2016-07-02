<?php

namespace Bmwxin;
/**
 * Class Bmwxin
 * @package Bmwxin
 */
class Bmwxin
{
    /**
     * @var string
     */
    protected $gateway = 'https://api.weixin.qq.com/cgi-bin/';
    /**
     * @var string
     */
    protected $appId = '';
    /**
     * @var string
     */
    protected $secret = '';

    /**
     * @var int
     */
    protected $timeout = 3;

    /**
     * @var string
     */
    protected $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 ' .
    '(KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36';

    /**
     * @var array
     */
    protected $interface = [
        'token' => 'token',
        'ip' => 'getcallbackip',
        'userinfo' => 'user/info',
        'username' => 'user/info/updateremark',
        'tag' => 'tags/create',
        'users'=> 'user/get',
        'qrcode' => 'qrcode/create',
        'shorturl' => 'shorturl',
        'menu' => 'menu/create',
        'diffmenu' => 'menu/addconditional',
        'material' => 'material/add_news',
        'file' => 'media/upload'
    ];
    /**
     * Bmwxin constructor.
     * @param $gateway
     * @param $appId
     * @param $secret
     */
    public function __construct($gateway, $appId, $secret)
    {
        $this->gateway = $gateway;
        $this->appId = $appId;
        $this->secret = $secret;
    }

    public function receiveMessage()
    {

    }

    public function replyMessage($type, $message)
    {

    }
    public function getAccessToken($callback = null)
    {
        if ($callback && !is_callable($callback)) {
            throw new \InvalidArgumentException('$callback must be Closure or implements __invoke method');
        }
        $args = [
            'grant_type' => 'client_credential',
            'appid' => $this->appId,
            'secret' => $this->secret,
        ];
        $this->http('GET', $args);
    }

    protected function verifyWeixinRequest()
    {

    }

    /**
     * @param string $method
     * @param array $postData
     * @return mixed
     */
    protected function http($method = "GET", array $postData = [])
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
        $url = $this->gateway;
        if (strtoupper($method) === 'GET' && $postData) {
            $url .= '?' . http_build_query($postData);
        }
        if (strtoupper($method) === 'POST') {
            $options[CURLOPT_POST] = 1;            // i am sending post data
            $options[CURLOPT_POSTFIELDS] = http_build_query($postData);    // this are my post vars]
        }
        $options[CURLOPT_URL] = $url;
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }


}