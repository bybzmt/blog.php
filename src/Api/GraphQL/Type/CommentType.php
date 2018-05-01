<?php
namespace GraphQL\Examples\Blog\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class CommentType extends ObjectType
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
     * @type ID!
     */
    public $id;

    /**
     * @return User
     */
    public function user()
    {
    }

    /**
     * @type string
     */
    public $content;

    /**
     * @type string
     */
    public $addtime;

    /**
     * 回复列表
     *
     * @param offset:int=0 偏移量
     * @param length:int=10 取数据条数
     * @return [Reply]
     */
    public function replys($val, $args, $ctx, $info)
    {
    }

    /**
     * 回复数量
     *
     * @return int
     */
    public function replysNum()
    {
    }

}
