<?php

declare(strict_types=1);

namespace Shrimp\Api;

/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/21
 * Time: 16:57
 */
use Exception;
use Shrimp\File\File;
use Shrimp\File\MediaFile;

/**
 * Class Material
 * @package Shrimp\Api
 */
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
     * 获取永久素材列表
     *
     * @param string $type
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws Exception*@throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/Get_materials_list.html
     */
    public function batchGet(string $type = self::MEDIA_NEWS, int $offset = 0, int $limit = 20)
    {
        if (!in_array($type, self::FILE_MEDIA_TYPE)) {
            throw new Exception("错误的素材类型");
        }
        $uri = $this->format('cgi-bin/material/batchget_material');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http(
                    $uri,
                    'POST',
                    ['json' => ['type' => $type, 'offset' => $offset, 'count' => $limit]]
                )
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $response;
    }

    /**
     * 获取永久素材总数
     *
     * @return array
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/Get_the_total_of_all_materials.html
     */
    public function count()
    {
        $uri = $this->format('cgi-bin/material/get_materialcount');
        try {
            $response = $this->sdk->returnResponseHandler($this->sdk->http($uri));
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $response;
    }

    /**
     * 获取永久素材的详情, 如果是图片素材将返回其内容
     *
     * @param string $mediaId
     * @return string|null
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/Getting_Permanent_Assets.html
     */
    public function get(string $mediaId)
    {
        $uri = $this->format('cgi-bin/material/get_material');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => ['media_id' => $mediaId]])
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $response;
    }


    /**
     * 上传永久素材
     *
     * @param MediaFile $files
     * @param null $type
     * @param array $parameters
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/Adding_Permanent_Assets.html
     */
    public function add(MediaFile $files, $type = null, array $parameters = [])
    {
        try {
            list($file, $type) = $this->uploadFileChecked($files, $type);
        } catch (Exception $e) {
            throw $e;
        }
        $uri = $this->format('cgi-bin/material/add_material') . '&type=' . $type;
        $data = [];
        if ($type === 'video') {
            $data['title'] = $parameters['title'];
            $data['introduction'] = $parameters['description'];
        }
        try {
            $response = $this->sdk->http($uri, 'POST', [
                'multipart' => [
                    [
                        'name' => 'media',
                        'contents' => fopen($file->getTmpname(), 'r'),
                        'filename' => $file->getName(),
                        'type' => $file->getType()
                    ],
                    [
                        'name' => 'description',
                        'contents' => $data ? json_encode($data) : '{}',
                        'headers' => [
                            'content-type' => 'application/json',
                        ],
                    ]
                ],
            ]);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 删除永久素材
     *
     * @param string $mediaId
     * @return array
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws Exception
     * @see https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/Deleting_Permanent_Assets.html
     */
    public function delete(string $mediaId)
    {
        $uri = $this->format('cgi-bin/material/del_material');
        try {
            $response = $this->sdk->returnResponseHandler(
                $this->sdk->http($uri, 'POST', ['json' => ['media_id' => $mediaId]])
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $response;
    }

    /**
     * 新增图文永久素材文章
     *
     * @param array $article
     * @param MediaFile $files
     * @return array|mixed
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Comments_management/Image_Comments_Management_Interface.html
     */
    public function addNews(array $article)
    {
        if (empty($article['title']) ||
            empty($article['content']) ||
            empty($article['content_source_url']) ||
            empty($article['thumb_media_id'])
        ) {
            throw new Exception("缺少必填参数");
        }
        $uri = $this->format('cgi-bin/material/add_news');
        $data['articles'] = [$article];
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => $data]);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }


    /**
     * 新增图文永久素材文章
     *
     * @param string $mediaId
     * @param array $article
     * @param int $index
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws Exception
     * @see https://developers.weixin.qq.com/doc/offiaccount/Comments_management/Image_Comments_Management_Interface.html
     */
    public function updateNews(string $mediaId, array $article, int $index = 0)
    {
        if (empty($article['title']) ||
            empty($article['content']) ||
            empty($article['content_source_url']) ||
            empty($article['thumb_media_id']) ||
            empty($article['author']) ||
            empty($article['digest'])
        ) {
            throw new Exception("缺少必填参数");
        }
        $uri = $this->format('cgi-bin/material/update_news');
        $data = [
            'media_id' => $mediaId,
            'index' => $index,
            'articles' => $article,
        ];
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => $data]);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }



    /**
     * 获取临时素材的URL
     *
     * @see https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/Get_temporary_materials.html
     * @return string|null
     * @throws Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @var string $mediaId
     */
    public function mediaGet(string $mediaId)
    {
        $uri = $this->format('cgi-bin/media/get') . '&media_id=' . $mediaId;
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
     * 新增临时素材文件
     *
     * @see https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/New_temporary_materials.html
     * @param MediaFile $files
     * @param string|null $type
     * @return array
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Exception
     */
    public function mediaUpload(MediaFile $files, string $type = null)
    {
        try {
            list($file, $type) = $this->uploadFileChecked($files, $type);
        } catch (\Exception $e) {
            throw $e;
        }
        $uri = $this->format('cgi-bin/media/upload') . '&type=' . $type;
        try {
            $response = $this->sdk->http($uri, 'POST', [
                'multipart' => [
                    [
                        'name' => 'media',
                        'contents' => fopen($file->getTmpname(), 'r'),
                        'filename' => $file->getName(),
                        'type' => $file->getType()
                    ],
                ]
            ]);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
    }

    /**
     * 上传图文素材中的图片
     *
     * @param MediaFile $files
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/Adding_Permanent_Assets.html
     */
    public function mediaUploadImg(MediaFile $files)
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
            $uri = $this->format('cgi-bin/media/uploadimg');
            try {
                $response[] = $this->sdk->returnResponseHandler(
                    $this->sdk->http($uri, 'POST', [
                        'multipart' => [
                            [
                                'name' => 'media',
                                'contents' => fopen($file->getTmpname(), 'r'),
                                'filename' => $file->getName(),
                                'type' => $file->getType()
                            ],
                        ]
                    ])
                );
            } catch (\Exception $e) {
                $response[] = $e;
            }
        }
        return $response;
    }

    /**
     *
     * @see https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Batch_Sends_and_Originality_Checks.html#1
     * @param array $articles
     * @return array|mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws Exception
     */
    public function mediaUploadNews(array $articles)
    {
        foreach ($articles as $article) {
            if (empty($article['title']) ||
                empty($article['content']) ||
                empty($article['content_source_url']) ||
                empty($article['thumb_media_id'])
            ) {
                throw new Exception("缺少必填参数");
            }
        }
        $uri = $this->format('cgi-bin/media/uploadnews');
        $data['articles'] = $articles;
        try {
            $response = $this->sdk->http($uri, 'POST', ['json' => $data]);
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sdk->returnResponseHandler($response);
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
            foreach ($this->mediaTypeExt as $wechatType => $mediaTypeExt) {
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
}
