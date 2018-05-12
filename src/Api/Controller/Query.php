<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller as Base;
use Bybzmt\Blog\Web\Bootstrap as Web;

class Query extends Base
{
    public function articleList($tag_id, $offset=0, $length=10)
    {
        //文章列表
        if ($tag_id) {
            $tag = $this->getRow('Tag', $tag_id);
            if (!$tag) {
                return [];
            }

            $articles = $tag->getArticleList($offset, $length);
        } else {
            $articles = $this->getService("Article")->getIndexList($offset, $length);
        }

        return $articles;
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
