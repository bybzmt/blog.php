<?php
namespace Bybzmt\Blog\Common;

abstract class Cache
{
    use Loader;


    public function getMemcached($name='default')
    {
        if (!isset($this->_context->memcachedConns[$name])) {
            $this->_context->memcachedConns[$name] = Resource::getMemcached($name);
        }

        return $this->_context->memcachedConns[$name];
    }

	public function getRedis($name='default')
	{
		if (!isset($this->_context->redisConns[$name])) {
			$this->_context->redisConns[$name] = Resource::getRedis($name);
		}

		return $this->_context->redisConns;
	}
}
