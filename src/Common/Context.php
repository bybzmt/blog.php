<?php
namespace Bybzmt\Blog\Common;

use Bybzmt\Framework\Context as Base;

class Context extends Base
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
