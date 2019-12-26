<?php
/*
 * BrandInfo
 * Copyright (C) 2013 S.Nakajima All Rights Reserved.
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


/* 
 * カテゴリ毎にコンテンツを設定する事ができます。
 */
class BrandInfo extends SC_Plugin_Base {

    /**
     * コンストラクタ
     * プラグイン情報(dtb_plugin)をメンバ変数をセットします.
     * @param array $arrSelfInfo dtb_pluginの情報配列
     * @return void
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    /**
     * インストール時に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function install($arrPlugin) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        if (DB_TYPE == 'mysql') {
            $sql = <<<DOC
CREATE TABLE dtb_brand (
    brand_id int NOT NULL,
    name text NOT NULL,
    brand_info text NOT NULL,
    brand_image text NOT NULL,
    maker_id int NOT NULL,
    rank int NOT NULL DEFAULT 0,
    creator_id int NOT NULL,
    create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_date timestamp NOT NULL,
    del_flg smallint NOT NULL DEFAULT 0,
    PRIMARY KEY (brand_id)
);
DOC;
    $objQuery->query($sql);


            $sql = <<<DOC
CREATE TABLE dtb_brand_count (
    brand_id int NOT NULL,
    product_count int NOT NULL,
    create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (brand_id)
);
DOC;
    $objQuery->query($sql);

    $objQuery->exec("ALTER TABLE dtb_products ADD COLUMN brand_id int(11) NOT NULL DEFAULT '0';");

    $objQuery->commit();
        }

    copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/' . 'brand.php',
         PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . '/' . 'brand.php');
    copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/logo.png',
         PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . '/' . 'logo.png');
    }


    /**
     * 削除時に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function uninstall($arrPlugin) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->query("DROP TABLE dtb_brand ;");
        $objQuery->query("DROP TABLE dtb_brand_count ;");
        $objQuery->exec("ALTER TABLE dtb_products DROP COLUMN brand_id;");
    }
    
    /**
     * 有効にした際に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function enable($arrPlugin) {
        // nop
    }

    /**
     * 無効にした際に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function disable($arrPlugin) {
        // nop
    }

    /**
     * prefilterコールバック関数
     * テンプレートの変更処理を行います.
     *
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @return void
     */
    function prefilterTransformBrandInfo(&$source, LC_Page_Ex $objPage, $filename) {
        // SC_Helper_Transformのインスタンスを生成.
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . $this->arrSelfInfo['plugin_code'] . '/templates/';
        // 呼び出し元テンプレートを判定します.
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE: // モバイル
            case DEVICE_TYPE_SMARTPHONE: // スマホ
                break;
            case DEVICE_TYPE_PC: // PC
                break;
            case DEVICE_TYPE_ADMIN: // 管理画面
                break;
            default:
                if (strpos($filename, 'products/subnavi.tpl') !== false) {
                    $objTransform->select('ul', NULL, false)->appendChild(
                        file_get_contents($template_dir . 'brand_navi.tpl'));
                }
                break;
        }

        $source = $objTransform->getHTML();
    }
}

?>
