<?php
namespace Bybzmt\Blog\Common\Helper;

use Bybzmt\Framework\Helper\Resource as Base;
use Bybzmt\Framework\Config;
use Bybzmt\Locker\SocketLock;
use Bybzmt\Locker\FileLock;
use Bybzmt\HttpStorage\SimpleHttpStorage;

//连接各种外部资源
class Resource extends Base
{
    protected $fileManagers;

	public function getLocker($key)
	{
        $config = Config::get("locker");

        switch($config['type']) {
        case 'socket':
            return new SocketLock($key, $config["host"], $config["port"], $config["timeout"]);
        case 'file':
            return new FileLock($key);
        default:
            throw new Exception("未定义的锁类型: {$config['type']}");
        }
	}

	/**
	 * 得到文件管理服务
	 * @param string 文件管理服务器名
	 * @return bybzmt\HttpStorage\SimpleHttpStorage
	 */
	public function getFileManager($name='default')
	{
		if (!isset($this->fileManagers[$name])) {
			$config = Config::get("fileManager.$name");

			$storage = new SimpleHttpStorage(
				$config['host'], $config['port'], $config['timeout']
			);

			$this->fileManagers[$name] = $storage;
		}

		return $this->fileManagers[$name];
	}
}
