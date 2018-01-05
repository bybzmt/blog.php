<?php
namespace Bybzmt\Blog\Web\Helper;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension;
use Twig_Function;
use Bybzmt\Blog\Web\Reverse;

class TwigExtension extends Twig_Extension
{
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
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
        return Reverse::mkUrl($action, $params, $https);
    }

}
