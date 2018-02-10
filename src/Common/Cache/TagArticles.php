<?php
namespace Bybzmt\Blog\Common\Cache;

/**
 * 文章评论列表
 */
class TagArticles extends ListCache
{
    protected function getRows(array $ids):array
    {
        return $this->_ctx->getLazyRows('Article', $ids);
    }

    protected function loadData(int $length):array
    {
        return $this->_ctx->getTable('ArticleTag')->getArticleIds($this->list_id, 0, $length);
    }


}
