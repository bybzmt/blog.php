<?php
namespace Bybzmt\Blog\Admin\Row;

use Bybzmt\Blog\Admin;

class AdminPermission extends Admin\Row
{
    public $permission;
    public $about;

    protected function init(array $row)
    {
        $this->permission = $row['permission'];
        $this->about = $row['about'];
    }
}
