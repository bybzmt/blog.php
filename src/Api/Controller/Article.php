<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller as Base;

class Article extends Base
{
    public function author()
    {
        return $this->getRow("User", $this->row->user_id);
    }

    public function addTime()
    {
        return strtotime($this->row->addtime);
    }

    public function publishTime()
    {
        return strtotime($this->row->addtime);
    }

    public function status()
    {
        switch ($this->row->status) {
        case 1: return "DRAFT";
        case 2: return "AUDITING";
        case 3: return "ONLINE";
        case 4: return "OFFLINE";
        }
    }

    public function comments($offset, $length)
    {
        return $this->row->getComments($offset, $length);
    }

    public function tags()
    {
        return $this->getTags();
    }

}
