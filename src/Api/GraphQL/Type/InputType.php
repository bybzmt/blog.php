<?php
namespace GraphQL\Examples\Blog\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class InputType extends ObjectType
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
     * 用户登陆
     *
     * @param user:string! 用户名
     * @param pass:string! 密码
     * @param captcha:string! 验证码
     * @return User
     */
    public function login($val, $args, $ctx, $info)
    {
    }

    /**
     * 登出
     *
     * @return Boolean
     */
    public function logout($val, $args, $ctx, $info)
    {
    }

    /**
     * 发表评论
     *
     * @param article:ID! 评论文章
     * @param reply:ID 被回复的评论
     * @param context:string! 回复内容
     * @return Comment
     */
    public function commentAdd($val, $args, $ctx, $info)
    {
    }
}
