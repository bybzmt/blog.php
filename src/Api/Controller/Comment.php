<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller as Base;

class Comment extends Base
{
    public function addTime()
    {
        return strtotime($this->row->addtime);
    }

    public function replys($offset, $length)
    {
        return $this->row->getReplys($offset, $length);
    }
}
