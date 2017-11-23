<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/11/20
 * Time: 19:19
 */

namespace Shrimp {

    class MediaFile implements \Iterator
    {
        private $position = 0;

        private $files = [];

        /**
         * MediaFile constructor.
         * @param string|array $file {$_FILE || example.png}
         */
        public function __construct($file)
        {
            if ($file) {
                if (is_file($file)) {
                    $this->addFile($this->formatFile($file));
                } else if (is_array($file)) {
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
            if (MediaType::$mine[$info['extension']]) {
                $type = is_array(MediaType::$mine[$info['extension']])
                    ? MediaType::$mine[$info['extension']][0]
                    : MediaType::$mine[$info['extension']];
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
                $this->files[] = new File($file);
            }
        }

        /**
         * Return the current element
         * @link http://php.net/manual/en/iterator.current.php
         * @return mixed Can return any type.
         * @since 5.0.0
         */
        public function current()
        {
            // TODO: Implement current() method.
            if ($this->valid()) {
                return $this->files[$this->position];
            }
            return null;
        }

        /**
         * Move forward to next element
         * @link http://php.net/manual/en/iterator.next.php
         * @return void Any returned value is ignored.
         * @since 5.0.0
         */
        public function next()
        {
            // TODO: Implement next() method.
            ++$this->position;
        }

        /**
         * Return the key of the current element
         * @link http://php.net/manual/en/iterator.key.php
         * @return mixed scalar on success, or null on failure.
         * @since 5.0.0
         */
        public function key()
        {
            // TODO: Implement key() method.
            return $this->position;
        }

        /**
         * Checks if current position is valid
         * @link http://php.net/manual/en/iterator.valid.php
         * @return boolean The return value will be casted to boolean and then evaluated.
         * Returns true on success or false on failure.
         * @since 5.0.0
         */
        public function valid()
        {
            // TODO: Implement valid() method.
            return isset($this->files[$this->position]);
        }

        /**
         * Rewind the Iterator to the first element
         * @link http://php.net/manual/en/iterator.rewind.php
         * @return void Any returned value is ignored.
         * @since 5.0.0
         */
        public function rewind()
        {
            // TODO: Implement rewind() method.
            $this->position = 0;
        }
    }

    /**
     * Class File
     * @package Shrimp
     */
    class File
    {
        /**
         * @var array|null
         */
        private $file = null;

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
         * @return mixed
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
            if (isset(MediaType::$mine[$ext])) {
                return is_array(MediaType::$mine[$ext]) 
                    ? (in_array($this->file['type'], MediaType::$mine[$ext]) ? MediaType::$mine[$ext] : MediaType::$mine[$ext][0]) 
                    : MediaType::$mine[$ext];
            }
            return "application/octet-stream";
        }

        /**
         * 
         */
        public function getType()
        {
            return strtolower($this->file['type']);
        }

        /**
         * @return mixed
         */
        public function getExtName()
        {
            return strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        }

        /**
         * @return array
         */
        public function getFile()
        {
            return $this->file;   
        }
    }

