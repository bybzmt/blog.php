<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Blog\Common;

/**
 * 文章评论列表
 */
class ArticleComments extends Common\ListCache
{
    protected function getRows(array $ids):array
    {
        $articles = [];
        foreach ($ids as $id) {
            $articles[] = $this->_context->getLazyRow('Comment', $id);
        }
        return $articles;
    }

    protected function loadData(int $length):array
    {
        return $this->_context->getTable('Comment')->getList($this->list_id, 0, $length);
    }


}
