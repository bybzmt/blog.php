<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Framework\ListCache;

/**
 * 首页文章列表
 */
class IndexArticles extends ListCache
{
    protected function findRows(array $ids): array
    {
        return $this->getLazyRows('Article', $ids);
    }

    protected function load()
    {
        return $this->getTable('Article')->getIndexIds(0, $this->size);
    }


}
