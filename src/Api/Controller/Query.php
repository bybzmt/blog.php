<?php
namespace Bybzmt\Blog\Api\Controller;

use Bybzmt\Blog\Api\Controller as Base;

class Query extends Base
{
    /**
     * 反回指定文章
     *
     * @param tag:ID 列表类型
     * @param offset:int=0 偏移量
     * @param length:int=10 取数据条数
     * @return Article
     */
    public function articleListResolver($id, $offset=0, $length=10)
    {
        return array(
            'id' => 1
        );
    }

    /**
     * 验证码链接
     *
     * @return string!
     */
    public function captchaUrl()
    {
    }

}
