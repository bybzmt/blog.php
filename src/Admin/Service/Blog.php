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

    public function getCommentList($type, $search, $offset, $length)
    {
        list($rows, $count) = $this->getTable("Comment")->getAdminList($type, $search, $offset, $length);

        $objs = [];
        foreach ($rows as $row) {
            $objs[] = $this->initRow("Comment", $row);
        }

        return [$objs, $count];
    }

}
