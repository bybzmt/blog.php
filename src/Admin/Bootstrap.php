<?php
namespace Bybzmt\Blog\Admin;

use Bybzmt\Blog\Common;

class Bootstrap extends Common\Bootstrap
{
    public function run()
    {
        $context = new Context();

        //自定义SESSION处理
        $handler = $context->getService("Session");
        session_set_save_handler($handler, false);

        $router = new Router($context);

        $router->run();
    }
}
