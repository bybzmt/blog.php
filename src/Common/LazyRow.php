<?php
namespace Bybzmt\Blog\Common;

class LazyRow extends LazyLoader
{
    protected function init()
    {
        if (!$this->isCached($name, $id)) {
            //从数据库中加载数据
            $this->rowLoad();
        }

        $this->row = $this->getCached($name, $id);
    }

}
