<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Framework\Row;

class Tag extends Row
{
    //标签文章列表
    public function getArticleList(int $offset, int $length)
    {
        //从标签列表缓存中取
        return $this->getCache('TagArticles', $this->id)->getlist($offset, $length);
    }

    //从标签列表缓存中取
    public function getArticleCount()
    {
        return $this->getCache('TagArticles', $this->id)->count();
    }

}
