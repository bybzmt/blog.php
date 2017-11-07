<?php
namespace Galaxy\Core;

use Galaxy\Library;

/**
 * 基础组件
 */
class Utils
{
	static public $di;
	static private $_params = array();
	static private $_dbs = array();
	static private $_memcacheds = array();
	static private $_redis = array();
	static private $_loggers = array();
	static private $_fileManagers = array();
	static private $_imServices = array();

	/**
	 * 得到Di
	 * @return Phalcon\DiInterface
	 */
	static public function getDi()
	{
		return self::$di;
	}

	/**
	 * 得到配置文件
	 * 多级配置以"."作分隔aa.bb.cc将会取$config['aa']['bb']['cc']
	 */
	static public function getConfig($keys)
	{
		$keys = explode('.', $keys);

		$config = self::$di->get("config");
		foreach ($keys as $key) {
			if (!isset($config[$key])) {
				return null;
			}

			$config = $config[$key];
		}
		return $config;
	}

	/**
	 * 得到数据库连接
	 * @param string $name 数据库名
	 * @return Phalcon\Db\Adapter
	 */
	static public function getDb($name)
	{

		if (!isset(self::$_dbs[$name])) {
			$configs = self::$di->get("config")['database'][$name];

			$num = count($configs);
			$conf = $num > 1 ? $configs[mt_rand(0, $num-1)] : $configs[0];

			$dsn = "mysql:dbname={$conf['dbname']};host={$conf['host']};charset={$conf['charset']}";

			$opts = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
			);
			$db = new \bybzmt\DB\DB($dsn, $conf['username'], $conf['password'], $opts);

			self::$_dbs[$name] = $db;
		}

