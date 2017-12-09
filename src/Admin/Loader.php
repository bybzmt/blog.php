<?php
namespace Bybzmt\Blog\Admin;

trait Loader
{
    protected $_context;

    /**
     * 初始化数据表对象
     */
    protected function initService(string $name)
    {
        $class = __NAMESPACE__ ."\\Service\\". str_replace('.', '\\', $name);
        if (class_exists($class)) {
            return new $class($this->_context);
        } else {
            return parent::initTable($name);
        }
    }

    /**
     * 初始化数据表对象
     */
    protected function initTable(string $name)
    {
        $class = __NAMESPACE__ ."\\Table\\". str_replace('.', '\\', $name);
        if (class_exists($class)) {
            return new $class($this->_context);
        } else {
            return parent::initTable($name);
        }
    }

    /**
     * 初始化缓存对像
     */
    protected function initCache(string $name, string $id='', ...$args)
    {
        $class = __NAMESPACE__ ."\\Cache\\". str_replace('.', '\\', $name);
        if (class_exists($class)) {
            return new $class($this->_context, $id, ...$args);
        } else {
            return parent::initCache($name, $id, ...$args);
        }
    }

    /**
     * 初始化一个数据行对像
     */
    protected function initRow(string $name, array $row)
    {
        $class = __NAMESPACE__ . "\\Row\\" . str_replace('.', '\\', $name);
        if (class_exists($class)) {
            return new $class($this->_context, $row);
        } else {
            return parent::initRow($name, $row);
        }
    }

    /**
     * 从数据库惰性加载一个数据行对像
     */
    protected function getLazyRow(string $name, string $id)
    {
        return new LazyRow($this->_context, $name, $id);
    }

    /**
     * 从缓存惰性加载一个数据行对像
     */
    protected function getLazyRowCache(string $name, string $id)
    {
        return new LazyRowCache($this->_context, $name, $id);
    }
}
