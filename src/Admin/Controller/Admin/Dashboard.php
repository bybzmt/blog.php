<?php
namespace Bybzmt\Blog\Admin\Controller;

class Admin_Dashboard extends AuthWeb
{

    public function show()
    {
        $data = [
            'sidebarMenu' => 'Dashboard'
        ];

        $this->render($data);
    }


}
