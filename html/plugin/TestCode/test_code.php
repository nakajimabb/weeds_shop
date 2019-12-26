<?php

// {{{ requires
require_once '../../require.php';
require_once PLUGIN_UPLOAD_REALDIR . 'TestCode/LC_Page_Admin_System_TestCode.php';

// }}}
// {{{ generate page

$objPage = new LC_Page_Admin_System_TestCode();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
