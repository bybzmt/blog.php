<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Framework\Row;

class User extends Row
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

        $ok = $this->_ctx->get("Table.User")->update($this->id, array('pass'=>$saved));
        if ($ok) {
            $this->pass = $saved;
        }
        return $ok;
    }

    //修改昵称
    public function setNickname($nickname)
    {
        $ok = $this->_ctx->get("Table.User")->update($this->id, array('nickname'=>$nickname));
        if ($ok) {
            $this->nickname = $nickname;
        }
        return $ok;
    }

    public function getRecords(int $offset, int $length)
    {
        $rows = $this->_ctx->get("Table.Record")->getList($this->id, $offset, $length);

        $records = array();
        foreach ($rows as $row) {
            $records[] = $this->_ctx->initRow("Record", $row);
        }
        return $records;
    }

    public function getRecordCount()
    {
        return $this->_ctx->get("Table.Record")->getListCount($this->id);
    }

    public function loginlog(int $offset, int $length)
    {
    }

    public function disable()
    {
        $ok = $this->_ctx->get("Table.User")->update($this->id, array('status'=>0));
        if ($ok) {
            $this->status = 0;
        }
        return $ok;
    }

    public function enable()
    {
        $ok = $this->_ctx->get("Table.User")->update($this->id, array('status'=>1));
        if ($ok) {
            $this->status = 1;
        }
        return $ok;
    }
}
