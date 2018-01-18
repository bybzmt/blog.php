<?php
namespace Bybzmt\Blog\Common;

abstract class Row
{
    protected $_context;

    public function __construct(Context $context, array $row)
    {
        $this->_context = $context;
        $this->init($row);
    }

    protected function init(array $row)
    {
        foreach ($row as $key => $val) {
            $this->$key = $val;
        }
    }

}
