<?php
namespace Bybzmt\Blog\Admin;

use Bybzmt\Blog\Common;

class Context extends Common\Context
{
    //初始化组件
    public function initComponent(string $name, ...$args)
    {
        $class = __NAMESPACE__ ."\\". $name;
        if (class_exists($class)) {
            return new $class($this, ...$args);
        } else {
            return parent::initComponent($name, ...$args);
        }
    }

}
