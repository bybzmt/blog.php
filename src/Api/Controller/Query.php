<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller as Base;
use Bybzmt\Blog\Web\Bootstrap as Web;

class Query extends Base
{
    public function articleList($tagId, $offset=0, $length=10)
    {
        //文章列表
        if ($tagId) {
            $tag = $this->getRow('Tag', $tagId);
            if (!$tag) {
                return [];
            }

            $articles = $tag->getArticleList($offset, $length);
            $count = $tag->getArticleCount();
        } else {
            $articles = $this->getService("Article")->getIndexList($offset, $length);
            $count = $this->getService("Article")->getIndexCount();
        }

        return [
            'items' => $articles,
            'count' => $count,
            'length' => $length,
        ];
    }

    public function captchaUrl()
    {
        return Web::getContext()->getComponent("Helper\\Utils")->mkUrl("User.Captcha");
    }

    public function user()
    {
        $user_id = $this->getHelper("Session")->get('user_id');
        if (!$user_id) {
            return null;
        }

        return $this->getRow("User", $user_id);
    }

    public function article($id)
    {
        return $this->getRow("Article", $id);
    }

}
