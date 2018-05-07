<?php
namespace Bybzmt\Blog\Api\GraphQL\Type;

use Bybzmt\Blog\Api\GraphQL\Types;
use Bybzmt\Blog\Api\GraphQL\ObjectType;

class ArticleType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => [
                'id' => Types::nonNull(Types::id()),
            ],
        ];
        parent::__construct($config);
    }

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
    public function comments($offset, $length)
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
