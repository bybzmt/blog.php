<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller as Base;

class User extends Base
{
    /**
     * @type ID!
     */
    public $id;

    /**
     * @type string
     */
    public $nickname;
}
