<?php
/**
 * プラグイン の情報クラス.
 *
 * @package MainImage
 * @author DELIGHT Inc.
 * @version $Id: $
 */
class plugin_info{
    
    static $PLUGIN_CODE       = 'MainImage';
    static $PLUGIN_NAME       = 'メインイメージ機能';
    static $CLASS_NAME        = 'MainImage';
    static $PLUGIN_VERSION    = '1.1.fix7';
    static $COMPLIANT_VERSION = '2.12.0';
    static $AUTHOR            = 'DELIGHT inc.';
    static $DESCRIPTION       = 'メイン画像を登録できます。複数登録やリンク、アニメーションの設定も可能です。';
    static $PLUGIN_SITE_URL    = 'http://www.ec-cube.net/owners/index.php';
    static $AUTHOR_SITE_URL    = 'http://www.delight-web.com/ec-cube/products/about.php';
    static $HOOK_POINTS       = array(array('prefilterTransform', 'prefilterTransform'));
}
?>