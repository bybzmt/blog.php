<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Framework\ListCache;

/**
 * 文章评论列表
 */
class ArticleComments extends ListCache
{
    protected function findRows(array $ids):array
    {
        return $this->getLazyRows('Comment', $ids);
    }

    protected function loadData(int $length):array
    {
        $ids = $this->getTable('Comment')->getListIds($this->list_id, 0, $length);
        $out = array();
        foreach ($ids as $id) {
            $out[] = $this->list_id.":".$id;
        }
        return $out;
    }


}
