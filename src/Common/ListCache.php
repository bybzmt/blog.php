<?php
namespace Bybzmt\Blog\Common;

abstract class ListCache extends Cache
{
    //缓存过期时间
    protected $expiration = 1800;

    //缓存的最大id数量
    protected $size = 10000;

    //排序方式(正序或倒序)
    protected $order = 'desc';

    //列表缓存id
    protected $list_id;

    public function __construct(Context $context, string $list_id='')
    {
        $this->_context = $context;
        $this->list_id = $list_id;
        $this->key = str_replace('\\', '.', static::class) .'.'. $list_id;
        $this->_hashPrefix = $this->key . $this->order;
    }

    abstract protected function getRows(array $ids):array;

    abstract protected function loadData(int $limit):array;

    public function gets(int $offset, int $length): array
    {
        $ids = array_slice($this->getAllIds(), $offset, $length);
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
        $ids = $this->unserialize($this->getMemcached()->get($this->key));
        if ($ids === null) {
            $ids = $this->loadData($this->size);
            $this->setAllIds($ids);
        }
        return $ids;
    }

    public function setAllIds(array $ids)
    {
        $this->getMemcached()->set($this->key, $this->serialize($ids), $this->expiration);
    }

    public function del()
    {
        return $this->getMemcached()->delete($this->key);
    }


}
