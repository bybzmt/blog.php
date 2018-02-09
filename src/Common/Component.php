<?php
namespace Bybzmt\Blog\Common;

/**
 * 根组件
 *
 * 只是一个象征性的根组件
 */
abstract class Component
{
    protected $_context;

    public function __construct(Context $context)
    {
        $this->_context = $context;
    }

    public function __debugInfo()
    {
        //防止反复打印上下文对像
        $attr = get_object_vars($this);
        unset($attr['_context']);
        return $attr;
    }
}
