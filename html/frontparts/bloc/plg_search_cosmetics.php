<?php

// {{{ requires
require_once realpath(dirname(__FILE__)) . '/../../require.php';
require_once PLUGIN_UPLOAD_REALDIR . 'SearchCosmetics/LC_Page_FrontParts_Bloc_SearchCosmetics.php';

// }}}
// {{{ generate page

$objPage = new LC_Page_FrontParts_Bloc_SearchCosmetics();
$objPage->blocItems = $params['items'];
$objPage->init();
$objPage->process();
?>
