<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Blog\Common;

class Router extends Common\Router
{
    public function _init()
    {
        $this->get('/', ':Article.List');
        $this->get('/list/(\d+)', ':Article.List:page');
        $this->get('/tag/(\d+)', ':Article.List:tag');
        $this->get('/tag/(\d+)/(\d+)', ':Article.List:tag:page');
        $this->get('/article/(\d+)', ':Article.Show:id');
        $this->get('/user/(\d+)', ':User.Show:id');
    }

    protected function default404()
    {
        $file = STATIC_PATH . $this->getUri();

        if (file_exists($file)) {
            header('Content-Type: ' . $this->_mime_type($file));
            header('Content-Length: ' . filesize($file));
            readfile($file);
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "Web 404 page not found\n";
        }
    }

    private function _mime_type($filename)
    {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/x-icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = substr(strrchr($filename, '.'), 1);

        if (isset($mime_types[$ext])) {
            return $mime_types[$ext];
        } else {
            return 'application/octet-stream';
        }
    }
}
