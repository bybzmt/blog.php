<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class User extends Common\Row
{
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

        $ok = $this->_ctx->getTable("User")->update($this->id, array('pass'=>$saved));
        if ($ok) {
            $this->pass = $saved;
        }
        return $ok;
    }

    //修改昵称
    public function setNickname($nickname)
    {
        $ok = $this->_ctx->getTable("User")->update($this->id, array('nickname'=>$nickname));
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
        $id = $this->_ctx->getTable('Article')->insert($data);

        return $id;
    }

    public function getArticles(int $offset, int $length)
    {
        $ids = $this->_ctx->getTable("Article")->getUserListIds($this->id, $offset, $length);

        return $this->_ctx->getLazyRows("Article", $ids);
    }

    public function getArticleCount()
    {
        return $this->_ctx->getTable("Article")->getUserListCount($this->id);
    }

    public function getRecords(int $offset, int $length)
    {
        $rows = $this->_ctx->getTable("Record")->getList($this->id, $offset, $length);

        $records = array();
        foreach ($rows as $row) {
            $records[] = $this->_ctx->initRow("Record", $row);
        }
        return $records;
    }

    public function getRecordCount()
    {
        return $this->_ctx->getTable("Record")->getListCount($this->id);
    }

    public function loginlog(int $offset, int $length)
    {
    }

    public function disable()
    {
        $ok = $this->_ctx->getTable("User")->update($this->id, array('status'=>0));
        if ($ok) {
            $this->status = 0;
        }
        return $ok;
    }

    public function enable()
    {
        $ok = $this->_ctx->getTable("User")->update($this->id, array('status'=>1));
        if ($ok) {
            $this->status = 1;
        }
        return $ok;
    }
}
