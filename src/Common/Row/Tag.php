<?php
namespace Bybzmt\Blog\Domain;

class Tag extends Base
{
    public $id;
    public $name;
    public $sort;
    public $status;

    protected function init($row)
    {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->sort = $row['sort'];
        $this->status = $row['status'];
    }

}
