<?php
namespace Bybzmt\Blog\Admin;

use Bybzmt\Blog\Common;

class Reverse extends Common\Reverse
{
    static public function mkUrl(string $action, array $params=array(), bool $https=false)
    {
        $uri = self::init()->buildUri($action, $params);

        $host = Common\Config::get('host.admin');

        if ($https) {
            return 'https://' . $host . $uri;
        }

        return 'http://' . $host . $uri;
    }
}
