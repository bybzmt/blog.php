<?php
namespace Bybzmt\Blog\Common;

class LazyRowCache extends LazyLoader
{
    private function init()
    {
        if (!$this->isCached($name, $id)) {
            //从缓存中批量载入
            $this->rowCacheLoad();
        }

        $this->row = $this->getCached($name, $id);

        if ($this->row === null) {
            //从数据库中批量载入
            $rows = $this->rowLoad();

            //更新缓存
            $this->context->getCache('RowCache', $this->name)->sets($rows);

            $this->row = $this->getCached($name, $id);
        }
    }
}
