<?php
namespace Bybzmt\Blog\Admin;

use Bybzmt\Blog\Common;

class Bootstrap extends Common\Bootstrap
{
    public function run()
    {
        $router = new Router();

        $router->run();
    }
}
