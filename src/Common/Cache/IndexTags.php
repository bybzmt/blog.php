<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Blog\Common;

/**
 * 文章评论列表
 */
class IndexTags extends Common\ListCache
{
    protected function getRows(array $ids):array
    {
        $articles = [];
        foreach ($ids as $id) {
            $articles[] = $this->_context->getLazyRow('Tag', $id);
        }
        return $articles;
    }

    protected function loadData(int $length):array
    {
        return $this->_context->getTable('Tag')->getIndexTagIds();
    }


}
