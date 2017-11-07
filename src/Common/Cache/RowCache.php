<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Blog\Utils\Cache;
use Memcached;

/**
 * 行缓存对像
 */
class RowCache
{
    //缓存过期时间
    protected $expiration = 1800;

    //上下文对像
    protected $_context;

    //数据对像名称
    protected $_name;

    protected $_keyPrefix;
    protected $_hashPrefix;
    protected $_primary;
    protected $_table;

    public function __construct(Context $context, string $name)
    {
        $this->_context = $context;
        $this->_name = $name;

        $this->_keyPrefix = str_replace('\\', '.', static::class) . $name;

        $this->_table = $this->getTable($this->name);

        $this->_primary = $this->_table->getPrimary();

        $this->_hashPrefix = $this->_table->getTableName() . $this->_primary . implode($this->_table->getColumns());
    }

    public function getCached(string $id)
    {
        return $this->unserialize(Cache::getCache()->get($this->getKey($id)));
    }

    public static function get(string $id)
    {
        $key = $this->getKey($id);

        $row = $this->unserialize(Cache::getCache()->get($key));
        if ($row === null) {
            $row = $this->_table->find($id);
            Cache::getCache()->set($key, $this->serialize($row), $this->expiration);
        }

        return $row;
    }

    public function getsCached(array $ids)
    {
        $keys = $out = [];

        foreach ($ids as $id) {
            $keys[$this->getKey($id)] = $id;
        }

        $rows = Cache::getCache()->getMulti(array_keys($keys), Memcached::GET_PRESERVE_ORDER);
        foreach ($rows as $key => $row) {
            $out[$keys[$key]] = $this->unserialize($row);
        }

        return $out;
    }

    public function gets(array $ids, $kv=false)
    {
        $keys = $out = $miss = [];

        foreach ($ids as $id) {
            $keys[$this->getKey($id)] = $id;
        }

        $rows = Cache::getCache()->getMulti(array_keys($keys), Memcached::GET_PRESERVE_ORDER);
        foreach ($rows as $key => $tmp) {
            $row = $this->unserialize($tmp);
            $id = $keys[$key];
            if ($row === null) {
                $miss[$id] = false;
            } else {
                if ($kv) {
                    $out[$id] = $row;
                } else if ($tmp) {
                    $out[] = $row;
                }
            }
        }

        if ($miss) {
            $new_caches = $found = [];

            $rows = $this->_table->finds(array_keys($miss));
            $rows = array_column($rows, null, $this->_primary) + $miss;

            foreach ($rows as $id => $row) {
                if ($kv) {
                    $out[$id] = $row;
                } else if ($row) {
                    $out[] = $row;
                }

                $new_caches[$this->getKey($id)] = $this->serialize($row);
            }

            Cache::getCache()->setMulti($new_caches, $this->expiration);
        }

        return $out;
    }

    public function set(string $id, $row)
    {
        $key = $this->getKey($id);
        return Cache::getCache()->set($key, $this->serialize($row), $this->expiration);
    }

    public function sets(array $rows)
    {
        $data = [];
        foreach ($rows as $id => $row) {
            $data[$this->getKey($id)] = $this->serialize($row);
        }

        return Cache::getCache()->setMulti($data, $this->expiration);
    }

    public function del(string $id): bool
    {
        $key = $this->getKey($id);
        return Cache::getCache()->delete($key);
    }

    public function dels(array $ids): bool
    {
        $keys = [];
        foreach ($ids as $id) {
            $key[] = $this->getKey($id);
        }

        return Cache::getCache()->deleteMulti($key);
    }

    protected function getKey(string $id): string
    {
        return $this->_keyPrefix . $id;
    }

    protected function hash(string $str): string
    {
        return hash("crc32b", $this->_hashPrefix.$str);
    }

    protected function serialize($data)
    {
        $str = serialize($data);
        //生成hash前缀
        return $this->hash($str) . $str;
    }

    protected function unserialize($data)
    {
        if (!$data) {
            return null;
        }

        $str = substr($data, 8);

        $hash = $this->hash($str);

        //验证数据是否损坏
        //实际使用中会发生表结构变动，缓存串key，缓存异常等情况
        //虽然一般这些损坏都是代码bug或代码改动造成的
        //理论上代码无bug且没有变动时不会出现损坏，但好的程序应该有
        //较好的容错性和健壮性，这里推荐坚持验证
        if (strncmp($hash, $data, 8) != 0) {
            return null;
        }

        return unserialize($str);
    }
}
