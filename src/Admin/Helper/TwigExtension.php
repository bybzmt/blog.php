<?php
namespace Bybzmt\Blog\Admin\Helper;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension;
use Twig_Function;
use Bybzmt\Blog\Admin;

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
            new Twig_Function('sidebarMenu', array($this, 'sidebarMenu')),
            new Twig_Function('mkUrl', array($this, 'mkUrl')),
        );
    }

    public function mkUrl(string $action, array $params=array(), bool $https=false)
    {
        return Admin\Reverse::mkUrl($action, $params, $https);
    }

    public function sidebarMenu($active)
    {
        $menus = array(
            array('name'=>'Dashboard', 'icons'=>'fa-dashboard', 'href'=>$this->mkUrl('Admin.Dashboard')),

            array('name'=>'后台管理', 'icons'=>'fa-cogs', 'childs'=>array(
                array('name'=>'管理员管理', 'href'=>$this->mkUrl('Admin.UserList')),
                array('name'=>'角色管理', 'href'=>$this->mkUrl('Admin.RoleList')),
            )),

            array('name'=>'博客管理', 'icons'=>'fa-book', 'childs'=>array(
                array('name'=>'文章管理', 'href'=>$this->mkUrl('Blog.ArticleList')),
                array('name'=>'评论管理', 'href'=>$this->mkUrl('Blog.CommentList')),
            )),

            array('name'=>'会员管理', 'icons'=>'fa-user', 'childs'=>array(
                array('name'=>'会员管理', 'href'=>$this->mkUrl('Member.UserList')),
            )),
        );

        //标记出是否为当前
        foreach ($menus as &$menu) {
            $menu['active'] = false;

            if (!empty($menu['name']) && $menu['name'] == $active) {
                $menu['active'] = true;
            }

            if (!empty($menu['childs'])) {
                foreach ($menu['childs'] as &$sub) {
                    $sub['active'] = false;
                    if (!empty($sub['name']) && $sub['name'] == $active) {
                        $sub['active'] = true;
                        $menu['active'] = true;
                    }
                }
            } else {
                $menu['childs'] = array();
            }
        }

        return $this->twig->render('SidebarMenu.tpl', array('menus'=>$menus));
    }
}
