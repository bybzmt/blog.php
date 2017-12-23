<?php
namespace Bybzmt\Blog\Admin\Service;

use Bybzmt\Blog\Admin;

class Blog extends Admin\Service
{
    public function getArticleList($type, $search, $offset, $length)
    {
        list($rows, $count) = $this->_context->getTable("Article")->getAdminList($type, $search, $offset, $length);

        $objs = [];
        foreach ($rows as $row) {
            $objs[] = $this->_context->initRow("Article", $row);
        }

        return [$objs, $count];
    }

    public function getCommentList($type, $search, $offset, $length)
    {
        list($rows, $count) = $this->_context->getTable("Comment")->getAdminList($type, $search, $offset, $length);

        $objs = [];
        foreach ($rows as $row) {
            $objs[] = $this->_context->initRow("Comment", $row);
        }

        return [$objs, $count];
    }

}
