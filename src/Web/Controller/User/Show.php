<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\AuthWeb;
use Bybzmt\Blog\Web\Reverse;

class Show extends AuthWeb
{
    private $_lenght = 10;
    private $_offset;

    protected $user;
    protected $msg;
    protected $page;
    protected $records;
    private $_records_count;

    public function init()
    {
        $this->page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($this->page < 1) {
            $this->page = 1;
        }

        $this->_offset = ($this->page - 1) * $this->_lenght;
    }

    public function valid()
    {
        $this->user = $this->_context->getRow('User', $this->_uid);
        if ($this->user) {
            return true;
        }

        $this->msg = "用户不存在";

        return false;
    }

    public function fail()
    {
        var_dump($this->msg);
    }

    public function show()
    {
        list($records, $records_count) = $this->user->getRecords($this->_offset, $this->_lenght);

        $this->render(array(
            'records' => $records,
        ));
    }


}
