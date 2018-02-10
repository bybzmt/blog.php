<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class Tag extends Common\Row
{
    //标签文章列表
    public function getArticleList(int $offset, int $length)
    {
        //从标签列表缓存中取
        return $this->_ctx->getCache('TagArticles', $this->id)->gets($offset, $length);
    }

    //从标签列表缓存中取
    public function getArticleCount()
    {
        return $this->_ctx->getCache('TagArticles', $this->id)->count();
    }

}
