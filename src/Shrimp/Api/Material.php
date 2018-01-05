<?php
namespace Shrimp\Api;
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/21
 * Time: 16:57
 */
use Exception;
use Shrimp\File;
use Shrimp\MediaFile;

class Material extends Base
{

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

    /**
     *
     * 获取临时素材的URL
     * @var string $mediaId
     * @return string|null
     * @throws Exception
     */
    public function getMaterialUrl($mediaId)
    {
        $uri = $this->format('get', true, 'media') . '&media_id=' . $mediaId;
        try {
            $response = $this->sdk->returnResponseHandler($this->sdk->http($uri));
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
            list($file, $type) = $this->uploadFileChecked($files, $type);
        } catch(Exception $e) {
            throw $e;
        }
        $data['media'] = $this->sdk->createFile($file->getFile);
        $uri = $this->format('upload', true, 'media') . '&type=' . $type;
        try {
            $response = $this->sdk->http($uri, $data, 'POST', 'form');
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 获取永久素材列表
     * @param string $type
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws Exception
     */
    public function getPermanentMaterial($type = self::MEDIA_NEWS, $offset = 0, $limit = 20)
    {
        if (!in_array($type, self::FILE_MEDIA_TYPE)) {
            throw new Exception("错误的素材类型");
        }
        $uri = $this->format('batchget_material');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http(
                    $uri, ['type' => $type, 'offset' => $offset, 'count' => $limit], 'POST', 'json'
                )
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $response;
    }

    /**
     * 获取永久素材总数
     * @return array
     * @throws Exception
     */
    public function getPermanentMaterialCount()
    {
        $uri = $this->format('get_materialcount');
        try {
            $response = $this->sdk->returnResponseHandler($this->sdk->http($uri));
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $response;
    }

    /**
     * 获取永久素材的详情, 如果是图片素材将返回其内容
     * @param int $mediaId
     * @return string|null
     * @throws Exception
     */
    public function getPermanentMaterialDetail($mediaId)
    {
        $uri = $this->format('get_material');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, ['media_id' => $mediaId], 'POST', 'json')
            );
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
    public function uploadPermanentMaterial(MediaFile $files, $type = null, $paramters = [])
    {
        try {
            list($file, $type) = $this->uploadFileChecked($files, $type);
        } catch(Exception $e) {
            throw $e;
        }
        $data['media'] = $this->sdk->createFile($file->getFile());
        $uri = $this->format('add_material') . '&type=' . $type;
        if ($type === 'video') {
            $data['title'] = $paramters['title'];
            $data['introduction'] = $paramters['description'];
        }
        try {
            $response = $this->sdk->http($uri, $data, 'POST', 'form');
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 删除永久素材
     * @param int $mediaId
     * @return array
     * @throws Exception
     */
    public function deletePermanentMaterial($mediaId)
    {
        $uri = $this->format('del_material');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, ['media_id' => $mediaId], 'POST', 'json')
            );
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
            $ext = $file->getExtName();
            if (!in_array($ext, ['jpg', 'png'])) {
                $response[] = new Exception("图片类型只能是jpg/png格式");
                continue;
            }
            if ($file->getSize() > 1048576) {
                $response[] = new Exception('超出文件大小范围，不能超过1MB');
                continue;
            }
            $data['media'] = $this->sdk->createFile($file->getFile());
            $uri = $this->format('uploadimg', true, 'media');
            try {
                $response[] = $this->sdk->returnResponseHandler(
                    $this->sdk->http($uri, $data, 'POST', 'form')
                );
            } catch (\Exception $e) {
                $response[] = $e;
            }
        }
        return $response;
    }


    /**
     * @param MediaFile $files
     * @param null $type
     * @return array
     * @throws Exception
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
        return [$file, $type];
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
        $uri = $this->format('add_news');
        $data['articles'] = [$content];
        try {
            $response = $this->sdk->http($uri, $data, 'POST', 'json');
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }
}