<?php
namespace Bybzmt\Blog\Api;

use Bybzmt\Blog\Api\Context;
use Bybzmt\Framework\Component;

class Controller extends Component
{
    static private $_actions = array();

    protected $_args;
    protected $row;

    public function __construct($ctx, $source, $args)
    {
        parent::__construct($ctx);

        $this->row = $source;
        $this->_args = $args;
    }

    static private function _parameters($name)
    {
        $key = static::class.'::'.$name;

        if (!isset(self::$_actions[$key])) {
            $ref = new \ReflectionMethod(static::class, $name);
            self::$_actions[$key] = $ref->getParameters();
        }

        return self::$_actions[$key];
    }

    public function execute($resolverFn)
    {
        $params = [];
        foreach (self::_parameters($resolverFn) as $parameter) {
            $pos = $parameter->getPosition();
            $key = $parameter->getName();

            if (isset($this->_args[$key])) {
                $params[$pos] = $this->_args[$key];
            } else if ($parameter->isDefaultValueAvailable()) {
                $params[$pos] = $parameter->getDefaultValue();
            } else {
                $params[$pos] = null;
            }
        }

        return $this->$resolverFn(...$params);
    }
}
