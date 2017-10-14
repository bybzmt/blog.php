<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller;
use Exception;

class Article_List extends Controller
{
    protected $page;
    protected $lenght = 10;


    public function init()
    {
        $this->page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($this->page < 1) {
            $this->page = 1;
        }
    }

    public function valid()
    {
        if ($this->page > 20) {
            $this->errno = 1;
            $this->error = "翻页只能翻20页";
            return false;
        }

        return true;
    }

    public function run()
    {
    }

    public function render()
    {
        return "Article list success";
    }
}
