<?php
namespace Bybzmt\Blog\Api;

use Bybzmt\Blog\Bootstrap as PBootstrap;

class Bootstrap extends PBootstrap
{
    public function run()
    {
        $router = new Router();

        $router->run();
    }
}
