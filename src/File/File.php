<?php

declare(strict_types=1);

namespace Shrimp\File;

/**
 * Class File
 * @package Shrimp\File
 */
class File
{
    /**
     * @var array|null
     */
    private $file;

    /**
     * File constructor.
     * @param array $file
     */
    public function __construct(array $file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->file['size'];
    }

    /**
     * @return mixed|string
     */
    public function getMediaType()
    {
        if (function_exists("mime_content_type")) {
            return mime_content_type($this->file['tmp_name']);
        }
        if (function_exists("finfo_open")) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $this->file['tmp_name']);
            finfo_close($finfo);
            return $mimetype;
        }
        $ext = $this->getExtName();
        if (isset(MimeType::$mine[$ext])) {
            return is_array(MimeType::$mine[$ext])
                ? (in_array($this->file['type'], MimeType::$mine[$ext])
                    ? MimeType::$mine[$ext]
                    : MimeType::$mine[$ext][0])
                : MimeType::$mine[$ext];
        }
        return "application/octet-stream";
    }

    /**
     * @return string
     */
    public function getType()
    {
        return strtolower($this->file['type']);
    }

    /**
     * @return string
     */
    public function getExtName()
    {
        return strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
    }

    /**
     * @return mixed
     */
    public function getTmpname()
    {
        return $this->file['tmp_name'];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->file['name'];
    }

    /**
     * @return array|null
     */
    public function getFile()
    {
        return $this->file;
    }
}
