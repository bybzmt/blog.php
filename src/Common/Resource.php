<?php
namespace Bybzmt\Blog\Common;

use PDO;
use Memcached;
use Redis;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Bybzmt\Blog\Utils\Config;
use Bybzmt\Logger\Filelog;
use Bybzmt\Logger\Syslog;
use bybzmt\Locker\SocketLock;
use bybzmt\Locker\FileLock;

class Resource
{
    public static function getDb($name='default')
    {
        $cfgs = Config::get("db.{$name}");

        list($dsn, $user, $pass) = $cfgs[mt_rand(0, count($cfgs)-1)];

        $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        return new PDO($dsn, $user, $pass, $opts);
    }

    public static function getMemcached($name='default')
    {
        $config = Config::get("memcached.$name");

        $client = new Memcached($config['persistent_id']);

        $client->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
        $client->setOption(Memcached::OPT_TCP_NODELAY, true);
        $client->setOption(Memcached::OPT_NO_BLOCK, true);
        $client->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);

        $now = $client->getServerList();
        if (!$now) {
            $client->addServers($config['servers']);
        }

        return $client;
    }

	public static function getRedis($name='default')
	{
        $config = Config::get("redis.$name");

        $md = new Redis();
        $md->connect($config["host"], $config["port"], $config["timeout"]);
        if (!empty($config['password'])) {
            $md->auth($config['password']);
        }
        //$md->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        //$md->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        //$md->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY);
        return $md;
	}

    public static function getLogger($name='default'): LoggerInterface
    {
        list($type, $ident, $facility) = Config::get("log.$name");

        if ($type == 'syslog') {
            $log = new Syslog($ident, $facility);
        } elseif ($type == 'file') {
            $log = new Filelog($ident, $facility);
        } elseif ($type == 'PHPlog') {
            $log = new PHPlog($ident);
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

	public static function getLocker($key)
	{
        $config = Config::get("locker");

        switch($config['type']) {
        case 'socket':
            return new SocketLock($key, $config["host"], $config["port"], $config["timeout"]);
        case 'file':
            return new FileLock($key);
        default:
            throw new Exception("未定义的锁类型: {$config['type']}");
        }
	}
}
