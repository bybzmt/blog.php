<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Blog\Controller as PController;
use Exception;

abstract class Controller extends PController
{
    public function error($e)
    {
        //error_log($e);
        throw $e;
    }
}
