<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Blog\Bootstrap as PBootstrap;

class Bootstrap extends PBootstrap
{
    public function run()
    {
        $router = new Router();

        $router->run();
    }
}
