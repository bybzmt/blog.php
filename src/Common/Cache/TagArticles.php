<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Framework\ListCache;

/**
 * 文章评论列表
 */
class TagArticles extends ListCache
{
    protected $id;

    protected function _init($id)
    {
        $this->id = $id;
        $this->key .= $id;
    }

    protected function findRows(array $ids):array
    {
        return $this->getLazyRows('Article', $ids);
    }

    protected function load()
    {
        return $this->getTable('ArticleTag')->getArticleIds($this->id, 0, $this->size);
    }


}
