<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller as Base;

class Reply extends Base
{
    public function addTime()
    {
        return strtotime($this->row->addtime);
    }

    public function user()
    {
        return $this->row->getUser();
    }
}
