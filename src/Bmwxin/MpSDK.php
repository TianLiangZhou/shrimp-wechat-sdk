<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 16:57
 */

namespace Bmwxin;


class MpSDK
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

    public function __construct($appId, $secret)
    {
        $this->appId = $appId;

        $this->secret = $secret;
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
        $result = $this->http($uri, $data);
        if (isset($result['errcode'])) {
            throw new \Exception($result['errmsg'], $result['errcode']);
        }
        $this->accessToken = $result['access_token'];
        return $this;
    }


    /**
     * @return mixed
     */
    public function getCallbackIp()
    {
        if (empty($this->accessToken)) {
            throw new \Exception('AccessToken is empty');
        }
        $uri = $this->gateway . 'getcallbackip?access_token=' . $this->accessToken;
        $result = $this->http($uri);
        return $result;
    }


    /**
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function createMenu(array $data)
    {
        if (empty($this->accessToken)) {
            throw new \Exception('AccessToken is empty');
        }
        $requestData = [];
        if (isset($data[0])) {
            $requestData['button'] = $data;
        } else {
            $requestData['button'][] = $data;
        }
        $uri = $this->gateway . 'menu/create?access_token=' . $this->accessToken;
        try {
            $result = $this->http($uri, $requestData, 'POST', 'json');
        } catch (\Exception $e) {
            throw $e;
        }
        if ($result['errcode'] !== 0) {
            throw new \Exception($result['errmsg']);
        }
        return true;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function menuQuery()
    {
        if (empty($this->accessToken)) {
            throw new \Exception('AccessToken is empty');
        }
        $uri = $this->gateway . 'menu/get?access_token=' . $this->accessToken;
        try {
            $result = $this->http($uri);
        } catch (\Exception $e) {
            throw $e;
        }
        return $result;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteMenu()
    {
        if (empty($this->accessToken)) {
            throw new \Exception('AccessToken is empty');
        }
        $uri = $this->gateway . 'menu/delete?access_token=' . $this->accessToken;
        try {
            $result = $this->http($uri);
        } catch (\Exception $e) {
            throw $e;
        }
        if ($result['errcode'] !== 0) {
            throw new \Exception($result['errmsg']);
        }
        return true;
    }


    /**
     * @param $file
     * @param $type
     * @return array
     */
    public function tempMaterialCreate(array $file, $type)
    {
        if (!in_array($type, ['image', 'voice', 'video', 'thumb'])) {
            throw new \TypeError('error type');
        }
        $data = [];
        $uri = $this->gateway . 'media/upload?access_token=' . $this->accessToken . '&type=' . $type;
        $data['media'] = $this->createFile($file);

        try {
            $result = $this->http($uri, $data, 'POST', 'form');
        } catch (\Exception $exception) {
            throw $exception;
        }
        if (isset($result['errcode'])) {
            throw new \Exception($result['errmsg']);
        }
        return $result;
    }

    /**
     * @param array $file
     * @return \CURLFile|null|string
     */
    private function createFile(array $file)
    {
        $object = null;
        if (function_exists('curl_file_create')) {
            $object = curl_file_create($file['tmp_name'], $file['type'] ?? '', $file['name'] ?? '');
        } else {
            $object = '@' . $file['tmp_name'] . ';type=' .  $file['type'] ?? '' . ';filename=' .  $file['name'] ?? '';
        }
        return $object;
    }

    /**
     * @param $file
     * @return array
     */
    public function lastingMaterialCreate($file)
    {
        return [];
    }


    /**
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * 设置accessToken
     * @param $accessToken
     * @return $this
     */
    public function setAccessToken($accessToken)
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

    private function http($uri, array $data = [], $method = 'GET', $contentType = 'html', $header = [])
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
}