<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Blog\Common;
use Memcached;

/**
 * 行缓存对像
 */
class RowCache extends Common\Cache
{
    //缓存过期时间
    protected $expiration = 1800;

    //上下文对像
    protected $_context;

    //数据对像名称
    protected $_name;

    protected $_keyPrefix;
    protected $_table;

    public function __construct(Common\Context $context, string $name)
    {
        $this->_context = $context;
        $this->_name = $name;

        $this->_keyPrefix = str_replace('\\', '.', static::class) . $name;

        $this->_table = $this->getTable($name);

        list($dbname, $tablename, $primary, $columns) = $this->_table->_getInfo();

        $this->_hashPrefix = $dbname.$tablename.$primary.implode($columns);
    }

    /**
     * 仅从缓存中取得数据
     */
    public function getCached(string $id)
    {
        return $this->unserialize($this->getMemcached()->get($this->getKey($id)));
    }

    /**
     * 得到数据,缓存未命中时从数据库中加载
     */
    public function get(string $id)
    {
        $key = $this->getKey($id);

        $row = $this->unserialize($this->getMemcached()->get($key));
        if ($row === null) {
            $row = $this->_table->find($id);
            $this->getMemcached()->set($key, $this->serialize($row), $this->expiration);
        }

        return $row;
    }

    /**
     * 批量得到数据,仅从缓存中加载
     */
    public function getsCached(array $ids)
    {
        $keys = $out = [];

        foreach ($ids as $id) {
            $keys[$this->getKey($id)] = $id;
        }

        $rows = $this->getMemcached()->getMulti(array_keys($keys), Memcached::GET_PRESERVE_ORDER);
        foreach ($rows as $key => $row) {
            $out[$keys[$key]] = $this->unserialize($row);
        }

        return $out;
    }

    /**
     * 批量得到数据,缓存未命中时从数据库中加载
     */
    public function gets(array $ids, $kv=false)
    {
        $keys = $out = $miss = [];

        foreach ($ids as $id) {
            $keys[$this->getKey($id)] = $id;
        }

        $rows = $this->getMemcached()->getMulti(array_keys($keys), Memcached::GET_PRESERVE_ORDER);
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

            $rows = $this->_table->finds(array_keys($miss), true);
            foreach ($rows as $id => $row) {
                if ($kv) {
                    $out[$id] = $row;
                } else if ($row) {
                    $out[] = $row;
                }

                $new_caches[$this->getKey($id)] = $this->serialize($row);
            }

            $this->getMemcached()->setMulti($new_caches, $this->expiration);
        }

        return $out;
    }

    /**
     * 修改己缓存的数据(仅保证下次从缓存取出的数据一定是新的)
     */
    public function update(string $id, array $row)
    {
        $old = $this->getCached($id);
        if ($old) {
            foreach ($row as $k => $v) {
                $old[$k] = $v;
            }
            $this->set($id, $old);
        } else if ($old !== null) {
            $this->del($id);
        }
    }

    /**
     * 直接设置缓存
     */
    public function set(string $id, $row)
    {
        $key = $this->getKey($id);
        return $this->getMemcached()->set($key, $this->serialize($row), $this->expiration);
    }

    /**
     * 批量设置缓存
     */
    public function sets(array $rows)
    {
        if (!$rows) {
            return true;
        }

        $data = [];
        foreach ($rows as $id => $row) {
            $data[$this->getKey($id)] = $this->serialize($row);
        }

        return $this->getMemcached()->setMulti($data, $this->expiration);
    }

    /**
     * 删除缓存
     */
    public function del(string $id): bool
    {
        $key = $this->getKey($id);
        return $this->getMemcached()->delete($key);
    }

    /**
     * 批量删除缓存
     */
    public function dels(array $ids): bool
    {
        $keys = [];
        foreach ($ids as $id) {
            $key[] = $this->getKey($id);
        }

        return $this->getMemcached()->deleteMulti($key);
    }

    protected function getKey(string $id): string
    {
        return $this->_keyPrefix . $id;
    }

}
