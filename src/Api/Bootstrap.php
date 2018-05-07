<?php
namespace Bybzmt\Blog\Api;

use Bybzmt\Framework\Bootstrap as Base;
use Bybzmt\Blog\Api\GraphQL\StandardServer;
use GraphQL\Utils\BuildSchema;
use GraphQL\Type\Definition\ResolveInfo;

class Bootstrap extends Base
{
    public $name = "api";

    private $schema;

    public function __construct()
    {
        $this->schema = BuildSchema::build(file_get_contents(__DIR__ . '/GraphQL/schema.graphqls'));
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
            'fieldResolver' => [$this, 'fieldResolver'],
        );

        $server = new StandardServer($config);
        $server->handleRequest();
    }

    public function fieldResolver($source, $args, Context $ctx, ResolveInfo $info)
    {
        // var_dump($info->parentType->name, $info->fieldName);
        $name = $info->parentType->name;

        $class = __NAMESPACE__ . "\\Controller\\" . $name;

        $obj = new $class($ctx, $source, $args, $info);

        return $obj->resolve();
    }
}
