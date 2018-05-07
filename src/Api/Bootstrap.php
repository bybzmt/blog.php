<?php
namespace Bybzmt\Blog\Api;

use Bybzmt\Framework\Bootstrap as Base;
use Bybzmt\Blog\Api\GraphQL\Types;
use Bybzmt\Blog\Api\GraphQL\Server\StandardServer;
use GraphQL\Type\Schema;

class Bootstrap extends Base
{
    public $name = "api";

    private $schema;

    public function __construct()
    {
        $this->schema = new Schema([
            'query' => Types::get("Query")
        ]);
    }

    public function getContext()
    {
    }

    public function run($request, $response)
    {
        //初始化上下文对像
        $ctx = new Context();
        $ctx->module = $this;
        $ctx->request = $request;
        $ctx->response = $response;

        $config = array(
            'Context' => $ctx,
            'schema' => $this->schema,
            'QueryBatching' => true,
            'debug' => true,
        );

        $server = new StandardServer($config);
        $server->handleRequest();
    }
}
