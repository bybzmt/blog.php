<?php
namespace Bybzmt\Blog\Common\Helper;

/**
 * 响应静态文件
 */
class StaticFile
{
    public static function readfile($file)
    {
        $size = filesize($file);
        $time = filemtime($file);
        $etag = "\"$time-$size\"";

        $_etag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : null;

        if ($_etag && $_etag == $etag) {
            header("HTTP/1.0 304 Not Modified");
            header("Etag: $etag");
            header('Last-Modified: ' . gmdate(DATE_RFC850, $time));
        } else {
            header('Content-Type: ' . self::_mime_type($file));
            header('Content-Length: ' . $size);
            header("Etag: $etag");
            header('Last-Modified: ' . gmdate(DATE_RFC850, $time));
            readfile($file);
        }
    }

    private static function _mime_type($filename)
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
