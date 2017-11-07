<?php
namespace Bybzmt\Blog\Common;

/**
 * 行数据惰性加载器
 */
abstract class LazyLoader
{
    use Loader;

    protected $name;
    protected $id;
    protected $initd;
    protected $row;

    public function __construct(Context $context, string $name, string $id)
    {
        $this->_context = $context;

        $this->name = $name;
        $this->id = $id;

        $this->row = $this->_GetCached($name, $id);
        $this->initd = $this->row === null ? false : true;
    }

    abstract protected function init();

    /**
     * 属性访问回调钩子
     */
    protected function __get($key)
    {
        if (!$this->initd) {
            $this->init();
            $this->initd = true;
        }

        return $this->row ? $this->row->$key : null;
    }

    /**
     * 方法访问回调钩子
     */
    protected function __call($name, $params)
    {
        if (!$this->initd) {
            $this->init();
            $this->initd = true;
        }

        return $this->row ? $this->row->$name(...$params) : null;
    }

    /**
     * 是否己存在
     */
    protected function isCached($id)
    {
        return isset($this->_context->cachedRow[$this->name][$id]);
    }

    /**
     * 得到己缓存的对像
     */
    protected function getCached($id)
    {
        return isset($this->_context->cachedRow[$this->name][$id]) ? $this->_context->cachedRow[$this->name][$id] : null;
    }

    /**
     * 标记为需要惰性缓存加载
     */
    protected function rowCacheAdd($id)
    {
        $this->_context->lazyRowCache[$this->name][$id] = false;
    }

    /**
     * 标记为需要惰性数据库加载
     */
    protected function rowAdd($id)
    {
        $this->_context->lazyRow[$this->name][$id] = false;
    }

    /**
     * 从数据库中批量加载
     */
    protected function rowLoad()
    {
        $ids = array_keys($this->_context->lazyRow[$this->name]);

        $table = $this->getTable($this->name);
        $primary = $table->getPrimary();

        $rows = $table->finds($ids);
        $rows = array_column($rows, null, $primary) + $this->_context->lazyRow[$this->name];

        foreach ($rows as $id=>$row) {
            $this->_context->cachedRow[$this->name][$id] = $row ? $this->initRow($this->name, $row) : false;
        }

        $this->_context->lazyRow[$this->name] = [];

        return $rows;
    }

    /**
     * 从缓存中批量加载
     */
    protected function rowCacheLoad()
    {
        $ids = array_keys($this->_context->lazyRowCache[$this->name]);

        $rows = $this->getCache('RowCache', $this->name)->getsCached($ids);
        foreach ($rows as $id => $row) {
            if ($row) {
                $this->_context->cachedRow[$this->name][$this->id] = $this->initRow($this->name, $row);
            } else if ($row === null) {
                //缓存未命中时标记为需要从数据库加载
                $this->_RowAdd($this->id);
            } else {
                $this->_context->cachedRow[$this->name][$this->id] = false;
            }
        }

        $this->_context->lazyRowCache[$this->name] = [];
    }

}
