<?php
namespace Bybzmt\Blog\Api;

use Bybzmt\Framework\Bootstrap as Base;
use GraphQL\Type\Schema;
use GraphQL\GraphQL;
use GraphQL\Error\FormattedError;
use GraphQL\Error\Debug;

use Bybzmt\Blog\Api\GraphQL\Server\StandardServer;

class Bootstrap extends Base
{
    public $name = "api";

    private $schema;

    public function __construct()
    {
        // GraphQL schema to be passed to query executor:
        $this->schema = new Schema([
            'query' => Types::query()
        ]);
    }

    public function run($request, $response)
    {
        //初始化上下文对像
        $ctx = new Context();
        $ctx->module = $this;
        $ctx->request = $request;
        $ctx->response = $response;

        $server = new StandardServer($config, $ctx);
        $server->handleRequest();
    }
}
