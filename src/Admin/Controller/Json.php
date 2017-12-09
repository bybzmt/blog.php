<?php
namespace Bybzmt\Blog\Admin\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Admin;

abstract class Json extends Common\Controller
{
    use Admin\Loader;

    /*
     * 反回码
     *
     * 0 请求成功
     * 1 通用错误 (前台显示错误即可)
     * 2 未登陆 (前台需显示登陆界面)
     * 3 服务器异常 (控制台日志)
     */
    protected $ret;

    //返回数据
    protected $data;

    public function execute()
    {
        parent::execute();

        echo json_encode(array('ret'=>$this->ret, 'data'=>$this->data));
    }

    public function init()
    {
    }

    public function valid()
    {
        return true;
    }

    public function exec()
    {
        return true;
    }

    public function fail()
    {
        if (!$this->data) {
            $this->ret = 1;
            $this->data = "执行失败";
        }
    }

    public function show()
    {
        $this->ret = 0;
        $this->data = "执行成功";
    }

    public function onException($e)
    {
        error_log($e);

        $this->ret = 3;
        $this->data = (string)$e;
    }

}
