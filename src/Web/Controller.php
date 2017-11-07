<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Blog\Common;

abstract class Controller extends Common\Controller
{
    public function error($e)
    {
        //error_log($e);
        throw $e;
    }
}
