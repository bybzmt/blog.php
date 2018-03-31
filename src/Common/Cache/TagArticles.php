<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Framework\ListCache;

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
        return $this->_ctx->get('Table.ArticleTag')->getArticleIds($this->list_id, 0, $length);
    }


}
