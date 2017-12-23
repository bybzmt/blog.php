<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Blog\Common;

/**
 * 首页文章列表
 */
class IndexArticles extends Common\ListCache
{
    protected function getRows(array $ids): array
    {
        $articles = [];
        foreach ($ids as $id) {
            $articles[] = $this->_context->getLazyRow('Article', $id);
        }
        return $articles;
    }

    protected function loadData(int $length): array
    {
        return $this->_context->getTable('Article')->getIndexIds(0, $length);
    }


}
