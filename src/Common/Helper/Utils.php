<?php
namespace Bybzmt\Blog\Common\Helper;

use Bybzmt\Framework\Helper\Utils as Base;
use Bybzmt\Framework\Config;
use Bybzmt\Imagefilter;

/**
 * 实用工具
 */
class Utils extends Base
{
	/**
	 * 异步任务(立刻执行)
	 *
	 * @param string $action 动作路径，对应backend模块下的控制器
	 * @param array $parms 参数，作为GET参数传给控制器
	 */
	public function asyncTask($action, array $params=array())
	{
		$redis = $this->getHelper("Resource")->getRedis();
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
	public function timerTask($time, $action, array $params=array())
	{
		$db = $this->getHelper("Resource")->getDb("blog_master");
		$db->insert('timer_task', array(
			'run_time' => date('Y-m-d H:i:s', $time),
			'action' => $action,
			'params' => http_build_query($params),
		));
	}

	/**
	 * 得到图片链接处理
     * 借由nginx缓存在访问连接中带缩放参数延后处理图片
	 */
	public function imageUrlEncode($path, $op, $width=0, $height=0, $format="", $anchor="")
	{
		$key = Config::get('imagefilter.signatureKey');
		Imagefilter::$signatureKey = $key;

		$tmp = new Imagefilter();
		return $tmp->build_url($path, $op, $width, $height, $format, $anchor);
	}

	/**
	 * 图片链接解码
	 */
	public function imageUrlDecode($url)
	{
		$path = parse_url($url, PHP_URL_PATH);
		if (!$path) {
			return false;
		}

		$base_url = Config::get("domain.image");
		$base_url = parse_url($base_url, PHP_URL_PATH);
		if ($base_url && strlen($base_url) > 1 && strpos($path, $base_url) === 0) {
			$path = substr($path, strlen($base_url));
		}

		$image_url = new Imagefilter();
		$path = $image_url->decode($path);
		if (!$path) {
			false;
		}

		return $path['path'];
	}
}
