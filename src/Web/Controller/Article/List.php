<?php
namespace Bybzmt\Blog\Web\Controller;

use Bybzmt\Blog\Web\Controller;

class Article_List extends Controller
{
    private $page;
    private $offset;
    private $length = 10;

    public function init()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }

        $this->page = $page;
        $this->offset = ($page-1) * $this->length;
    }

    public function run()
    {
        //文章列表
        $article_rows = $this->getService('Article')->getIndexList($this->offset, $this->length);

        $articles = [];
        foreach ($article_rows as $row) {
            $article_tags = [];
            foreach ($row->getTags() as $tag) {
                $article_tags[] = ['id'=>$tag->id, 'name'=>$tag->name];
            }

            $articles[] = [
                'id' => $row->id,
                'title' => $row->title,
                'intro' => $row->intro,
                'addtime' => $row->addtime,
                'author_id' => $row->author->id,
                'author_nickname' => $row->author->nickname,
                'comments_num' => $row->getCommentsNum(),
                'classify_id' => $classify->id,
                'classify_name' => $classify->name,
                'tags' => $article_tags,
            ];
        }

        $data = [
            'article_list' => $articles,
        ];

        $this->render($data);
    }


}
