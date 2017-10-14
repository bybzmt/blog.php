<?php
namespace Bybzmt\Blog\Api;

use Bybzmt\Blog\Reverse as PReverse;

class Reverse extends PReverse
{
    static public function mkUrl(string $func, array $params=array(), bool $https=false)
    {
        $uri = self::init()->buildUri($action, $params);

        $host = Config::get('host.api');

        if ($https) {
            return 'https://' . $host . $uri;
        }

        return 'http://' . $host . $uri;
    }
}
