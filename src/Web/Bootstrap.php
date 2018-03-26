<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Common\Context;
use Bybzmt\Blog\Common\Helper\Session;

class Bootstrap extends Common\Bootstrap
{
    public function run($request, $response)
    {
        $context = new Context();
        $context->request = $request;
        $context->response = $response;

        //自定义SESSION处理
        $context->session = new Session($context);

        $router = new Router($context);

        $router->run();
    }
}
