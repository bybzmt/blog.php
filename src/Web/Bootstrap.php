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
        $context->module = $this;

        return $context;
    }

    protected function default404($ctx)
    {
        $file = STATIC_PATH .'/web'. $this->getURI($ctx->request);

        if (file_exists($file)) {
            $ctx->getComponent("Helper\\StaticFile")->readfile($file);
        } else {
            parent::default404($ctx);
        }
    }
}
