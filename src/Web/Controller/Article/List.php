<?php
namespace Bybzmt\Blog\Web\Controller;

use Bybzmt\Blog\Web\Controller;
use Bybzmt\Blog\Utils\Logger;

class Article_List extends Controller
{
    public function run()
    {
        $log = Logger::get('debug');
        $log->info("Article list");

        echo "Article list";
    }

}
