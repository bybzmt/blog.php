<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Blog\Common\Context as Base;

class Context extends Base
{
    //初始化组件
    public function init(string $name, ...$args)
    {
        $class = __NAMESPACE__ ."\\". $name;
        if (class_exists($class)) {
            return new $class($this, ...$args);
        } else {
            return parent::init($name, ...$args);
        }
    }

}
