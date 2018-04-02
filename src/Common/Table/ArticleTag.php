<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Framework\Table;
use PDO;

class ArticleTag extends Table
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

    //得到指定标签下己发布的文章id
    public function getArticleIds(int $tag_id, int $offset, int $length)
    {
        $sql = "SELECT A.article_id FROM {$this->_tableName} AS A
            RIGHT JOIN articles AS B ON (A.article_id = B.id)
            WHERE A.tag_id = ? AND B.status = 3 LIMIT $offset,$length";

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
            $this->insert(array(
                'article_id' => $article_id,
                'tag_id' => $tag_id,
                'sort' => $sort,
            ));
        }

        return true;
    }
}
