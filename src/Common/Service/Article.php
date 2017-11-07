<?php
namespace Bybzmt\Blog\Common\Service;

use Bybzmt\Blog\Common;

class Article extends Common\Service
{

    //首页列表
    public function getIndexList(int $offset, int $lenght)
    {
        //从首页列表缓存中取
        $rows = $this->getListCache('IndexArticle')->gets($offset, $length);
    }

    //标签文章列表
    public function getTagList(Domain\Tag $tag, int $offset, int $lenght)
    {
        //从标签列表缓存中取
    }

    //文章详情
    public function get(int $id)
    {
    }

    //添加新文章
    public function add(string $title, string $intro, string $content)
    {
        //保存数据
        //更新缓存
    }


}
