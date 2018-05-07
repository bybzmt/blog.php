<?php
namespace Bybzmt\Blog\Api;

use Bybzmt\Blog\Api\Context;
use Bybzmt\Framework\Component;

use GraphQL\Executor\Executor;

class Controller extends Component
{
    protected $_args;
    protected $_source;
    protected $_info;

    public function __construct($ctx, $source, $args, $info)
    {
        parent::__construct($ctx);

        $this->_source = $source;
        $this->_args = $args;
        $this->_info = $info;
    }

    public function resolve()
    {
        $resolverFn = $this->_info->fieldName . "Resolver";
        if (!method_exists($this, $resolverFn)) {
            return Executor::defaultFieldResolver($this->_source, $this->_args, $this->_ctx, $this->_info);
        }

        $refm = new \ReflectionMethod(get_class($this), $resolverFn);

        $params = [];
        foreach ($refm->getParameters() as $parameter) {
            $pos = $parameter->getPosition();
            $key = $parameter->getName();

            if (isset($args[$key])) {
                $params[$pos] = $args[$key];
            } else if ($parameter->isDefaultValueAvailable()) {
                $params[$pos] = $parameter->getDefaultValue();
            }
        }

        return $refm->invokeArgs($this, $params);
    }
}
