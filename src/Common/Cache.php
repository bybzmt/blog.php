<?php
namespace Bybzmt\Blog\Common;

abstract class Cache
{
    use Loader;

    //缓存过期时间
    protected $expiration = 1800;

    //key前缀
    protected $keyPrefix;

    //hash前缀
    protected $hashPrefix;

    //使用哪个memcached
    protected $memcachedName = 'default';

    //使用哪个redis
    protected $redisName = 'default';

    public function __construct(Context $context, string $id='')
    {
        $this->_context = $context;
        $this->id = $id;
        $this->keyPrefix = str_replace('\\', '.', static::class) .'.'. $id;
        $this->hashPrefix = $this->keyPrefix;
    }

    protected function getMemcached($name=null)
    {
        $name = $name ?? $this->memcachedName;
        if (!isset($this->_context->memcachedConns[$name])) {
            $this->_context->memcachedConns[$name] = Resource::getMemcached($name);
        }

        return $this->_context->memcachedConns[$name];
    }

	protected function getRedis($name=null)
	{
        $name = $name ?? $this->redisName;

		if (!isset($this->_context->redisConns[$name])) {
			$this->_context->redisConns[$name] = Resource::getRedis($name);
		}

		return $this->_context->redisConns;
	}

    protected function hash(string $str): string
    {
        return hash("crc32b", $this->hashPrefix.$str);
    }

    protected function serialize($data)
    {
        $str = serialize($data);
        //生成hash前缀
        return $this->hash($str) . $str;
    }

    protected function unserialize($data)
    {
        if (!$data) {
            return null;
        }

        $str = substr($data, 8);

        $hash = $this->hash($str);

        //验证数据是否损坏
        //实际使用中会发生表结构变动，缓存串key，缓存异常等情况
        //虽然一般这些损坏都是代码bug或代码改动造成的
        //理论上代码无bug且没有变动时不会出现损坏，但好的程序应该有
        //较好的容错性和健壮性，这里推荐坚持验证
        if (strncmp($hash, $data, 8) != 0) {
            return null;
        }

        return unserialize($str);
    }
}
