<?php
namespace Bybzmt\Blog\Common;

abstract class ListCache
{
    //缓存过期时间
    protected $expiration = 1800;

    //缓存的最大id数量
    protected $size = 10000;

    //排序方式(正序或倒序)
    protected $order = 'desc';

    //使用哪个memcached
    protected $memcachedName = 'default';

    //缓存key
    protected $key;

    public function __construct(Context $context)
    {
        $this->_context = $context;
        $this->key = str_replace('\\', '.', static::class);
    }

    abstract protected function getRows(array $ids);

    abstract protected function loadData(int $limit);

    public function gets(int $offset, int $length): array
    {
        $ids = array_slice($this->getAllIds(), $offset, $limit);
        return $this->getRows($ids);
    }

    public function count()
    {
        return count($this->getAllIds());
    }

    public static function addItem(string $id) : bool
    {
        $ids = $this->getAllIds();

        if ($this->order == 'desc') {
            array_unshift($ids, $id);

            while (count($ids) > $this->size) {
                array_pop($ids);
            }
        } else {
            array_push($ids, $id);

            while (count($ids) > $this->size) {
                array_shift($ids);
            }
        }

        return $this->setAllIds($ids);
    }

    public static function delItem(string $id) : bool
    {
        $ids = array_diff($this->getAllIds(), [$id]);
        return $this->setAllIds($ids);
    }

    public function getAllIds()
    {
        $ids = $this->unserialize($this->getMemcached($this->memcachedName)->get($this->key));
        if ($ids === null) {
            $ids = $this->loadData($this->size);
            $this->set($ids);
        }
        return $ids;
    }

    public function setAllIds(array $ids)
    {
        $this->getMemcached($this->memcachedName)->set($this->key, $this->serialize($ids), $this->expiration);
    }

    public function del()
    {
        return $this->getMemcached($this->memcachedName)->delete($this->key);
    }

    protected function hash(string $str): string
    {
        return hash("crc32b", $this->key . $this->order . $this->canOverSize . $str);
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
