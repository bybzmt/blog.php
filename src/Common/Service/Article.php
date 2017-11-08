<?php
namespace Bybzmt\Blog\Common\Service;

use Bybzmt\Blog\Common;

class Article extends Common\Service
{

    //首页列表
    public function getIndexList(int $offset, int $length)
    {
        //从首页列表缓存中取
        $rows = $this->getCache('IndexArticles')->gets($offset, $length);
        return $rows;
    }

    //标签文章列表
    public function getTagList(Domain\Tag $tag, int $offset, int $length)
    {
        //从标签列表缓存中取
    }

    //文章详情
    public function get(int $id)
    {
        return $this->getRowCache('Article', $id);
    }


}
