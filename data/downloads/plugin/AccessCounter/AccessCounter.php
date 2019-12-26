<?php
/*
 * AccessCounter
 * Copyright (C) 2012 S-Cubism CO.,LTD. All Rights Reserved.
 * http://ec-cube.ec-orange.jp/
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
 * @package AccessCounter
 * @author S-Cubism CO.,LTD.
 * @version $Id: $
 */
class AccessCounter extends SC_Plugin_Base {

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
        // dtb_productsテーブルにカラムを追加
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $objQuery->query("ALTER TABLE dtb_products ADD COLUMN plg_count int DEFAULT 1");
        $objQuery->commit();
        // ファイルコピー
        copy(PLUGIN_UPLOAD_REALDIR . "AccessCounter/logo.png", PLUGIN_HTML_REALDIR . "AccessCounter/logo.png");  
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
        $objQuery->query("ALTER TABLE dtb_products DROP COLUMN plg_count");
        $objQuery->commit();
        // ファイル削除
        SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "AccessCounter");
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
        $objHelperPlugin->addAction("LC_Page_Products_Detail_action_before", array($this, "access_count"));
        $objHelperPlugin->addAction("prefilterTransform", array($this, "prefilterTransform"));
    }

    /**
     * 商品アクセス数の表示
     *
     */
    function access_count (LC_Page_Ex $objPage) {
        $objQuery = new SC_Query();
        if ($_GET['product_id'] != "" && $_GET['product_id'] != null) {
            $product_id = $_GET['product_id'];
            $col = 'plg_count';
            $from = 'dtb_products';
            $where = 'product_id ='.$product_id;
            $arrRet = $objQuery->select($col, $from, $where);
            $sqlval = array($col => $arrRet[0]['plg_count'] + 1);
            $objQuery->update($from, $sqlval, $where);
            $objQuery->commit();
            $access = $arrRet[0]['plg_count'];
        }
        $objPage->accessCount = $access;
    }

    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        $objPlugin = new SC_Helper_Plugin();
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . "AccessCounter/templates/";
        // 商品詳細画面
        switch($objPage->arrPageLayout['device_type_id']) {
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC:
                if(strpos($filename, "products/detail.tpl") !== false) {
                    $objTransform->select("#undercolumn")->insertAfter(file_get_contents($template_dir . "plg_AccessCounter_detail.tpl"));
                }
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                break;
        }
        $source = $objTransform->getHTML();
    }
}
?>
