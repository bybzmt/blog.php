<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Framework\Controller;

abstract class Base extends Controller
{
    protected $_session;

    public function __construct($context)
    {
        parent::__construct($context);

        $this->_session = $this->getHelper("Session");
    }

    public function execute()
    {
        parent::execute();

        $this->_session->save();
    }
}
