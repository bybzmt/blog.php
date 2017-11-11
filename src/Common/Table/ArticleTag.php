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
        $sql = "select article_id from article_tags where tag_id = ? limit $offset, $length";

        $stmt = $this->getSlave()->prepare($sql);
        $stmt->execute([$tag_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getTags(int $article_id)
    {
        $sql = "select tag_id from article_tags where article_id = ?";

        $stmt = $this->getSlave()->prepare($sql);
        $stmt->execute([$article_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function setTags(int $article_id, array $tag_ids)
    {
        $sql = "delete from article_tags where article_id = ?";
        $stmt = $this->getSlave()->prepare($sql);
        $stmt->execute([$article_id]);

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
