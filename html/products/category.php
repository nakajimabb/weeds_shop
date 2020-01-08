<?php

require_once '../require.php';
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';


class LC_Page_Category extends LC_Page_Ex
{
    function init()
    {
        parent::init();
    }

    function process()
    {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    function action()
    {
    }
}

$objPage = new LC_Page_Category();
$objPage->init();
$objPage->process();
