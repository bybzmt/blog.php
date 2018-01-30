<?php
namespace Bybzmt\Blog\Common;

abstract class Service
{

    public function __construct(Context $context)
    {
        $this->_context = $context;
        $this->_init();
    }

    protected function _init()
    {
    }


}
