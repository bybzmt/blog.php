<?php
namespace Bybzmt\Blog\Console;

use Bybzmt\Blog\Controller as PController;

class Controller extends PController
{
    public function execute()
    {
        try {
            $this->init();

            if ($this->valid()) {
                $this->service();

                $re = $this->render();

                echo json_encode(array('ret' => $this->errno, 'data'=> $re));
            } else {
                $this->fail();
            }
        } catch(Exception $e) {
            $this->error($e);
        }
    }

    public function init()
    {
    }

    public function valid()
    {
        return true;
    }

    public function fail()
    {
        echo json_encode(array('ret' => $this->errno, 'error' => $this->error));
    }

    public function run()
    {
    }

    public function render()
    {
    }

    public function error($e)
    {
        //error_log($e);
        throw $e;
    }
}
