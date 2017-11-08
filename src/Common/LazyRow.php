<?php
namespace Bybzmt\Blog\Common;

class LazyRow extends LazyLoader
{
    public function __construct(Context $context, string $name, string $id)
    {
        parent::__construct($context, $name, $id);

        if (!$this->initd) {
            $this->rowAdd($id);
        }
    }

    protected function init()
    {
        if (!$this->isCached($this->id)) {
            //从数据库中加载数据
            $this->rowLoad();
        }

        $this->row = $this->getCached($this->id);
    }

}
