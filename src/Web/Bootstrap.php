<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Framework\Bootstrap as Base;
use Bybzmt\Blog\Common\Helper\Session;

class Bootstrap extends Base
{
    public function getContext()
    {
        $context = new Context();
        $context->moduleName = "web";
        $context->router = $context->init("Router");

        return $context;
    }

    public function run($request, $response)
    {
        $context = $this->getContext();
        $context->request = $request;
        $context->response = $response;

        $context->router->run();
    }
}
