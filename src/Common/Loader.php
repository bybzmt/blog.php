<?php
namespace Bybzmt\Blog\Common;

trait Loader
{
    protected $_context;

    public static function getLogger($name='default'): LoggerInterface
    {
		if (!isset($this->_context->loggers[$name])) {
			$this->_context->loggers[$name] = Resource::getLogger($name);
		}

		return $this->_context->loggers[$name];
    }

    /**
     * 得到数据表对象
     */
    protected function getService(string $name)
    {
        if (!isset($this->_context->services[$name])) {
            $table = __NAMESPACE__ ."\\Service\\". str_replace('.', '\\', $name);
            $this->_context->services[$name] = new $table($this->_context);
        }
        return $this->_context->services[$name];
    }

    /**
     * 得到数据表对象
     */
    protected function getTable(string $name)
    {
        if (!isset($this->_context->tables[$name])) {
            $table = __NAMESPACE__ ."\\Table\\". str_replace('.', '\\', $name);
            $this->_context->tables[$name] = new $table($this->_context);
        }
        return $this->_context->tables[$name];
    }

    /**
     * 得到缓存对像
     */
    protected function getCache(string $name, ...$args)
    {
        if (!isset($this->_context->caches[$name])) {
            $class = __NAMESPACE__ ."\\Cache\\". str_replace('.', '\\', $name);
            $this->_context->caches[$name] = new $class($this->_context, ...$args);
        }
        return $this->_context->caches[$name];
    }

    /**
     * 初始化一个数据行对像
     */
    protected function initRow(string $name, array $row)
    {
        $class = __NAMESPACE__ . "\\" . str_replace('.', '\\', $name);
        return new $class($this->_context, $row);
    }

    /**
     * 从数据库加载一个数据行对像
     */
    protected function getRow(string $name, string $id)
    {
        $row = $this->getTable($name)->get($id);
        return $row ? $this->initRow($this->_context, $row) : false;
    }

    /**
     * 从数据库惰性加载一个数据行对像
     */
    protected function getLazyRow(string $name, string $id)
    {
        return new LazyRow($this->_context, $name, $id);
    }

    /**
     * 从缓存加载一个数据行对像
     */
    protected function getRowCache(string $name, string $id)
    {
        $row = $this->getCache('RowCache', $name)->get($id);
        return $row ? $this->initRow($this->_context, $row) : false;
    }

    /**
     * 从缓存惰性加载一个数据行对像
     */
    protected function getLazyRowCache(string $name, string $id)
    {
        return new LazyRowCache($this->_context, $name, $id);
    }

}
