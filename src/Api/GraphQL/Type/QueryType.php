<?php
namespace GraphQL\Examples\Blog\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Query',
            'fields' => [
            ],
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            }
        ];
        parent::__construct($config);
    }

    /**
     * 反回指定文章
     *
     * @param tag:ID 列表类型
     * @param offset:int=0 偏移量
     * @param length:int=10 取数据条数
     * @return Article
     */
    public function articlelistField($val, $args, $ctx, $info)
    {
    }

    /**
     * 验证码链接
     *
     * @return string!
     */
    public function captchaUrl($val, $args, $ctx, $info)
    {
    }

}
