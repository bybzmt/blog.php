<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller as Base;

class User extends Base
{
    public function articleList($offset, $length)
    {
        $service = $this->getService("Article");
        return $service->getUserList($this->row, $this->offset, $this->length);
    }

    public function records($offset, $length)
    {
        return $this->row->getRecords($offset, $length);
    }

}
