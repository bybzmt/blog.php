<?php
namespace Bybzmt\Blog\Api\GraphQL\Type;

use Bybzmt\Blog\Api\GraphQL\Types;
use Bybzmt\Blog\Api\GraphQL\ObjectType;

class CommentType extends ObjectType
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
    public function replys($offset, $length)
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
