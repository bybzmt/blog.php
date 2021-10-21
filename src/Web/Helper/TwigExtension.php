<?php
namespace Bybzmt\Blog\Web\Helper;

use Twig;
use Bybzmt\Blog\Web\Reverse;
use Bybzmt\Framework\ComponentTrait;

class TwigExtension extends Twig\Extension\AbstractExtension
{
    use ComponentTrait;

    private $_ctx;
    private $twig;

    public function __construct($context, Twig\Environment $twig)
    {
        $this->_ctx = $context;
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return array(
            new Twig\TwigFunction('mkUrl', array($this, 'mkUrl')),
        );
    }

    public function mkUrl(string $action, array $params=array(), bool $https=false)
    {
        return $this->getHelper("Utils")->mkUrl($action, $params, $https);
    }

}
