<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Blog\Common;

/**
 * 文章评论列表
 */
class ArticleComments extends Common\ListCache
{
    private $article_id;

    public function __construct(Context $context, string $article_id)
    {
        parent::__construct($context);

        $this->article_id = $article_id;
        $this->key .= $article_id;
    }

    protected function getRows(array $ids)
    {
        $articles = [];
        foreach ($ids as $id) {
            $articles[] = $this->getLazyRowCache('Comment')->get($id);
        }
        return $articles;
    }

    protected function loadData(int $length)
    {
        return $this->getTable('Comment')->getList($this->article_id, 0, $length);
    }


}
