<?php
namespace GraphQL\Examples\Blog\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class ArticleType extends ObjectType
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
    public $title;

    /**
     * @type string
     */
    public $intro;

    /**
     * @type string
     */
    public $html;

    /**
     * @type string
     */
    public $addtime;

    /**
     * @type string
     */
    public $edittime;

    /**
     * 评论列表
     *
     * @param offset:int=0 偏移量
     * @param length:int=10 取数据条数
     * @return [Comment]
     */
    public function comments($val, $args, $ctx, $info)
    {
    }

    /**
     * 评论列表数量
     *
     * @return int
     */
    public function commentsNum()
    {
    }

    /**
     * 文章标签列表
     *
     * @return [Tag]
     */
    public function tags()
    {
    }

}
