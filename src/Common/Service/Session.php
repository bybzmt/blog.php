<?php
namespace Bybzmt\Blog\Common\Service;

use Bybzmt\Blog\Common;
use SessionHandlerInterface;
use Memcached;

/**
 * 安全
 */
class Session extends Common\Service implements SessionHandlerInterface
{
    protected $_prefix = "session_";
    protected $_expiration = 60*60*2;

    public function close()
    {
        return true;
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    public function open($save_path, $session_name)
    {
        return true;
    }

    public function read($sid)
    {
        $res = $this->_ctx->getMemcached()->get($this->_prefix.$sid, null, Memcached::GET_EXTENDED);
        if ($res) {
            return $res['value'];
        } else {
            //判断确实未找到,而非memcache服务器出问题了
            if ($this->_ctx->getMemcached()->getResultCode() == Memcached::RES_NOTFOUND) {
                $this->_ctx->getService("Security")->inrc_newSession();
            }
            return '';
        }
    }

    public function write($sid, $session_data)
    {
        return $this->_ctx->getMemcached()->set($this->_prefix.$sid, $session_data, $this->_expiration);
    }

    public function destroy($sid)
    {
        return $this->_ctx->getMemcached()->delete($this->_prefix.$sid);
    }

    public function create_sid()
    {
        return sha1(microtime(true).mt_rand());
    }
}
