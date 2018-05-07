<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller as Base;

class Comment extends Base
{
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
