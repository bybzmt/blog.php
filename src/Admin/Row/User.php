<?php
namespace Bybzmt\Blog\Admin\Row;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Admin;

class User extends Common\Row\User
{
    public function disable()
    {
        $ok = $this->_context->getTable("User")->update($this->id, array('status'=>0));
        if ($ok) {
            $this->status = 0;
        }
        return $ok;
    }

    public function enable()
    {
        $ok = $this->_context->getTable("User")->update($this->id, array('status'=>1));
        if ($ok) {
            $this->status = 1;
        }
        return $ok;
    }

}
