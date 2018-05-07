<?php
namespace Bybzmt\Blog\Api\GraphQL\Type;

use Bybzmt\Blog\Api\GraphQL\Types;
use Bybzmt\Blog\Api\GraphQL\ObjectType;

class QueryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => [
                'ArticleList' => [
                    'type' => Types::get("Article"),
                    'description' => 'Article description',
                    'args' => [
                        'offset' => Types::int(),
                        'length' => Types::int(),
                        'id' => Types::nonNull(Types::id()),
                    ]
                ],
                'CommentList' => Types::get("Comment"),
                'Reply' => Types::get("Reply"),
                'Tag' => Types::get("Tag"),
                'User' => Types::get("User"),
            ],
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
    public function articleListResolver($id, $offset=0, $length=10)
    {
        return array(
            'id' => 1
        );
    }

    /**
     * 验证码链接
     *
     * @return string!
     */
    public function captchaUrl()
    {
    }

}
