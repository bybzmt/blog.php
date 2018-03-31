<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Framework\Bootstrap as Base;
use Bybzmt\Blog\Common\Helper\Session;

class Bootstrap extends Base
{
    public function run($request, $response)
    {
        $context = new Context();
        $context->moduleName = "Web";
        $context->request = $request;
        $context->response = $response;

        $context->router = $context->init("Router");

        $context->router->run();
    }
}
