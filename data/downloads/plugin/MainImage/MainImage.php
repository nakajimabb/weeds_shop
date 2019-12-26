<?php
/*
 *
 * MainImage
 * Copyright(c) 2012 DELIGHT Inc. All Rights Reserved.
 *
 * http://www.delight-web.com/
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// TODO transactionを使ってみるなど
require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/class/sql/plg_MainImage_MySQL.php';
require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/class/sql/plg_MainImage_PostgreSQL.php';

/**
 * プラグインのメインクラス
 *
 * @package MainImage
 * @author DELIGHT CO.,LTD.
 * @version $
 */
class MainImage extends SC_Plugin_Base {
    
    public static $table_name = 'dtb_main_images';

    /**
     * インストール時に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function install($arrPlugin) {
        self::createTables();
        self::copyFiles($arrPlugin);
        self::initPluginRow($arrPlugin);
    }

    /**
     * 削除時に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function uninstall($arrPlugin) {
        self::dropTables();
        self::deleteFiles($arrPlugin);
    }
    
    /**
     * 有効にした際に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function enable($arrPlugin) {
        self::insertBloc($arrPlugin);
    }

    /**
     * 無効にした際に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function disable($arrPlugin) {
        self::deleteBloc();
    }
    
    /**
     * プラグイン用ファイルをコピー 
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    static function copyFiles($arrPlugin){
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
     * 本体を含むすべてのプラグイン用ファイルを削除
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void 
     */
    static function deleteFiles($arrPlugin){
        //ブロック
        SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR .              'frontparts/bloc/plg_MainImage_main_image.tpl');
        SC_Helper_FileManager_Ex::deleteFile(SMARTPHONE_TEMPLATE_REALDIR .   'frontparts/bloc/plg_MainImage_main_image.tpl');
        SC_Helper_FileManager_Ex::deleteFile(MOBILE_TEMPLATE_REALDIR .       'frontparts/bloc/plg_MainImage_main_image.tpl');
        //html
        SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . ADMIN_DIR . 'contents/plg_MainImage_main_image.php');
        SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR .'frontparts/bloc/plg_MainImage_main_image.php');
        //アイコン
        SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . '/logo.png');
        //プラグイン本体
        SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code']);
    }
    
    /**
     * RDBMS別のスキーマを取得する
     * 
     * @return array スキーマ配列 
     */
    static function getSchema(){
        
        $arrSchema = array();
        switch(DB_TYPE){
            case 'mysql':
                $arrSchema = plg_MainImage_MySQL::$arrSchema;
                break;
            case 'pgsql':
                $arrSchema = plg_MainImage_PostgreSQL::$arrSchema;
                break;
        }
        return $arrSchema;
    }

    /**
     * プラグイン用テーブルの作成
     */
    static function createTables() {
        
        $objQuery = & SC_Query_Ex::getSingletonInstance();
        
        //テーブル作成
        foreach(self::getSchema() as $table => $arrFields){
           $fields = implode(',', $arrFields);
           $sql = sprintf('CREATE TABLE %s (%s)', $table, $fields);
           $objQuery->query($sql);
        }
    }

    /**
     * プラグイン用テーブルの削除
     */
    static function dropTables() {
        
        $objQuery = &SC_QUERY_Ex::getSingletonInstance();
        $arrTables = $objQuery->listTables();
        
        //テーブル削除
        foreach(self::getSchema() as $table => $arrFields){
            
            if(in_array($table, $arrTables)){
                
                $sql = sprintf('DROP TABLE %s', $table);
                $objQuery->query($sql);
            }
        }
    }
    

    /**
     * bloc関係のテーブルからプラグインの情報を削除する
     * 
     * @return void
     */
    static function deleteBloc(){
        $objQuery =&SC_Query_Ex::getSingletonInstance();
        
        $arrDeviceTypes = array(
            DEVICE_TYPE_PC,
            DEVICE_TYPE_SMARTPHONE,
            DEVICE_TYPE_MOBILE
        );
        foreach($arrDeviceTypes as $device_type){
            //filenameの前方一致とデバイスタイプでbloc_id取得
            $arrBlocIds = $objQuery->getCol('bloc_id','dtb_bloc','filename = ? AND device_type_id = ?',array('plg_MainImage_main_image',$device_type));
            
            if(isset($arrBlocIds[0])){
                $bloc_id = $arrBlocIds[0];
                $where = 'bloc_id = ? AND device_type_id = ?';
                $arrWhereValues = array($bloc_id,$device_type);
                $objQuery->delete('dtb_bloc',$where,$arrWhereValues);
                $objQuery->delete('dtb_blocposition',$where,$arrWhereValues);
            }
        }
    }
    
    /**
     * bloc関係のテーブルにプラグインの情報を登録する
     *  
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    static function insertBloc($arrPlugin){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $file_name = 'plg_MainImage_main_image';
        
        $arrDeviceTypeName = array(
            DEVICE_TYPE_PC,
            DEVICE_TYPE_SMARTPHONE,
            DEVICE_TYPE_MOBILE
        );
        
        foreach($arrDeviceTypeName as $type){
            $arrSql = array(
                'device_type_id' => $type,
                'bloc_id' => $objQuery->max('bloc_id','dtb_bloc',sprintf('device_type_id = %d',$type)) + 1,
                'bloc_name' => $arrPlugin['plugin_name'],
                'tpl_path' => 'plg_MainImage_main_image.tpl',
                'filename' => $file_name,
                'create_date' => 'CURRENT_TIMESTAMP',
                'update_date' => 'CURRENT_TIMESTAMP',
                'php_path' => 'frontparts/bloc/plg_MainImage_main_image.php',
                'deletable_flg' => 0,
                'plugin_id' => $arrPlugin['plugin_id']
            );
            $objQuery->insert('dtb_bloc',$arrSql);
        }
    }

    /**
     * プレフィルタコールバック関数
     *
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @return void
     */
    static function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . 'MainImage/templates/';
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC:
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                if (strpos($filename, 'contents/subnavi.tpl') !== false) {
                    $objTransform->select('ul.level1')->appendChild(file_get_contents($template_dir . 'plg_MainImage_snippet_admin_contents_subnavi.tpl'));
                }
                break;
        }
        $source = $objTransform->getHTML();
    }
    
    /**
     * データベース上のプラグイン情報を初期化する
     * @param type $arrPlugin 
     */
    function initPluginRow($arrPlugin){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrOtherData = array(
            'effect' => 0,
            'interval' => 5000,
            'speed' => 500
        );
        $sqlVal = array(
            'free_field1' => 720, //画像最大幅
            'free_field2' => 500, //画像最大高
            'free_field3' => 0, // 最大登録数
            'free_field4' => serialize($arrOtherData)
        );
        $where = 'plugin_code = ?';
        $objQuery->update('dtb_plugin',$sqlVal,$where,array($arrPlugin['plugin_code']));
    }
    
    /**
     * プラグイン情報を連想配列に格納して取得
     * @return array プラグイン情報
     */
    static function getNamedPluginInfo(){
        $arrPlugin = SC_Plugin_Util_Ex::getPluginByPluginCode('MainImage');
        $arrOtherData = unserialize($arrPlugin['free_field4']);
        $arrRet = array(
            'image_width' => $arrPlugin['free_field1'],
            'image_height' => $arrPlugin['free_field2'],
            'max_registration' => $arrPlugin['free_field3'],
            'effect' => $arrOtherData['effect'],
            'interval' => $arrOtherData['interval'],
            'speed' => $arrOtherData['speed']
        );
        return $arrRet;
    }
}