<?php

// {{{ requires
require_once '../require.php';
require_once CLASS_REALDIR . 'pages/contents/LC_Page_Movie_List.php';

// }}}
// {{{ generate page

$objPage = new LC_Page_Movie_List();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
