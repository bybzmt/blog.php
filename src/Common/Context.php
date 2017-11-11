<?php
namespace Bybzmt\Blog\Common;

/**
 * 环境上下对像
 */
class Context
{
    //db连接
    public $dbConns;

    //memcached连接
    public $memcachedConns;

    //redis连接
    public $redisConns;

    //日志
    public $loggers;

    //缓存对像
    public $caches;

    //数据表对像
    public $tables;

    //服务对像
    public $services;

    //己缓存的数据行对像(php内存临时缓存)
    public $cachedRow;

    //标记的批量加载id(通过数据库加载)
    public $lazyRow;

    //标记的批量加载id(通过缓存加载)
    public $lazyRowCache;

    //防止var_dump打印太多无用信息
    public function __debugInfo()
    {
        return array(
            'cachedRowNum' => count($this->cachedRow),
        );
    }
}
