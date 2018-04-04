<?php
namespace Bybzmt\Blog\Admin;

use Bybzmt\Framework\Bootstrap as Base;

class Bootstrap extends Base
{
    public function getContext()
    {
        $context = new Context();
        $context->moduleName = "admin";
        $context->module = $this;

        return $context;
    }

    protected function default404($ctx)
    {
        $file = STATIC_PATH .'/admin'. $this->getURI($ctx->request);

        if (file_exists($file)) {
            $ctx->getComponent("Helper\\StaticFile")->readfile($file);
        } else {
            parent::default404($ctx);
        }
    }

}
