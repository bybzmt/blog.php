<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class Tag extends Common\Row
{
    public $id;
    public $name;
    public $sort;
    public $status;
    public $top;

    protected function init(array $row)
    {
        $this->id = (int)$row['id'];
        $this->name = $row['name'];
        $this->sort = (int)$row['sort'];
        $this->status = (int)$row['status'];
        $this->top = (int)$row['top'];
    }

    //标签文章列表
    public function getArticleList(int $offset, int $length)
    {
        //从标签列表缓存中取
        return $this->_context->getCache('TagArticles', $this->id)->gets($offset, $length);
    }

    //从标签列表缓存中取
    public function getArticleCount()
    {
        return $this->_context->getCache('TagArticles', $this->id)->count();
    }

}
