<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\AuthWeb;
use Bybzmt\Blog\Web\Reverse;
use Bybzmt\Blog\Web\Exception;
use Bybzmt\Blog\Common\Helper\Pagination;

class ArticleAddExec extends AuthWeb
{
    public $msg;
    public $user;

    public $title;
    public $tags;
    public $intro;
    public $content;

    public function init()
    {
        $this->title = trim($this->getPost("title"));
        $this->intro = trim($this->getPost("intro"));
        $this->content = $this->getPost("content");
        $tag = $this->getPost("tag");

        $tags = preg_split("/，|,|\s/", $tags);
        $this->tags = array_filter(array_map('trim', $tags));
    }

    public function valid()
    {
        //验证安全情况
        if ($this->getHelper("Security")->isLocked()) {
            $this->error = "操作过于频繁请明天再试!";
            return false;
        }

        if (!$this->title) {
            $this->msg = "标题不能为空";
            return false;
        }

        if (!$this->intro) {
            $this->msg = "简介不能为空";
            return false;
        }

        if (!$this->intro) {
            $this->msg = "简介不能为空";
            return false;
        }

        if (!$this->content) {
            $this->msg = "正文不能为空";
            return false;
        }

        $this->user = $this->getRow("User", $this->_uid);
        if (!$this->user) {
            throw new Exception("uid:{$this->_uid} not exists.");
        }

        return true;
    }

    public function exec()
    {
        //发表文章次数
        $this->getHelper("Security")->incr_addArticle();

        $service = $this->getService("Article");

        $id = $service->addArticle($this->user, $this->title, $this->intro, $this->content);
        if (!$id) {
            throw new Exception("add article fail.");
        }

        $article = $this->getRow("Article", $id);
        if (!$article) {
            throw new Exception("add article id:{$id} not exists.");
        }


        $tags = array();
        foreach ($this->tags as $tag) {
            $obj = $service->getTag($tag);
            if (!$obj) {
                $tag_id = $service->addTag($tag);
                if (!$tag_id) {
                    throw new Exception("add tag fail.");
                }

                $obj = $this->getRow("Tag", $tag_id);
                if (!$obj) {
                    throw new Exception("add tag id:{$tag_id} not exists.");
                }
            }

            $tags[] = $obj;
        }
        if ($tags) {
            $ok = $article->setTags(...$tags);
            if (!$ok) {
                throw new Exception("article setTag fail.");
            }
        }

        return true;
    }

    public function fail()
    {
        echo json_encode(['ret'=>1, 'data'=>$this->msg]);
    }

    public function show()
    {
        echo json_encode(['ret'=>0, 'data'=>'保存成功']);
    }

}
