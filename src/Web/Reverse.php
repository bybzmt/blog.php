<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Framework\Reverse as Base;
use Bybzmt\Framework\Config;

class Reverse extends Base
{
    public function mkUrl(string $action, array $params=array(), bool $https=false)
    {
        $uri = $this->buildUri($action, $params);

        $host = Config::get('host.web');

        if ($https) {
            return 'https://' . $host . $uri;
        }

        return 'http://' . $host . $uri;
    }
}
