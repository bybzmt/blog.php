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
        $ids = $this->_context->getTable('Comment')->getListIds($this->list_id, 0, $length);
        $out = array();
        foreach ($ids as $id) {
            $out[] = $this->list_id.":".$id;
        }
        return $out;
    }


}
