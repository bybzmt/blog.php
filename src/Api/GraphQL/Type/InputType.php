<?php
namespace GraphQL\Examples\Blog\Type;

use GraphQL\Type\Definition\Type;
use Bybzmt\Blog\Api\GraphQL\Types;
use Bybzmt\Blog\Api\GraphQL\ObjectType;

class InputType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => [
            ]
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
    public function login($user, $pass, $captcha)
    {
    }

    /**
     * 登出
     *
     * @return Boolean
     */
    public function logout()
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
    public function commentAdd($article, $reply, $context)
    {
    }
}
