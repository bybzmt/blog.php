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

            array('name'=>'文章管理', 'icons'=>'fa-book', 'childs'=>array(
                array('name'=>'文章管理', 'href'=>'/general.html'),
                array('name'=>'评论管理', 'href'=>'/buttons.html'),
            )),

            array('name'=>'会员管理', 'icons'=>'fa-user', 'href'=>'#'),

            array('name'=>'Components', 'icons'=>'fa-cogs', 'childs'=>array(
                array('name'=>'Calendar', 'href'=>'/calendar.html'),
                array('name'=>'Gallery', 'href'=>'/gallery.html'),
                array('name'=>'Todo List', 'href'=>'/todo_list.html'),
            )),

            array('name'=>'Extra Pages', 'icons'=>'fa-book', 'childs'=>array(
                array('name'=>'Blank Page', 'href'=>'/blank.html'),
                array('name'=>'Login', 'href'=>'/login.html'),
                array('name'=>'Lock Screen', 'href'=>'/lock_screen.html'),
            )),

            array('name'=>'Forms', 'icons'=>'fa-tasks', 'childs'=>array(
                array('name'=>'Form Components', 'href'=>'/form_component.html'),
            )),

            array('name'=>'Data Tables', 'icons'=>'fa-th', 'childs'=>array(
                array('name'=>'Basic Table', 'href'=>'/basic_table.html'),
                array('name'=>'Responsive Table', 'href'=>'/responsive_table.html'),
            )),

            array('name'=>'Charts', 'icons'=>'fa-bar-chart-o', 'childs'=>array(
                array('name'=>'Morris', 'href'=>'/morris.html'),
                array('name'=>'Chartjs', 'href'=>'/chartjs.html'),
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
                    if (!empty($sub['name']) && $sub['name'] == $active) {
                        $sub['active'] = true;
                        $menu['active'] = true;
                    }
                }
            }
        }

        return $this->twig->render('SidebarMenu.tpl', array('menus'=>$menus));
    }
}
