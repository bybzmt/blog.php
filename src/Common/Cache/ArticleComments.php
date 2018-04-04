<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Framework\ListCache;

/**
 * 文章评论列表
 */
class ArticleComments extends ListCache
{
    protected $id;

    protected function _init($id)
    {
        $this->id = $id;
        $this->key .= $id;
    }

    protected function findRows(array $ids):array
    {
        return $this->getLazyRows('Comment', $ids);
    }

    protected function load()
    {
        $ids = $this->getTable('Comment')->getListIds($this->id, 0, $this->size);
        $out = array();
        foreach ($ids as $id) {
            $out[] = $this->id.":".$id;
        }
        return $out;
    }


}
