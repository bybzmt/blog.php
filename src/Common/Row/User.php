<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class User extends Common\Row
{
    public $id;
    public $user;
    public $pass;
    public $nickname;
    public $addtime;
    public $status;

    protected function init(array $row)
    {
        $this->id = (int)$row['id'];
        $this->user = $row['user'];
        $this->pass = $row['pass'];
        $this->nickname = $row['nickname'];
        $this->addtime = strtotime($row['addtime']);
        $this->status = (int)$row['status'];
    }

    public function encryptPass($pass)
    {
        //密码摘要，密钥确定后不可更改
        return hash_hmac('md5', $pass, $this->id.'encryptkey');
    }

    //验证用户密码
    public function validPass(string $pass)
    {
        return $this->encryptPass($pass) == $this->pass;
    }

    public function setPass(string $pass)
    {
        $saved = $this->encryptPass($pass);

        $ok = $this->_context->getTable("User")->update($this->id, array('pass'=>$saved));
        if ($ok) {
            $this->pass = $saved;
        }
        return $ok;
    }

    //修改昵称
    public function setNickname($nickname)
    {
        $ok = $this->_context->getTable("User")->update($this->id, array('nickname'=>$nickname));
        if ($ok) {
            $this->nickname = $nickname;
        }
        return $ok;
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
        $id = $this->_context->getTable('Article')->insert($data);

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

    public function disable()
    {
        $ok = $this->_context->getTable("User")->update($this->id, array('status'=>0));
        if ($ok) {
            $this->status = 0;
        }
        return $ok;
    }

    public function enable()
    {
        $ok = $this->_context->getTable("User")->update($this->id, array('status'=>1));
        if ($ok) {
            $this->status = 1;
        }
        return $ok;
    }
}
