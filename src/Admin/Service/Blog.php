<?php
namespace Bybzmt\Blog\Admin\Service;

use Bybzmt\Framework\Service;


class Blog extends Service
{
    public function getArticleList($type, $search, $offset, $length)
    {
        list($rows, $count) = $this->getTable("Article")->getAdminList($type, $search, $offset, $length);

        $objs = [];
        foreach ($rows as $row) {
            $objs[] = $this->initRow("Article", $row);
        }

        return [$objs, $count];
    }

    public function getCommentTables()
    {
        $tables = [];
        $tmp = $this->getTable("AdminComment");
        for ($i=0; $i<$tmp->commentTableNum; $i++) {
            $tables[] = $tmp->commentTablePrefix . $i;
        }
        for ($i=0; $i<$tmp->replyTableNum; $i++) {
            $tables[] = $tmp->replyTablePrefix . $i;
        }
        return $tables;
    }

    public function isCommentTable($table)
    {
        $_table = $this->getTable("AdminComment");
        return strncmp($_table->commentTablePrefix, $table, strlen($_table->commentTablePrefix)) == 0;
    }

    public function getCommentList($table, $type, $search, $offset, $length)
    {
        $_table = $this->getTable("AdminComment");
        list($rows, $count) = $_table->getList($table, $type, $search, $offset, $length);

        $rowName = $this->isCommentTable($table) ? 'Comment' : 'Reply';

        $tmp = array();
        foreach ($rows as $row) {
            $tmp[] = $this->initRow($rowName, $row);
        }
        $rows = $tmp;

        return [$rows, $count];
    }

}
