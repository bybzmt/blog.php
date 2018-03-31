<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Framework\ListCache;

/**
 * 文章评论列表
 */
class TagArticles extends ListCache
{
    protected function findRows(array $ids):array
    {
        return $this->getLazyRows('Article', $ids);
    }

    protected function loadData(int $length):array
    {
        return $this->getTable('ArticleTag')->getArticleIds($this->list_id, 0, $length);
    }


}
