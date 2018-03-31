<?php
namespace Bybzmt\Blog\Web\Helper;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension;
use Twig_Function;
use Bybzmt\Blog\Web\Reverse;
use Bybzmt\Framework\ComponentTrait;

class TwigExtension extends Twig_Extension
{
    use ComponentTrait;

    private $_ctx;
    private $twig;

    public function __construct($context, Twig_Environment $twig)
    {
        $this->_ctx = $context;
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('mkUrl', array($this, 'mkUrl')),
        );
    }

    public function mkUrl(string $action, array $params=array(), bool $https=false)
    {
        return $this->getHelper("Utils")->mkUrl($action, $params, $https);
    }

}
