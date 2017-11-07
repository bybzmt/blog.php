<?php
namespace Bybzmt\Blog\Common;

abstract class Row
{
    use Loader;

    public function __construct(Context $context, array $row)
    {
        $this->_context = $context;
        $this->init($row);
    }

    abstract protected function init(array $row);

}
