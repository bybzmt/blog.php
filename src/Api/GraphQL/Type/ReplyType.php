<?php
namespace Bybzmt\Blog\Api\GraphQL\Type;

use Bybzmt\Blog\Api\GraphQL\Types;
use Bybzmt\Blog\Api\GraphQL\ObjectType;

class ReplyType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => [
                'id' => Types::nonNull(Types::id()),
            ]
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

}
