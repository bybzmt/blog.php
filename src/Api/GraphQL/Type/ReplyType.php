<?php
namespace GraphQL\Examples\Blog\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class ReplyType extends ObjectType
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

}