		return self::$_dbs[$name];
	}

	/**
	 * 得到memcached连接
	 * @param string $name memcached群组名
	 * @return Memcached
	 */
	static public function getMemcached($name='default')
	{
		if (!isset(self::$_memcacheds[$name])) {
			$config = self::$di->get("config")['memcached'][$name];
			$md = new \Memcached($config["persistent_id"]);
			$md->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
			$md->setOption(\Memcached::OPT_TCP_NODELAY, true);
			$md->setOption(\Memcached::OPT_NO_BLOCK, true);
			$md->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);

			$list = $config["servers"]->toArray();
			$now = $md->getServerList();
			if ($now != $list) {
				if ($now) {
					$md->resetServerList();
					$md->addServers($list);
				} else {
					$md->addServers($list);
				}
			}

			self::$_memcacheds[$name] = $md;
		}

		return self::$_memcacheds[$name];
	}

	/**
	 * 得到redis连接
	 * @param string $name redis名称
	 * @return Redis
	 */
	static public function getRedis($name='default')
	{
		if (!isset(self::$_redis[$name])) {
			$config = self::$di->get("config")['redis'][$name];

			$md = new \Redis();
			$md->connect($config["host"], $config["port"], $config["timeout"]);
			if ($config['password']) {
				$md->auth($config['password']);
			}
			//$md->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
			//$md->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
			//$md->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY);
			self::$_redis[$name] = $md;
		}

		return self::$_redis[$name];
	}

	//关闭redis
	static public function closeRedis($name='default')
	{
		unset(self::$_redis[$name]);
	}

	/**
	 * 得到日志记录器
	 * @param string $name 日志名
	 * @return Phalcon\Logger\Adapter
	 */
	static public function getLogger($name)
	{
		if (!isset(self::$_loggers[$name])) {
			$config = self::$di->get("config")['logger'][$name];

			switch ($config['type']) {
			case "file" :
				$logger = new \Phalcon\Logger\Adapter\File($config['filename'], array('mode' => 'a'));
				break;

			case "syslog":
				$logger = new \Phalcon\Logger\Adapter\Syslog(
					$config['ident'],
					array('facility' => $config['facility'])
				);
				break;

			default:
				throw new \Exception("logger type: {$config['type']}");
			}

			self::$_loggers[$name] = $logger;
		}

		return self::$_loggers[$name];
	}

	/**
	 * 得到锁服务
	 * @param string 锁名
	 * @return bybzmt\SocketLock\locker
	 */
	static public function getLocker($key)
	{
		$config = self::$di->get("config")['locker'];

		return new \bybzmt\SocketLock\Locker($key, $config["host"], $config["port"], $config["timeout"]);
	}


    /**                 开启内存并发锁：只保留第一个请求
     * @param $key      锁名称
     * @param $ttl      锁时长
     * @return bool     锁定结果
     */
    static public function Cache_Locke($key, $ttl)
    {
        $cache = self::getMemcached();
        if($cache->increment($key, 1, 0, $ttl) > 1) {
            return false;
        }

        $cache->touch($key, $ttl);
        return true;
    }

    /**                 设置内存锁时长
     * @param $key      锁名称
     * @param $ttl      锁时长
     */
    static public function Cache_TTL($key, $ttl)
    {
        $cache = self::getMemcached();
        $cache->touch($key, $ttl);
    }

    /**                 解除内存锁
     * @param $key      锁名称
     */
    static public function Cache_UnLock($key)
    {
        $cache = self::getMemcached();
        $cache->delete($key);
    }

	/**
	 * 得到文件管理服务
	 * @param string 文件管理服务器名
	 * @return bybzmt\HttpStorage\SimpleHttpStorage
	 */
	static public function getFileManager($name='default')
	{
		if (!isset(self::$_fileManagers[$name])) {
			$config = self::$di->get("config")['fileManager'][$name];

			$storage = new \bybzmt\HttpStorage\SimpleHttpStorage(
				$config['host'], $config['port'], $config['timeout']
			);

			self::$_fileManagers[$name] = $storage;
		}

		return self::$_fileManagers[$name];
	}

	/**
	 * 得到LRU缓存服务
	 */
	static public function getLRUCache($name='default')
	{
		$server_url = self::getConfig('LRUCache')[$name];
		return new \bybzmt\lrucache\LRUCache($server_url);
	}

	/**
	 * 得到IM推送服务
	 * @param string 服务器名
	 * @return bybzmt\phpim
	 */
	static public function getIMService($name='default')
	{
		$host = self::getConfig('IMServer')[$name];
		\bybzmt\phpim\phpim::$host = $host;

		return new \bybzmt\phpim\phpim();
	}

	/**
	 * 得到图片链接处理
	 * @param string 服务器名
	 * @return bybzmt\phpim
	 */
	static public function getImageUrl($path, $op, $width=0, $height=0, $format="", $anchor="")
	{
		$key = self::getConfig('imagefilter.signatureKey');
		\bybzmt\imagefilter::$signatureKey = $key;

		$tmp = new \bybzmt\imagefilter();
		return $tmp->build_url($path, $op, $width, $height, $format, $anchor);
	}

	/**
	 * 图片链接解码
	 */
	static public function ImageUrlDecode($url)
	{
		$path = parse_url($url, PHP_URL_PATH);
		if (!$path) {
			return false;
		}

		$base_url = self::getConfig("domain.image");
		$base_url = parse_url($base_url, PHP_URL_PATH);
		if ($base_url && strlen($base_url) > 1 && strpos($path, $base_url) === 0) {
			$path = substr($path, strlen($base_url));
		}

		$image_url = new \bybzmt\imagefilter();
		$path = $image_url->decode($path);
		if (!$path) {
			false;
		}

		return $path['path'];
	}

	static public function getMailer()
	{
		$mail = new \PHPMailer();
		//$mail->SMTPDebug = 3;                                // Enable verbose debug output
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'user@example.com';                 // SMTP username
		$mail->Password = 'secret';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;
		return $mail;
	}

	/**
	 * 异步任务(立刻执行)
	 *
	 * @param string $action 动作路径，对应backend模块下的控制器
	 * @param array $parms 参数，作为GET参数传给控制器
	 */
	static public function AsyncTask($action, array $params=array())
	{
		$default = self::getParam('task_default');
		if ($default) {
			$params += $default;
		}

		$redis = self::getRedis();

		$redis->RPUSH(Library\RedisKey::ASYNC_TASK_QUEUE_JSON, json_encode(array(
			'action' => $action,
			'params' => http_build_query($params)
		)));
	}

	/**
	 * 定时任务(精度为分)
	 *
	 * @param int $time 定时的时间点
	 * @param string $action 动作路径，对应backend模块下的控制器
	 * @param array $parms 参数，作为GET参数传给控制器
	 */
	static public function TimerTask($time, $action, array $params=array())
	{
		$default = self::getParam('task_default');
		if ($default) {
			$params += $default;
		}

		$db = self::getDb("yinher_master");
		$db->insert('timer_task', array(
			'run_time' => date('Y-m-d H:i:s', $time),
			'action' => $action,
			'params' => serialize($params),
		));
	}

	/**
	 * 设置参数(跨层参数传递用)
	 */
	static public function setParam($key, $param)
	{
		self::$_params[$key] = $param;
	}

	/**
	 * 取得参数(跨层参数传递用)
	 */
	static public function getParam($key)
	{
		return isset(self::$_params[$key]) ? self::$_params[$key] : null;
	}

	/**
	 * 创建url
	 *
	 * @param string $module 模块名
	 * @param string $action 控制器
	 * @param array $params 控制器参数
	 */
	static public function mkUrl($module, $action, array $params=array())
	{
		$base = self::getConfig('domain.'.$module);
		if (!$base) {
			throw new \Exception("module:{$module} not defined");
		}

        if($module == 'web') {
            $action_temp = strtolower($action);
            $action_temp = '/' . trim($action_temp, '/');

            switch ($action_temp)
            {
                case '/index/indexguest' :   //  游客页面
                    $action = '/guest' ;
                    if(array_key_exists('class_id', $params)) {
                        $action .= "/". $params['class_id'].'.html';
                        unset($params['class_id']);
                    }
                    break;
                case '/index/index' :       //  首页 , 分类页是同一个方法
                        $action = '/';
                    if(array_key_exists('class_id', $params)) {
                        $action .= $params['class_id'].'.html';
                        unset($params['class_id']);
                    }
                    break;
                case '/book/info' :         //  作品详情页
                    $action = '/book' ;
                    if(array_key_exists('bookid', $params)) {
                        $action .= "/".$params['bookid'].'.html';
                        unset($params['bookid']);
                    }
                    break;
                case '/chapter/read' :      //  阅读页
                    $action = '/read' ;
                    if(array_key_exists('bookid', $params) and array_key_exists('chapterid', $params)) {
                        $action .= "/".$params['bookid'].'/'.$params['chapterid'].'.html';
                        unset($params['bookid']);
                        unset($params['chapterid']);
                    }
                    break;
                case '/center/personal' :   //  他的个人中心
                    $action = '/personal' ;
                    if(array_key_exists('u_id', $params)) {
                        $action .= "/".$params['u_id'].'.html';
                        unset($params['u_id']);
                    }
                    break;
                case '/center/index' :   //  他的个人中心
                    $action = '/personal' ;
                    if(array_key_exists('u_id', $params)) {
                        $action .= "/".$params['u_id'].'.html';
                        unset($params['u_id']);
                    }elseif(array_key_exists('userid', $params)) {
                        $action .= "/".$params['userid'].'.html';
                        unset($params['userid']);
                    }
                    break;
                case '/index/search' :      //  搜索页
                    $action = '/search' ;
                    break;
                case '/booklist/index' :     //  书单详情页
                    $action = '/list' ;
                    if(array_key_exists('id', $params)) {
                        $action .= "/".$params['id'].'.html';
                        unset($params['id']);
                    }
                    break;
                case '/book/bookmap' :     //  网站地图
                    $action = '/book/map.html' ;
                    break;
            }
        }

		//TODO
		//bug! 这里有一个己知bug
		//域配置里把协议配置文件中，但实际协议有http和https，需要处理
		//这里临时处理
		//$base = str_replace("http://", $_SERVER['REQUEST_SCHEME'].'://', $base);

		$url = $base . $action;

		if ($params) {
			$query = http_build_query($params);
			if (strpos($url, '?') === false) {
				$url .= '?' . $query;
			} else {
				$url .= '&' . $query;
			}
		}

		return $url;
	}

    static public function getController()
    {
        $di = self::getDi();
        return $di['dispatcher']->getControllerName();
    }

    static public function getAction()
    {
        $di = self::getDi();
        return $di['dispatcher']->getActionName();
    }

    static public function checkPrivildge($code)
    {
        // 判断 $code 是否存在用的权限列表中 (Session)
//        if($_SESSION['super_admin']==1){
//            return true;
//        }

        if(!$code){
            return false;
        }

        list($controller,$action)=explode('_',$code);

        if(!isset($_SESSION['acl_actions'][$controller])){
            return false;
        }

        if(in_array($action,$_SESSION['acl_actions'][$controller]) || !$action){
            return true;
        }else{
            return false;
        }
    }

    //格式化时间
    static public function getTopicAddTime($time)
    {
        $today=date('Y-m-d');
        list($day)=explode(' ',$time);
        if($today==$day){
            $time=strtotime($time);
            $seconds=time()-$time;
            $second=$seconds%60;
            $minute=($seconds-$second)%3600/60;
            $hour=($seconds-$minute*60-$second)/3600;
            $str='';
            if($hour!=0){
                $str.=$hour.'小时';
            }
            if($minute!=0 || $hour!=0){
                $str.=$minute.'分';
            }
            if($second!=0 || $minute!=0 || $hour!=0){
                $str.=$second.'秒';
            }
            $str.='前';
            return $str;
        }else{
            return $day;
        }

    }

}
