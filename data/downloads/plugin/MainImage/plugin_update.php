<?php

require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/MainImage.php';
/**
 * プラグイン のアップデート用クラス.
 *
 * @package MainImage
 * @author DELIGHT Inc.
 * @version $Id: $
 */
class plugin_update{
   /**
     * アップデート
     * updateはアップデート時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     * 
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function update($arrPlugin) {
        self::copyFiles($arrPlugin);
        self::updatePluginRow($arrPlugin, '1.1.fix7');
    }
    
    /**
     * アップデートに必要なファイルをコピーする
     * 
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    static function copyFiles($arrPlugin){
        // TODO とりあえず全ファイルコピーしてる
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/class/plg_MainImage_LC_Page_Admin_Contents_MainImage.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/class/plg_MainImage_LC_Page_Admin_Contents_MainImage.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/class/plg_MainImage_LC_Page_FrontParts_Bloc_MainImage.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/class/plg_MainImage_LC_Page_FrontParts_Bloc_MainImage.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/class/plg_MainImage_LC_Page_Plugin_MainImage_Config.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/class/plg_MainImage_LC_Page_Plugin_MainImage_Config.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/class/plg_MainImage_SC_CheckError_Ex.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/class/plg_MainImage_SC_CheckError_Ex.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/class/plg_MainImage_SC_UploadFile_Ex.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/class/plg_MainImage_SC_UploadFile_Ex.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/class/plg_MainImage_SC_Utils_Ex.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/class/plg_MainImage_SC_Utils_Ex.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/html/admin/contents/plg_MainImage_main_image.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/html/admin/contents/plg_MainImage_main_image.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/html/frontparts/bloc/plg_MainImage_main_image.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/html/frontparts/bloc/plg_MainImage_main_image.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/templates/plg_MainImage_admin_main_image.tpl', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/plg_MainImage_admin_main_image.tpl');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/templates/plg_MainImage_admin_main_image_complete.tpl', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/plg_MainImage_admin_main_image_complete.tpl');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/templates/plg_MainImage_admin_main_image_config.tpl', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/plg_MainImage_admin_main_image_config.tpl');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/templates/plg_MainImage_main_image_mb.tpl', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/plg_MainImage_main_image_mb.tpl');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/templates/plg_MainImage_main_image_pc.tpl', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/plg_MainImage_main_image_pc.tpl');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/templates/plg_MainImage_main_image_sp.tpl', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/plg_MainImage_main_image_sp.tpl');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/templates/plg_MainImage_snippet_admin_contents_subnavi.tpl', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/plg_MainImage_snippet_admin_contents_subnavi.tpl');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/MainImage.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/MainImage.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/config.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/config.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/logo.png', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/logo.png');
        
        mkdir(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/class/sql');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/class/sql/plg_MainImage_MySQL.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/class/sql/plg_MainImage_MySQL.php');
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . '/class/sql/plg_MainImage_PostgreSQL.php', PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/class/sql/plg_MainImage_PostgreSQL.php');
        
        //アイコン
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/logo.png', PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . '/logo.png');
        //ブロック
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/plg_MainImage_main_image_pc.tpl', TEMPLATE_REALDIR .              'frontparts/bloc/plg_MainImage_main_image.tpl');
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/plg_MainImage_main_image_sp.tpl', SMARTPHONE_TEMPLATE_REALDIR .   'frontparts/bloc/plg_MainImage_main_image.tpl');
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/plg_MainImage_main_image_mb.tpl', MOBILE_TEMPLATE_REALDIR .       'frontparts/bloc/plg_MainImage_main_image.tpl');
        //html
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/html/admin/contents/plg_MainImage_main_image.php', HTML_REALDIR . ADMIN_DIR . 'contents/plg_MainImage_main_image.php');
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/html/frontparts/bloc/plg_MainImage_main_image.php', HTML_REALDIR .'frontparts/bloc/plg_MainImage_main_image.php');
    }
    
    /**
     * プラグインの情報をアップデートする
     * 
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @param integer $plugin_version プラグインのバージョン
     * @return void
     */
    static function updatePluginRow($arrPlugin,$plugin_version){
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $table = 'dtb_plugin';
        $arrSqlValues = array(
            'plugin_version' => $plugin_version,
            'update_date' => 'CURRENT_TIMESTAMP'
        );
        $where = 'plugin_id = ?';
        $objQuery->update($table,$arrSqlValues,$where,array($arrPlugin['plugin_id']));
    }
}