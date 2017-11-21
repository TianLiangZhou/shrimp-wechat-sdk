<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/3
 * Time: 16:57
 */

namespace Shrimp;

use Exception;

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


    const MEDIA_IMAGE = 'image';

    const MEDIA_VOICE = 'voice';

    const MEDIA_VIDEO = 'video';

    const MEDIA_THUMB = 'thumb';

    const MEDIA_NEWS  = 'news';

    const FILE_MEDIA_TYPE = [
        self::MEDIA_IMAGE, self::MEDIA_VOICE, self::MEDIA_VIDEO, self::MEDIA_THUMB, self::MEDIA_NEWS
    ];

    /**
     * 
     */
    private $mediaTypeExt = [
        self::MEDIA_IMAGE => ['bmp', 'png', 'jpeg', 'jpg', 'gif'],
        self::MEDIA_VOICE => ['mp3', 'wma', 'wav', 'amr'],
        self::MEDIA_VIDEO => ['mp4'],
        self::MEDIA_THUMB => ['jpg'],
    ];

    /**
     * 
     */
    private $mediaFileSize = [
        self::MEDIA_IMAGE => 2 * 1048576,
        self::MEDIA_VOICE => 2 * 1048576,
        self::MEDIA_VIDEO => 10 * 1048576,
        self::MEDIA_THUMB => 64 * 1024,
    ];

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
     */
    public function getCallbackIp()
    {
        if (empty($this->accessToken)) {
            throw new \Exception('AccessToken is empty');
        }
        $uri = $this->gateway . 'getcallbackip?access_token=' . $this->accessToken;
        return $this->returnResponseHandler($this->http($uri));
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
            $response = $this->http($uri, $requestData, 'POST', 'json');
        } catch (\Exception $e) {
            throw $e;
        }
        return $this->returnResponseHandler($response);
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
            $response = $this->http($uri);
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
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
            $response = $this->http($uri);
        } catch (\Exception $e) {
            throw $e;
        }
        return $this->returnResponseHandler($response);
    }

    /**
     * 获取临时素材的URL
     * @var string $mediaId
     * @return string|null
     */
    public function getMaterialUrl($mediaId)
    {
        $uri = $this->gateway . 'media/get?access_token=' . $this->accessToken . '&media_id=' . $mediaId;
        try {
            $response = $this->returnResponseHandler($this->http($uri));
        } catch (\Exception $exception) {
            throw $exception;
        }
        foreach (self::FILE_MEDIA_TYPE as $value) {
            if (isset($response[$value . '_url'])) {
                return $response[$value. '_url'];   
            }
        }
        return null;
    }

    /**
     * 上传临时素材文件
     * @param MediaFile $file
     * @param string $type
     * @return array
     * @throws Exception
     */
    public function uploadMaterial(MediaFile $files, $type = null)
    {
        try {
            $file = $this->uploadFileChecked($files, $type);
        } catch(Exception $e) {
            throw $e;
        }
        $data['media'] = $this->createFile($file->getFile);
        $uri = $this->gateway . 'media/upload?access_token=' . $this->accessToken . '&type=' . $type;
        try {
            $response = $this->http($uri, $data, 'POST', 'form');
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->returnResponseHandler($response);
    }

    /**
     * 获取永久素材列表
     * @param string $type
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getPermanentMaterial($type = self::MEDIA_NEWS, $offset = 0, $limit = 20)
    {
        if (!in_array($type, self::FILE_MEDIA_TYPE)) {
            throw new Exception("错误的素材类型");
        }
        $uri = $this->gateway . 'material/batchget_material?access_token=' . $this->accessToken;
        try {
            $response = $this->returnResponseHandler($this->http(
                $uri, ['type' => $type, 'offset' => $offset, 'count' => $limit], 'POST', 'json'
            ));
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $response;
    }

    /**
     * 获取永久素材总数
     * @return array
     */
    public function getPermanentMaterialCount()
    {
        $uri = $this->gateway . 'material/get_materialcount?access_token=' . $this->accessToken;
        try {
            $response = $this->returnResponseHandler($this->http($uri));
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $response;
    }

    /**
     * 获取永久素材的详情, 如果是图片素材将返回其内容
     * @param int $mediaId
     * @return string|null
     */
    public function getPermanentMaterialDetail($mediaId)
    {
        $uri = $this->gateway . 'material/get_material?access_token=' . $this->accessToken;
        try {
            $response = $this->returnResponseHandler($this->http($uri, ['media_id' => $mediaId], 'POST', 'json'));
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $response;
    }
   

    /**
     * 上传永久素材
     * @param MediaFile $file
     * @param string $type
     * @return array|mixed
     * @throws Exception
     */
    public function uploadPermanentMaterial(MediaFile $files, $type = null)
    {
        try {
            $file = $this->uploadFileChecked($files, $type);
        } catch(Exception $e) {
            throw $e;
        }
        $data['media'] = $this->createFile($file->getFile());
        $uri = $this->gateway . 'material/add_material?access_token=' . $this->accessToken . '&type=' . $type;
        try {
            $response = $this->http($uri, $data, 'POST', 'form');
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->returnResponseHandler($response);
    }

    /**
     * 删除永久素材
     * @param int $mediaId
     * @return array
     */
    public function deletePermanentMaterial($mediaId)
    {
        $uri = $this->gateway . 'material/del_material?access_token=' . $this->accessToken;
        try {
            $response = $this->returnResponseHandler($this->http($uri, ['media_id' => $mediaId], 'POST', 'json'));
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $response;
    }

    /**
     * 上传图文素材中的图片
     * @param array $file
     * @return array|mixed
     * @throws Exception
     */
    public function uploadMaterialImage(MediaFile $files)
    {
        $response = [];
        foreach ($files as $file) {
            $file = $files->current();
            $ext = $file->getExtName();
            if (!in_array($ext, ['jpg', 'png'])) {
                $response[] = new Exception("图片类型只能是jpg/png格式");
                continue;
            }
            if ($file->getSize() > 1048576) {
                $response[] = new Exception('超出文件大小范围，不能超过1MB');
                continue;
            }
            $data['media'] = $this->createFile($file);
            $uri = $this->gateway . 'media/uploadimg?access_token=' . $this->accessToken;
            try {
                $response[] = $this->returnResponseHandler($this->http($uri, $data, 'POST', 'form'));
            } catch (\Exception $e) {
                $response[] = $e;
            }
        }
        return $response;
    }

    
    /**
     * @return File
     */
    private function uploadFileChecked(MediaFile $files, $type = null)
    {
        /**
         * @var File $file
         */
        $file = $files->current();
        if ($file === null) {
            throw new Exception('error file');
        }
        $ext = $file->getExtName();
        if (empty($type)) {
            foreach($this->mediaTypeExt as $wechatType => $mediaTypeExt) {
                if (in_array($ext, $mediaTypeExt)) {
                    $type = $wechatType;
                    break;
                }
            }
        }
        if ($file->getMediaType() !== $file->getType()) {
            throw new Exception('文件mine_type与实际的不符');
        }
        if (empty($type)) {
            throw new Exception('通过mine_type无法查找到对的资源类型');
        }
        if (!in_array($type, self::FILE_MEDIA_TYPE)) {
            throw new Exception('无效的资源类型');
        }
        if ($this->mediaFileSize[$type] < ($size = $file->getSize())) {
            $unit = $this->mediaFileSize[$type] >= 1048576
                    ? ($this->mediaFileSize[$type] / 1048576) . 'MB'
                    : ($this->mediaFileSize[$type] / 1024) . 'KB';
            throw new Exception('超出文件大小范围，不能超过' . $unit);
        }
        return $file;
    }

    /**
     * 新增图文永久素材文章
     * @param array $content
     * @param array $file
     * @return array|mixed
     * @throws Exception
     */
    public function createPictureContent(array $content, MediaFile $files)
    {
        if (empty($content['title']) || empty($content['content']) || empty($content['content_source_url'])) {
            throw new Exception("缺少必填参数");
        }
        try {
            $response = $this->uploadPermanentMaterial($files, self::MEDIA_THUMB);
        }catch (Exception $e) {
            throw $e;
        }
        $content['thumb_media_id'] = $response['media_id'];
        $uri = $this->gateway . 'material/add_news?access_token=' . $this->accessToken;
        $data['articles'] = [$content];
        try {
            $response = $this->http($uri, $data, 'POST', 'form');
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->returnResponseHandler($response);
    }

    /**
     * @param array $file
     * @return \CURLFile|string
     */
    private function createFile(array $file)
    {
        $object = '@' . $file['tmp_name'] . ';type=' .  $file['type'] ?? '' . ';filename=' .  $file['name'] ?? '';
        if (function_exists('curl_file_create')) {
            $object = curl_file_create($file['tmp_name'], $file['type'] ?? '', $file['name'] ?? '');
        }
        return $object;
    }

    /**
     * 创建用户标签
     * @param string $name
     * @return array
     */
    public function createUserLabel($name)
    {
        $uri = $this->gateway . 'tags/create?access_token=' . $this->accessToken;
        $data['tag']['name'] = $name;
        try {
            $response = $this->http($uri, $data, 'POST', 'json');
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->returnResponseHandler($response);
    }

    /**
     * 获取已创建的标签
     * @return array|mixed
     * @throws Exception
     */
    public function getLabel()
    {
        $uri = $this->gateway . 'tags/get?access_token=' . $this->accessToken;
        try {
            $response = $this->http($uri);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->returnResponseHandler($response);
    }

    public function updateLabel()
    {

    }

    public function deleteLabel()
    {

    }



    /**
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }


    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    private function refreshAccessToken(string $method, array $args)
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
    private function returnResponseHandler(array $response)
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