<?php
namespace Bybzmt\Blog\Common\Cache;

/**
 * 首页文章列表
 */
class IndexArticles extends ListCache
{
    protected function getRows(array $ids): array
    {
        return $this->_ctx->getLazyRows('Article', $ids);
    }

    protected function loadData(int $length): array
    {
        return $this->_ctx->getTable('Article')->getIndexIds(0, $length);
    }


}
