<?php
namespace Bybzmt\Blog\Api\GraphQL\Server;

use GraphQL\Server\StandardServer as Base;


class StandardServer extends Base
{
    public function __construct($config, $ctx)
    {
        if (is_array($config)) {
            $config = ServerConfig::create($config);
        }
        if (!$config instanceof ServerConfig) {
            throw new InvariantViolation("Expecting valid server config, but got " . Utils::printSafe($config));
        }
        $this->config = $config;
        $this->helper = new Helper($ctx);
    }



}