    class MediaType
    {
        public static $mine = [
            'hqx'	=>	['application/mac-binhex40', 'application/mac-binhex', 'application/x-binhex40', 'application/x-mac-binhex40'],
            'cpt'	=>	'application/mac-compactpro',
            'csv'	=>	['text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain'],
            'bin'	=>	['application/macbinary', 'application/mac-binary', 'application/octet-stream', 'application/x-binary', 'application/x-macbinary'],
            'dms'	=>	'application/octet-stream',
            'lha'	=>	'application/octet-stream',
            'lzh'	=>	'application/octet-stream',
            'exe'	=>	['application/octet-stream', 'application/x-msdownload'],
            'class'	=>	'application/octet-stream',
            'psd'	=>	['application/x-photoshop', 'image/vnd.adobe.photoshop'],
            'so'	=>	'application/octet-stream',
            'sea'	=>	'application/octet-stream',
            'dll'	=>	'application/octet-stream',
            'oda'	=>	'application/oda',
            'pdf'	=>	['application/pdf', 'application/force-download', 'application/x-download', 'binary/octet-stream'],
            'ai'	=>	['application/pdf', 'application/postscript'],
            'eps'	=>	'application/postscript',
            'ps'	=>	'application/postscript',
            'smi'	=>	'application/smil',
            'smil'	=>	'application/smil',
            'mif'	=>	'application/vnd.mif',
            'xls'	=>	['application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel', 'application/xls', 'application/x-xls', 'application/excel', 'application/download', 'application/vnd.ms-office', 'application/msword'],
            'ppt'	=>	['application/powerpoint', 'application/vnd.ms-powerpoint', 'application/vnd.ms-office', 'application/msword'],
            'pptx'	=> 	['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/x-zip', 'application/zip'],
            'wbxml'	=>	'application/wbxml',
            'wmlc'	=>	'application/wmlc',
            'dcr'	=>	'application/x-director',
            'dir'	=>	'application/x-director',
            'dxr'	=>	'application/x-director',
            'dvi'	=>	'application/x-dvi',
            'gtar'	=>	'application/x-gtar',
            'gz'	=>	'application/x-gzip',
            'gzip'  =>	'application/x-gzip',
            'php'	=>	['application/x-httpd-php', 'application/php', 'application/x-php', 'text/php', 'text/x-php', 'application/x-httpd-php-source'],
            'php4'	=>	'application/x-httpd-php',
            'php3'	=>	'application/x-httpd-php',
            'phtml'	=>	'application/x-httpd-php',
            'phps'	=>	'application/x-httpd-php-source',
            'js'	=>	['application/x-javascript', 'text/plain'],
            'swf'	=>	'application/x-shockwave-flash',
            'sit'	=>	'application/x-stuffit',
            'tar'	=>	'application/x-tar',
            'tgz'	=>	['application/x-tar', 'application/x-gzip-compressed'],
            'z'	=>	'application/x-compress',
            'xhtml'	=>	'application/xhtml+xml',
            'xht'	=>	'application/xhtml+xml',
            'zip'	=>	['application/x-zip', 'application/zip', 'application/x-zip-compressed', 'application/s-compressed', 'multipart/x-zip'],
            'rar'	=>	['application/x-rar', 'application/rar', 'application/x-rar-compressed'],
            'mid'	=>	'audio/midi',
            'midi'	=>	'audio/midi',
            'mpga'	=>	'audio/mpeg',
            'mp2'	=>	'audio/mpeg',
            'mp3'	=>	['audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'],
            'aif'	=>	['audio/x-aiff', 'audio/aiff'],
            'aiff'	=>	['audio/x-aiff', 'audio/aiff'],
            'aifc'	=>	'audio/x-aiff',
            'ram'	=>	'audio/x-pn-realaudio',
            'rm'	=>	'audio/x-pn-realaudio',
            'rpm'	=>	'audio/x-pn-realaudio-plugin',
            'ra'	=>	'audio/x-realaudio',
            'rv'	=>	'video/vnd.rn-realvideo',
            'wav'	=>	['audio/x-wav', 'audio/wave', 'audio/wav'],
            'bmp'	=>	['image/bmp', 'image/x-bmp', 'image/x-bitmap', 'image/x-xbitmap', 'image/x-win-bitmap', 'image/x-windows-bmp', 'image/ms-bmp', 'image/x-ms-bmp', 'application/bmp', 'application/x-bmp', 'application/x-win-bitmap'],
            'gif'	=>	'image/gif',
            'jpeg'	=>	['image/jpeg', 'image/pjpeg'],
            'jpg'	=>	['image/jpeg', 'image/pjpeg'],
            'jpe'	=>	['image/jpeg', 'image/pjpeg'],
            'jp2'	=>	['image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'],
            'j2k'	=>	['image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'],
            'jpf'	=>	['image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'],
            'jpg2'	=>	['image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'],
            'jpx'	=>	['image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'],
            'jpm'	=>	['image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'],
            'mj2'	=>	['image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'],
            'mjp2'	=>	['image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'],
            'png'	=>	['image/png',  'image/x-png'],
            'tiff'	=>	'image/tiff',
            'tif'	=>	'image/tiff',
            'css'	=>	['text/css', 'text/plain'],
            'html'	=>	['text/html', 'text/plain'],
            'htm'	=>	['text/html', 'text/plain'],
            'shtml'	=>	['text/html', 'text/plain'],
            'txt'	=>	'text/plain',
            'text'	=>	'text/plain',
            'log'	=>	['text/plain', 'text/x-log'],
            'rtx'	=>	'text/richtext',
            'rtf'	=>	'text/rtf',
            'xml'	=>	['application/xml', 'text/xml', 'text/plain'],
            'xsl'	=>	['application/xml', 'text/xsl', 'text/xml'],
            'mpeg'	=>	'video/mpeg',
            'mpg'	=>	'video/mpeg',
            'mpe'	=>	'video/mpeg',
            'qt'	=>	'video/quicktime',
            'mov'	=>	'video/quicktime',
            'avi'	=>	['video/x-msvideo', 'video/msvideo', 'video/avi', 'application/x-troff-msvideo'],
            'movie'	=>	'video/x-sgi-movie',
            'doc'	=>	['application/msword', 'application/vnd.ms-office'],
            'docx'	=>	['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword', 'application/x-zip'],
            'dot'	=>	['application/msword', 'application/vnd.ms-office'],
            'dotx'	=>	['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword'],
            'xlsx'	=>	['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.ms-excel', 'application/msword', 'application/x-zip'],
            'word'	=>	['application/msword', 'application/octet-stream'],
            'xl'	=>	'application/excel',
            'eml'	=>	'message/rfc822',
            'json'  =>	['application/json', 'text/json'],
            'pem'   =>	['application/x-x509-user-cert', 'application/x-pem-file', 'application/octet-stream'],
            'p10'   =>	['application/x-pkcs10', 'application/pkcs10'],
            'p12'   =>	'application/x-pkcs12',
            'p7a'   =>	'application/x-pkcs7-signature',
            'p7c'   =>	['application/pkcs7-mime', 'application/x-pkcs7-mime'],
            'p7m'   =>	['application/pkcs7-mime', 'application/x-pkcs7-mime'],
            'p7r'   =>	'application/x-pkcs7-certreqresp',
            'p7s'   =>	'application/pkcs7-signature',
            'crt'   =>	['application/x-x509-ca-cert', 'application/x-x509-user-cert', 'application/pkix-cert'],
            'crl'   =>	['application/pkix-crl', 'application/pkcs-crl'],
            'der'   =>	'application/x-x509-ca-cert',
            'kdb'   =>	'application/octet-stream',
            'pgp'   =>	'application/pgp',
            'gpg'   =>	'application/gpg-keys',
            'sst'   =>	'application/octet-stream',
            'csr'   =>	'application/octet-stream',
            'rsa'   =>	'application/x-pkcs7',
            'cer'   =>	['application/pkix-cert', 'application/x-x509-ca-cert'],
            '3g2'   =>	'video/3gpp2',
            '3gp'   =>	['video/3gp', 'video/3gpp'],
            'mp4'   =>	'video/mp4',
            'm4a'   =>	'audio/x-m4a',
            'f4v'   =>	['video/mp4', 'video/x-f4v'],
            'flv'	=>	'video/x-flv',
            'webm'	=>	'video/webm',
            'aac'   =>	'audio/x-acc',
            'm4u'   =>	'application/vnd.mpegurl',
            'm3u'   =>	'text/plain',
            'xspf'  =>	'application/xspf+xml',
            'vlc'   =>	'application/videolan',
            'wmv'   =>	['video/x-ms-wmv', 'video/x-ms-asf'],
            'au'    =>	'audio/x-au',
            'ac3'   =>	'audio/ac3',
            'flac'  =>	'audio/x-flac',
            'ogg'   =>	['audio/ogg', 'video/ogg', 'application/ogg'],
            'kmz'	=>	['application/vnd.google-earth.kmz', 'application/zip', 'application/x-zip'],
            'kml'	=>	['application/vnd.google-earth.kml+xml', 'application/xml', 'text/xml'],
            'ics'	=>	'text/calendar',
            'ical'	=>	'text/calendar',
            'zsh'	=>	'text/x-scriptzsh',
            '7zip'	=>	['application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip'],
            'cdr'	=>	['application/cdr', 'application/coreldraw', 'application/x-cdr', 'application/x-coreldraw', 'image/cdr', 'image/x-cdr', 'zz-application/zz-winassoc-cdr'],
            'wma'	=>	['audio/x-ms-wma', 'video/x-ms-asf'],
            'jar'	=>	['application/java-archive', 'application/x-java-application', 'application/x-jar', 'application/x-compressed'],
            'svg'	=>	['image/svg+xml', 'application/xml', 'text/xml'],
            'vcf'	=>	'text/x-vcard',
            'srt'	=>	['text/srt', 'text/plain'],
            'vtt'	=>	['text/vtt', 'text/plain'],
            'ico'	=>	['image/x-icon', 'image/x-ico', 'image/vnd.microsoft.icon'],
            'odc'	=>	'application/vnd.oasis.opendocument.chart',
            'otc'	=>	'application/vnd.oasis.opendocument.chart-template',
            'odf'	=>	'application/vnd.oasis.opendocument.formula',
            'otf'	=>	'application/vnd.oasis.opendocument.formula-template',
            'odg'	=>	'application/vnd.oasis.opendocument.graphics',
            'otg'	=>	'application/vnd.oasis.opendocument.graphics-template',
            'odi'	=>	'application/vnd.oasis.opendocument.image',
            'oti'	=>	'application/vnd.oasis.opendocument.image-template',
            'odp'	=>	'application/vnd.oasis.opendocument.presentation',
            'otp'	=>	'application/vnd.oasis.opendocument.presentation-template',
            'ods'	=>	'application/vnd.oasis.opendocument.spreadsheet',
            'ots'	=>	'application/vnd.oasis.opendocument.spreadsheet-template',
            'odt'	=>	'application/vnd.oasis.opendocument.text',
            'odm'	=>	'application/vnd.oasis.opendocument.text-master',
            'ott'	=>	'application/vnd.oasis.opendocument.text-template',
            'oth'	=>	'application/vnd.oasis.opendocument.text-web'
        ];
    }
}