<?php
namespace Bybzmt\Blog\Web\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

use Bybzmt\Framework\Controller;
use Bybzmt\Blog\Web\Helper\TwigExtension;

abstract class Web extends Controller
{
    protected $_uid;
    protected $_session;

    public function __construct($context)
    {
        parent::__construct($context);

        $this->_session = $context->get("Helper.Session");
        $this->_uid = $this->_session->get('uid');
    }

    public function execute()
    {
        parent::execute();

        $this->_session->save();
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
        echo 'fail';
    }

    public function onException($e)
    {
        throw $e;
    }

    public function show()
    {
    }

    public function render(array $data=array(), string $name=null)
    {
        $dir = __DIR__;

        if (!$name) {
            $name = substr(static::class, strlen(__NAMESPACE__)+1);
        }
        $file = $name . '.tpl';

        $loader = new Twig_Loader_Filesystem($dir);
        $twig = new Twig_Environment($loader, array(
            'cache' => VAR_PATH . '/cache/templates',
            'debug' => true,
            'auto_reload' => true,
            'strict_variables' => true,
        ));
        $twig->addExtension(new TwigExtension($this->_ctx, $twig));

        $html = $twig->render($file, $data);

        $this->_ctx->response->end($html);
    }

}
