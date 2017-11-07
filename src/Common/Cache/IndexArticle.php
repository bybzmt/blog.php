<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Blog\Common;

/**
 * 首页文章列表
 */
class IndexArticle extends Common\ListCache
{
    protected function getRows(array $ids)
    {
        $articles = [];
        foreach ($ids as $id) {
            $articles[] = $this->getLazyRowCache('Article')->get($id);
        }
        return $articles;
    }

    protected function loadData(int $length)
    {
        return $this->getTable('Article')->getIndexIds(0, $length);
    }


}
