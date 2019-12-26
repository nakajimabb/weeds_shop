<?php
/*
 * TopicPath
 * Copyright (C) 2012 LOCKON CO.,LTD. All Rights Reserved.
 * http://www.lockon.co.jp/
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
 * @package TopicPath
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class TopicPath extends SC_Plugin_Base {

    /**
     * コンストラクタ
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
        
        TopicPath::insertBloc($arrPlugin);
        TopicPath::insertFreeField();

        // 必要なファイルをコピーします.
        copy(PLUGIN_UPLOAD_REALDIR . "TopicPath/templates/plg_topicPath_topicpath.tpl", TEMPLATE_REALDIR . "frontparts/bloc/plg_topicPath_topicpath.tpl");
        copy(PLUGIN_UPLOAD_REALDIR . "TopicPath/bloc/plg_topicPath_topicpath.php", HTML_REALDIR . "frontparts/bloc/plg_topicPath_topicpath.php");
        copy(PLUGIN_UPLOAD_REALDIR . "TopicPath/config.php", PLUGIN_HTML_REALDIR . "TopicPath/config.php");
        copy(PLUGIN_UPLOAD_REALDIR . "TopicPath/logo.png", PLUGIN_HTML_REALDIR . "TopicPath/logo.png");
        mkdir(PLUGIN_HTML_REALDIR . "TopicPath/media");
        SC_Utils_Ex::sfCopyDir(PLUGIN_UPLOAD_REALDIR . "TopicPath/media/", PLUGIN_HTML_REALDIR . "TopicPath/media/");
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
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrBlocId = $objQuery->getCol('bloc_id', "dtb_bloc", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_PC , "plg_topicPath_topicpath"));
        $bloc_id = (int) $arrBlocId[0];
        // ブロックを削除する.
        $where = "bloc_id = ?";
        $objQuery->delete("dtb_bloc", $where, array($bloc_id));
        $objQuery->delete("dtb_blocposition", $where, array($bloc_id));

        // メディアディレクトリ削除.
        SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "TopicPath/media");
        SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "frontparts/bloc/plg_topicPath_topicpath.tpl");
        SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR  . "frontparts/bloc/plg_topicPath_topicpath.php");
        SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "TopicPath");
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
        // nop
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
        // nop
    }

    /**
     * 処理の介入箇所とコールバック関数を設定
     * registerはプラグインインスタンス生成時に実行されます
     * 
     * @param SC_Helper_Plugin $objHelperPlugin 
     */
    function register(SC_Helper_Plugin $objHelperPlugin) {
        // ヘッダへの追加
        $template_dir = PLUGIN_UPLOAD_REALDIR . 'TopicPath/templates/';
        $objHelperPlugin->setHeadNavi($template_dir . 'plg_topicPath_header.tpl');
    }
    
    // プラグイン独自の設定データを追加
    function insertFreeField() {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $sqlval = array();
        $sqlval['free_field1'] = "1";
        $sqlval['free_field2'] = "1";
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = ?";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where, array('TopicPath'));
    }
    
    function insertBloc($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        // dtb_blocにブロックを追加する.
        $sqlval_bloc = array();
        $sqlval_bloc['device_type_id'] = DEVICE_TYPE_PC;
        $sqlval_bloc['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = " . DEVICE_TYPE_PC) + 1;
        $sqlval_bloc['bloc_name'] = $arrPlugin['plugin_name'];
        $sqlval_bloc['tpl_path'] = "plg_topicPath_topicpath.tpl";
        $sqlval_bloc['filename'] = "plg_topicPath_topicpath";
        $sqlval_bloc['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc['update_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc['php_path'] = "frontparts/bloc/plg_topicPath_topicpath.php";
        $sqlval_bloc['deletable_flg'] = 0;
        $sqlval_bloc['plugin_id'] = $arrPlugin['plugin_id'];
        $objQuery->insert("dtb_bloc", $sqlval_bloc);
    }
}
?>
