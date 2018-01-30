<?php
namespace Bybzmt\Blog\Common;

use Bybzmt\Blog\Common\Config;

use Bybzmt\Router\Router as PRouter;

/**
 * 请求对像
 */
class Request
{
    public function getMethod()
    {
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            $_SERVER['REQUEST_METHOD'] = 'GET';
        }

        // Take the method as found in $_SERVER
        $method = $_SERVER['REQUEST_METHOD'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) &&
            in_array($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'], array('PUT', 'DELETE', 'PATCH'))
        ) {
            $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
        }

        return $method;
    }

    public function getURI()
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = '/';
        }

        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function getIP()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    }
}
