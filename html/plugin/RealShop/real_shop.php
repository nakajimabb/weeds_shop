<?php
// {{{ requires
require_once '../../require.php';
require_once PLUGIN_UPLOAD_REALDIR . 'RealShop/LC_Page_Plugin_Admin_Basis_Shop.php';

// }}}
$objPage = new LC_Page_Plugin_Admin_Basis_Shop();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
?>

