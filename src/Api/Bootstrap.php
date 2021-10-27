<?php
namespace Bybzmt\Blog\Api;

use Bybzmt\Framework\Bootstrap as Base;
use GraphQL\Server\StandardServer;
use GraphQL\Utils\BuildSchema;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Executor\Executor;
use GraphQL\Error\DebugFlag;


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
            'fieldResolver' => [$this, 'fieldResolver'],
        );

        $server = new StandardServer($config);
        $server->handleRequest();
    }

    public function fieldResolver($source, $args, Context $ctx, ResolveInfo $info)
    {
        if (strncmp($info->fieldName, "__", 2) !== 0) {
            $class = __NAMESPACE__ . "\\Controller\\" . $info->parentType->name;
            if (class_exists($class)) {
                $obj = new $class($ctx, $source, $args, $info);

                if (!method_exists($obj, $info->fieldName)) {
                    return Executor::defaultFieldResolver($source, $args, $ctx, $info);
                }

                return $obj->execute($info->fieldName);
            }
        }

        return Executor::defaultFieldResolver($source, $args, $ctx, $info);
    }

}
