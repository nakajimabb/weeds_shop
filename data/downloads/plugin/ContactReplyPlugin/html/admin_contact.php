<?php
// {{{ requires
require_once '../../require.php';
require_once PLUGIN_UPLOAD_REALDIR . 'ContactReplyPlugin/LC_Page_Plugin_Admin_Contact.php';

// }}}
$objPage = new LC_Page_Plugin_Admin_Contact();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
?>

