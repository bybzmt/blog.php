<?php
namespace Bybzmt\Blog\Api\GraphQL;

use Bybzmt\Blog\Api\Context;
use Bybzmt\Framework\ComponentTrait;
use GraphQL\Type\Definition\ObjectType as Base;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Executor\Executor;

class ObjectType extends Base
{
    use ComponentTrait;

    protected $_ctx;
    protected $_info;
    protected $_source;
    private $_refm = [];

    public function __construct($config)
    {
        if (!isset($config['resolveField'])) {
            $config['resolveField'] = array($this, 'resolveField');
        }

        $config['name'] = ucfirst(substr(get_class($this), strlen(__NAMESPACE__.'/Type')+1, -strlen('Type')));

        parent::__construct($config);
    }

    public function resolveField($source, $args, Context $ctx, ResolveInfo $info)
    {
        $resolverFn = $info->fieldName . "Resolver";
        if (!method_exists($this, $resolverFn)) {
            return Executor::defaultFieldResolver($source, $args, $ctx, $info);
        }

        $this->_ctx = $ctx;
        $this->_info = $info;
        $this->_source = $source;

        $out = $this->_invokeArgs($resolverFn, $args);

        $this->_ctx = null;
        $this->_info = null;
        $this->_source = null;

        return $out;
    }

    private function _invokeArgs($resolverFn, $args)
    {
        if (!isset($this->_refm[$resolverFn])) {
            $refm = $this->_refm[$resolverFn] = new \ReflectionMethod(get_class($this), $resolverFn);
        } else {
            $refm = $this->_refm[$resolverFn];
        }

        $params = [];
        foreach ($refm->getParameters() as $parameter) {
            $pos = $parameter->getPosition();
            $key = $parameter->getName();

            if (isset($args[$key])) {
                $params[$pos] = $args[$key];
            } else {
                $params[$pos] = $parameter->getDefaultValue();
            }
        }

        return $refm->invokeArgs($this, $params);
    }
}
