<?php

// {{{ requires
require_once '../../require.php';
require_once PLUGIN_UPLOAD_REALDIR . 'CustomerCSV/LC_Page_Admin_Customer_CustomerCSV.php';

// }}}
// {{{ generate page

$objPage = new LC_Page_Admin_Customer_CustomerCSV();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
