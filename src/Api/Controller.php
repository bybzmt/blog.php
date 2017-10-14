<?php
namespace Bybzmt\Blog\Api;

use Bybzmt\Blog\Controller as PController;

class Controller extends PController
{
    protected $errno = 0;
    protected $error = 0;

    public function fail()
    {
        $this->renderJson($this->error, $this->errno);
    }

}
