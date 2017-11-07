<?php
namespace Bybzmt\Blog\Common;

abstract class Service
{
    use Loader;


    public function __construct(Context $context)
    {
        $this->_context = $context;
    }


}
