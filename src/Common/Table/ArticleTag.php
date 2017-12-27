<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;
use PDO;

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
        $sql = "SELECT article_id FROM {$this->_tableName} WHERE tag_id = ? LIMIT $offset,$length";

        return $this->query($sql, [$tag_id])->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getTags(int $article_id)
    {
        $sql = "select tag_id FROM {$this->_tableName} where article_id = ?";

        return $this->query($sql, [$article_id])->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function setTags(int $article_id, array $tag_ids)
    {
        $sql = "DELETE FROM {$this->_tableName} WHERE article_id = ?";
        $this->exec($sql, [$article_id]);

        $data = [];
        foreach ($tag_ids as $sort => $tag_id) {
            $data[] = array(
                'article_id' => $article_id,
                'tag_id' => $tag_id,
                'sort' => $sort,
            );
        }

        return $this->inserts($data);
    }
}
