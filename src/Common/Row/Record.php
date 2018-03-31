<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Framework\Row;

class Record extends Row
{
    const TYPE_COMMENT = 1;
    const TYPE_REPLY = 2;

    public function getData()
    {
        switch($this->type) {
        case self::TYPE_COMMENT:
            return $this->getRow("Comment", $this->to_id);
        case self::TYPE_REPLY:
            return $this->getRow("Reply", $this->to_id);
        }

        return false;
    }

}
