<?php
namespace Bybzmt\Blog\Console;

use Bybzmt\Blog\Reverse as PReverse;

class Reverse extends PReverse
{
    static public function mkUrl(string $func, array $params=array(), bool $https=false)
    {
        //控制台不存在域名这个说法
        return self::init()->buildUri($action, $params);
    }
}
