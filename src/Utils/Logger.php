<?php
namespace Bybzmt\Blog\Utils;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Bybzmt\Blog\Utils\Config;
use Bybzmt\Logger\Filelog;
use Bybzmt\Logger\Syslog;

class Logger
{
    public static function get($name): LoggerInterface
    {
        list($type, $ident, $facility) = Config::get("log.$name");

        if ($type == 'syslog') {
            $log = new Syslog($ident, $facility);
        } elseif ($type == 'file') {
            $log = new Filelog($ident, $facility);
        } elseif ($type == 'null') {
            //直接抛弃
            $log = new NullLogger();
        } elseif ($type == null) {
            throw new Exception("未定义的日志名称: $name");
        } else {
            throw new Exception("未定义的日志类型: $type 名称: $name");
        }

        return $log;
    }
}
