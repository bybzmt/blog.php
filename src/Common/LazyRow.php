<?php
namespace Bybzmt\Blog\Common;

/**
 * 行数据惰性加载器
 */
class LazyRow
{
    protected $_context;
    protected $name;
    protected $id;
    protected $initd;
    protected $row;
    protected $cached;

    public function __construct(Context $context, string $name, string $id)
    {
        $this->_context = $context;

        $this->name = $name;
        $this->id = $id;

        //尝试一下直接从内存加载数据
        $this->row = $this->getCached($name, $id);
        $this->initd = $this->row === null ? false : true;

        if (!$this->initd) {
            $this->cached = $this->_context->getTable($name) instanceof TableRowCache;

            if ($this->cached) {
                $this->rowCacheAdd($id);
            } else {
                $this->rowAdd($id);
            }
        }
    }


    /**
     * 属性访问回调钩子
     */
    public function __get($key)
    {
        if (!$this->initd) {
            $this->init();
            $this->initd = true;
        }

        return $this->row ? $this->row->$key : null;
    }

    /**
     * 属性判断回调钩子
     */
    public function  __isset($key)
    {
        if (!$this->initd) {
            $this->init();
            $this->initd = true;
        }

        return $this->row ? isset($this->row->$key) : null;
    }

    /**
     * 方法访问回调钩子
     */
    public function __call($name, $params)
    {
        if (!$this->initd) {
            $this->init();
            $this->initd = true;
        }

        return $this->row ? $this->row->$name(...$params) : null;
    }

    public function _isHit()
    {
        if (!$this->initd) {
            $this->init();
            $this->initd = true;
        }

        return (bool)$this->row;
    }

    public function __debugInfo()
    {
        if (!$this->initd) {
            $this->init();
            $this->initd = true;
        }

        return array(
            'row' => $this->row,
        );
    }

    protected function init()
    {
        if ($this->cached) {
            $this->initCacheRow();
        } else {
            $this->initRow();
        }
    }

    protected function initRow()
    {
        if (!$this->isCached($this->id)) {
            //从数据库中加载数据
            $this->rowLoad();
        }

        $this->row = $this->getCached($this->id);
    }

    protected function initCacheRow()
    {
        if (!$this->isCached($this->id)) {
            //从缓存中批量载入
            $this->rowCacheLoad();
        }

        $this->row = $this->getCached($this->id);

        if ($this->row === null) {
            //从数据库中批量载入
            $rows = $this->rowLoad();

            //更新缓存
            $this->_context->getTable($this->name)->setCaches($rows);

            $this->row = $this->getCached($this->id);
        }
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

        $rows = $this->_context->getTable($this->name)->gets($ids);

        $rows += $this->_context->lazyRow[$this->name];

        foreach ($rows as $id=>$row) {
            $this->_context->cachedRow[$this->name][$id] = $row ? $this->_context->initRow($this->name, $row) : false;
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

        $rows = $this->_context->getTable($this->name)->getCaches($ids);
        foreach ($rows as $id => $row) {
            if ($row) {
                $this->_context->cachedRow[$this->name][$id] = $this->_context->initRow($this->name, $row);
            } else if ($row === null) {
                //缓存未命中时标记为需要从数据库加载
                $this->rowAdd($id);
            } else {
                $this->_context->cachedRow[$this->name][$id] = false;
            }
        }

        $this->_context->lazyRowCache[$this->name] = [];
    }

}
