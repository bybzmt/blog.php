<?php
namespace Bybzmt\Blog\Common;

class LazyRowCache extends LazyLoader
{
    public function __construct(Context $context, string $name, string $id)
    {
        parent::__construct($context, $name, $id);

        if (!$this->initd) {
            $this->rowCacheAdd($id);
        }
    }

    protected function init()
    {
        if (!$this->isCached($this->id)) {
            //从缓存中批量载入
            $this->rowCacheLoad();
        }

        $this->row = $this->getCached($this->id);

        if ($this->row === null) {
            //从数据库中批量载入
            $rows = $this->rowLoad();

            //更新缓存
            $this->getCache('RowCache', $this->name)->sets($rows);

            $this->row = $this->getCached($this->id);
        }
    }
}
