<?php
namespace Bybzmt\Blog\Admin;

use Bybzmt\Blog\Common;

class Context extends Common\Context
{
    /**
     * 初始化数据表对象
     */
    public function initService(string $name)
    {
        $class = __NAMESPACE__ ."\\Service\\". str_replace('.', '\\', $name);
        if (class_exists($class)) {
            return new $class($this);
        } else {
            return parent::initService($name);
        }
    }

    /**
     * 初始化数据表对象
     */
    public function initTable(string $name)
    {
        $class = __NAMESPACE__ ."\\Table\\". str_replace('.', '\\', $name);
        if (class_exists($class)) {
            return new $class($this);
        } else {
            return parent::initTable($name);
        }
    }

    /**
     * 初始化缓存对像
     */
    public function initCache(string $name, string $id='', ...$args)
    {
        $class = __NAMESPACE__ ."\\Cache\\". str_replace('.', '\\', $name);
        if (class_exists($class)) {
            return new $class($this, $id, ...$args);
        } else {
            return parent::initCache($name, $id, ...$args);
        }
    }

    /**
     * 初始化一个数据行对像
     */
    public function initRow(string $name, array $row)
    {
        $class = __NAMESPACE__ . "\\Row\\" . str_replace('.', '\\', $name);
        if (class_exists($class)) {
            return new $class($this, $row);
        } else {
            return parent::initRow($name, $row);
        }
    }
}
