<?php
namespace Bybzmt\Blog\Common\Service;

use Bybzmt\Blog\Common;

class Article extends Common\Service
{

    //首页列表 (从首页列表缓存中取)
    public function getIndexList(int $offset, int $length)
    {
        return $this->getCache('IndexArticles')->gets($offset, $length);
    }

    //首页列表文章数量 (从首页列表缓存中取)
    public function getIndexCount()
    {
        return $this->getCache('IndexArticles')->count();
    }

    public function getIndexTags()
    {
        return $this->getCache('IndexTags')->gets(0, 50);
    }

}
