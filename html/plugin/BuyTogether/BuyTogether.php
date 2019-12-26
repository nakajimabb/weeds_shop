<?php
/*
 *
 * BuyTogether
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

/**
 * プラグインのメインクラス
 *
 * @package BuyTogether
 * @author DELIGHT Inc.
 * @version $Id: $
 */
class BuyTogether extends SC_Plugin_Base {

    /**
     * コンストラクタ
     *
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    /**
     * インストール
     * installはプラグインのインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin plugin_infoを元にDBに登録されたプラグイン情報(dtb_plugin)
     * @return void
     */
    function install($arrPlugin) {

        // プラグイン
        if(copy(PLUGIN_UPLOAD_REALDIR . "BuyTogether/logo.png", PLUGIN_HTML_REALDIR . "BuyTogether/logo.png") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "BuyTogether/BuyTogether.php", PLUGIN_HTML_REALDIR . "BuyTogether/BuyTogether.php") === false);

        // ブロック
        if(copy(PLUGIN_UPLOAD_REALDIR . "BuyTogether/templates/default/plg_buytogether.tpl", TEMPLATE_REALDIR . "frontparts/bloc/plg_buytogether.tpl") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "BuyTogether/templates/mobile/plg_buytogether.tpl", SMARTY_TEMPLATES_REALDIR . MOBILE_TEMPLATE_NAME . "/frontparts/bloc/plg_buytogether.tpl") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "BuyTogether/templates/sphone/plg_buytogether.tpl", SMARTY_TEMPLATES_REALDIR . SMARTPHONE_DEFAULT_TEMPLATE_NAME . "/frontparts/bloc/plg_buytogether.tpl") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "BuyTogether/bloc/plg_buytogether.php", HTML_REALDIR . "frontparts/bloc/plg_buytogether.php") === false);

        if(mkdir(PLUGIN_HTML_REALDIR . "BuyTogether/media") === false);
        SC_Utils_Ex::sfCopyDir(PLUGIN_UPLOAD_REALDIR . "BuyTogether/media/", PLUGIN_HTML_REALDIR . "BuyTogether/media/");

        // 初期設定値を挿入
        BuyTogether::insertFreeField();

    }

    /**
     * アンインストール
     * uninstallはアンインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     * 
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function uninstall($arrPlugin) {

        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "BuyTogether/media") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR  . "frontparts/bloc/plg_buytogether.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "frontparts/bloc/plg_buytogether.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "BuyTogether") === false);

    }
    
    /**
     * 稼働
     * enableはプラグインを有効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function enable($arrPlugin) {

        // ブロック登録
        BuyTogether::insertBloc($arrPlugin);

    }

    /**
     * 停止
     * disableはプラグインを無効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function disable($arrPlugin) {

        // ブロック削除
        BuyTogether::deleteBloc($arrPlugin);

    }

    // プラグイン独自の設定データを追加
    function insertFreeField() {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $sqlval = array();
        $sqlval['free_field1'] = "120"; // 検索対象期間
        
        $arrDispCnt['pc'] = '6';
        $arrDispCnt['mb'] = '2';
        $arrDispCnt['sp'] = '3';
        
        $sqlval['free_field2'] = serialize($arrDispCnt); // 表示個数
        $sqlval['free_field3'] = "1"; // 検索対象受注数
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = ?";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where, array('BuyTogether'));
    }

    function insertBloc($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        // dtb_blocにブロックを追加する.
        $sqlval_bloc = array();
        $sqlval_bloc['device_type_id'] = DEVICE_TYPE_PC;
        $sqlval_bloc['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = " . DEVICE_TYPE_PC) + 1;
        $sqlval_bloc['bloc_name'] = $arrPlugin['plugin_name'];
        $sqlval_bloc['tpl_path'] = "plg_buytogether.tpl";
        $sqlval_bloc['filename'] = "plg_buytogether";
        $sqlval_bloc['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc['update_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc['php_path'] = "frontparts/bloc/plg_buytogether.php";
        $sqlval_bloc['deletable_flg'] = 0;
        $sqlval_bloc['plugin_id'] = $arrPlugin['plugin_id'];
        $objQuery->insert("dtb_bloc", $sqlval_bloc);
        
        $sqlval_bloc['device_type_id'] = DEVICE_TYPE_MOBILE;
        $sqlval_bloc['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = " . DEVICE_TYPE_MOBILE) + 1;
        $objQuery->insert("dtb_bloc", $sqlval_bloc);
        
        $sqlval_bloc['device_type_id'] = DEVICE_TYPE_SMARTPHONE;
        $sqlval_bloc['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = " . DEVICE_TYPE_SMARTPHONE) + 1;
        $objQuery->insert("dtb_bloc", $sqlval_bloc);
    }

    function deleteBloc($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrBlocId = $objQuery->getCol('bloc_id', "dtb_bloc", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_PC , "plg_buytogether"));
        $bloc_id = (int) $arrBlocId[0];
        // ブロックを削除する.
        $where = "bloc_id = ? AND device_type_id = ?";
        $objQuery->delete("dtb_bloc", $where, array($bloc_id, DEVICE_TYPE_PC));
        $objQuery->delete("dtb_blocposition", $where, array($bloc_id, DEVICE_TYPE_PC));

        $arrBlocId = $objQuery->getCol('bloc_id', "dtb_bloc", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_MOBILE , "plg_buytogether"));
        $bloc_id = (int) $arrBlocId[0];
        // ブロックを削除する.
        $where = "bloc_id = ? AND device_type_id = ?";
        $objQuery->delete("dtb_bloc", $where, array($bloc_id, DEVICE_TYPE_MOBILE));
        $objQuery->delete("dtb_blocposition", $where, array($bloc_id, DEVICE_TYPE_MOBILE));

        $arrBlocId = $objQuery->getCol('bloc_id', "dtb_bloc", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_SMARTPHONE , "plg_buytogether"));
        $bloc_id = (int) $arrBlocId[0];
        // ブロックを削除する.
        $where = "bloc_id = ? AND device_type_id = ?";
        $objQuery->delete("dtb_bloc", $where, array($bloc_id, DEVICE_TYPE_SMARTPHONE));
        $objQuery->delete("dtb_blocposition", $where, array($bloc_id, DEVICE_TYPE_SMARTPHONE));
    }

}
?>