<?php
/**
 * プラグイン の情報クラス.
 *
 * @package CheckedItems
 * @author DELIGHT Inc.
 * @version $Id: $
 */
class plugin_info{
    static $PLUGIN_CODE       = "CheckedItems";
    static $PLUGIN_NAME       = "最近チェックした商品";
    static $CLASS_NAME        = "CheckedItems";
    static $PLUGIN_VERSION     = "0.2";
    static $COMPLIANT_VERSION  = "2.12.0";
    static $AUTHOR            = "DELIGHT inc.";
    static $DESCRIPTION       = "最近チェックした商品を表示するブロックです。";
    static $PLUGIN_SITE_URL    = "http://www.ec-cube.net/owners/index.php";
    static $AUTHOR_SITE_URL    = "http://www.delight-web.com/";
    static $LICENSE          = "LGPL";
    static $HOOK_POINTS       = array(array('LC_Page_Products_Detail_action_after', 'LC_Page_Products_Detail_action_after'));
}
?>