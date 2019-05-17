<?php

namespace Shrimp\File;

use Shrimp\Support\Collection;

/**
 * Created by PhpStorm.
 * User: meshell
 * Date: 2017/11/20
 * Time: 19:19
 */


class MediaFile extends Collection
{

    /**
     * MediaFile constructor.
     * @param string|array $file {$_FILE || example.png}
     * @throws \Exception
     */
    public function __construct($file)
    {
        if ($file) {
            if (is_file($file)) {
                $this->addFile($this->formatFile($file));
            } elseif (is_array($file)) {
                $this->addFile($file);
            }
        }
    }

    /**
     * @param $file
     * @return array
     * @throws \Exception
     */
    private function formatFile($file)
    {
        $info = pathinfo($file);
        $type = "";
        if (MimeType::$mine[$info['extension']]) {
            $type = is_array(MimeType::$mine[$info['extension']])
                ? MimeType::$mine[$info['extension']][0]
                : MimeType::$mine[$info['extension']];
        }
        if (empty($type)) {
            throw new \Exception("不能识别的文件");
        }
        return [
            'name' => $info['basename'],
            'type' => $type,
            'size' => filesize($file),
            'tmp_name' => $file,
            'error'    => 0,
        ];
    }
    /**
     * @param array $files
     */
    private function addFile(array $files)
    {
        $keys = array_keys($files);
        if (!is_array($files[$keys[0]])) {
            $files = [$files];
        }
        foreach ($files as $name => $file) {
            if (!isset($file['name']) || !isset($file['type']) ||
            !isset($file['size']) || !isset($file['tmp_name']) || !isset($file['error'])) {
                continue;
            }
            $this->add(new File($file));
        }
    }
}

