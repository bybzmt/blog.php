<?php
namespace Bybzmt\Blog\Service\Domain;

class User extends Base
{
    public $id;
    public $user;
    public $pass;
    public $nickname;
    public $status;

    protected function init(array $row)
    {
        $this->id = (int)$row['id'];
        $this->user = $row['user'];
        $this->pass = $row['pass'];
        $this->nickname = $row['nickname'];
        $this->status = (int)$row['status'];
    }

    public function addArticle($title, $intro, $content)
    {
        $data = array(
            'user_id' => $this->id,
            'title' => $title,
            'intro' => $intro,
            'content' => $content,
            'addtime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'edittime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'status' => 0,
            'top' => 0,
            'cache_tags' => '',
            'cache_comments_num' => 0,
        );

        //保存数据
        $id = $this->getTable('Article')->insert($data);
        if ($id) {
            $data['id'] = $id;

            //更新缓存
            $this->getCache('RowCache', 'Article')->set($id, $data);
        }

        return $id;
    }

    public function getArticleList(int $offset, int $length)
    {
    }

    public function getComments(int $offset, int $length)
    {
    }

    public function loginlog(int $offset, int $length)
    {
    }

    public function getOrders(int $offset, int $length)
    {
    }

    public function getAccounts()
    {
    }

}
