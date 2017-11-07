<?php
namespace Bybzmt\Blog\Utils;

use Psr\Log\CacheInterface;
use Bybzmt\Blog\Utils\Config;
use Cache\Adapter\Memcached\MemcachedCachePool;
use Memcached;

class Cache
{
    private static $ins = [];

    public static function getCache($name=null)
    {
        if (!$name) {
            $name = 'default';
        }

        if (!isset(self::$ins[$name])) {
            $config = Config::get("cache.$name");
            $list = $config['servers'];

            $client = new Memcached($config['persistent_id']);

			$client->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
			$client->setOption(Memcached::OPT_TCP_NODELAY, true);
			$client->setOption(Memcached::OPT_NO_BLOCK, true);
			$client->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);

			$now = $client->getServerList();
            if (!$now) {
				$client->addServers($list);
            }

            self::$ins[$name] = $client;
        }

        return self::$ins[$name];
    }

    public static function getPool($name=null): CacheInterface
    {
        $cache = self::getCache($name);

        $pool = new MemcachedCachePool($cache);

        return $pool;
    }

    public static function incrby($key, $increment=1, $name=null)
    {
        $cache = self::getCache($name);
        if ($increment > 0) {
            return $cache->increment($key, $increment);
        }

        return $cache->decrement($key, abs($increment));
    }
}
