<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;

class ArticleTag extends Common\Table
{
    protected $_dbName = 'blog';
    protected $_tableName = 'article_tags';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'article_id',
        'tag_id',
        'sort',
    ];

    public function getArticleIds(int $tag_id, int $offset, int $length)
    {
        return $this->getSlave()->findColumnAll($this->_tableName, 'article_id', array('tag_id'=>$tag_id), $offset, $length);
    }

    public function getTags(int $article_id)
    {
        return $this->getSlave()->findColumnAll($this->_tableName, 'tag_id', array('article_tags'=>$article_id));
    }

    public function setTags(int $article_id, array $tag_ids)
    {
        $this->getMaster()->delete($this->_tableName, array('article_id'=>$article_id));

        $data = [];
        foreach ($tag_ids as $sort => $tag_id) {
            $data[] = array(
                'article_id' => $article_id,
                'tag_id' => $tag_id,
                'sort' => $sort,
            );
        }

        return $this->adds($data);
    }
}
