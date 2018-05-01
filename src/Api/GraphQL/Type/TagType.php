<?php
namespace GraphQL\Examples\Blog\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class TagType extends ObjectType
{
    /**
     * @type ID!
     */
    public $id;

    /**
     * @type string
     */
    public $name;
}
