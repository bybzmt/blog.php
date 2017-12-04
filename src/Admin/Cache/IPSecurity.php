<?php
namespace Bybzmt\Blog\Admin\Cache;

use Bybzmt\Blog\Common;

/**
 * 安全检查
 */
class IPSecurity extends Common\Cache
{
    public function check($ip)
    {
        $key = $this->getKey($ip);
        $num = $this->getMemcached()->increment($key, 1, 1, $this->expiration);
        if ($num < 100) {
            return true;
        }
        return false;
    }

    private function getKey($ip)
    {
        return $this->keyPrefix .".". $ip;
    }


}
