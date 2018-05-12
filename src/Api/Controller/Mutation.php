<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller as Base;

class Input extends ObjectType
{
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
