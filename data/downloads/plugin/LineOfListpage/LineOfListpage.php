<?php
/*
 * LineOfListpage
 * Copyright (C) 2013 BLUE STYLE All Rights Reserved.
 * http://bluestyle.jp/
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
 * 商品一覧ページを横並び表示にできます。
 */
class LineOfListpage extends SC_Plugin_Base {

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

        // 初期値設定
        $arrData = array();
        $arrData['product_code']          = 'off';
        $arrData['image']                 = 'on';
        $arrData['status']                = 'on';
        $arrData['name']                  = 'on';
        $arrData['price']                 = 'on';
        $arrData['listcomment']           = 'on';
        $arrData['detail_btn']            = 'on';
        $arrData['cartin_btn']            = 'on';
        $arrData['stock']                 = 'off';
        $arrData['jqueryAutoHeight']      = 'on';
        $arrData['line_list_css']         = 'on';

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // UPDATEする値を作成する。
        $sqlval = array();
        $sqlval['free_field1'] = serialize($arrData);
        $where = "plugin_code = 'LineOfListpage'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);
        $objQuery->commit();

        unset($arrData);
        unset($sqlval);

        // 必要なファイルをhtmlディレクトリにコピーします.
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/logo.png", PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . "/logo.png");
        mkdir(PLUGIN_HTML_REALDIR . "LineOfListpage/media");
        SC_Utils_Ex::sfCopyDir(PLUGIN_UPLOAD_REALDIR . "LineOfListpage/media/", PLUGIN_HTML_REALDIR . "LineOfListpage/media/");
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
        SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "LineOfListpage/media");
        SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "LineOfListpage");
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
     * 処理の介入箇所とコールバック関数を設定
     * registerはプラグインインスタンス生成時に実行されます
     * 
     * @param SC_Helper_Plugin $objHelperPlugin 
     */
    function register(SC_Helper_Plugin $objHelperPlugin) {
        return parent::register($objHelperPlugin, $priority);
    }

    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        // SC_Helper_Transformのインスタンスを生成.
        $objTransform = new SC_Helper_Transform($source);
        // 呼び出し元テンプレートを判定します.
        $template_dir = PLUGIN_UPLOAD_REALDIR . 'LineOfListpage/';
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE: // モバイル
                break;
            case DEVICE_TYPE_SMARTPHONE: // スマホ
                break;
            case DEVICE_TYPE_PC: // PC
                // 商品一覧画面
                if (strpos($filename, 'site_frame.tpl') !== false) {
                    $html  = '';
                    $param_array = $this->loadData();
                    $template_dir = PLUGIN_UPLOAD_REALDIR . $this->arrSelfInfo['plugin_code'] . '/templates/';
                    $objTransform->select('head')->appendChild(file_get_contents($template_dir . 'lineoflistpage_header.tpl'));

                    // 高さ揃え jQuery
                    if ($param_array['jqueryAutoHeight'] === 'on'){
                        $objTransform->select('head')->appendChild(file_get_contents($template_dir . 'lineoflistpage_header_jqueryAutoHeight.tpl'));
                    }

                    // 高さ揃え jQuery
                    if ($param_array['line_list_css'] === 'on'){
                        $objTransform->select('head')->appendChild(file_get_contents($template_dir . 'lineoflistpage_header_line_list_css.tpl'));
                    }

                }
                if (strpos($filename, 'products/list.tpl') !== false) {
                    $html  = '';
                    $param_array = $this->loadData();
                    $template_dir = PLUGIN_UPLOAD_REALDIR . $this->arrSelfInfo['plugin_code'] . '/templates/';
                    $objTransform->select('div.list_area')->insertBefore(file_get_contents($template_dir . 'lineoflistpage_products_list_line.tpl'));

                    // 商品コード
                    if ($param_array['product_code'] === 'on'){
                        $objTransform->select('div.list_area')->appendFirst(file_get_contents($template_dir . 'lineoflistpage_products_list_line_product_code.tpl'));
                    }

                    // 商品画像
                    if ($param_array['image'] !== 'on'){
                        $objTransform->select('div.listphoto')->removeElement();
                    }

                    // ステータス
                    if ($param_array['status'] !== 'on'){
                        $objTransform->select('ul.status_icon')->removeElement();
                    }

                    // 商品名
                    if ($param_array['name'] !== 'on'){
                        $objTransform->select('div.listrightbloc h3')->removeElement();
                    }

                    // 価格
                    if ($param_array['price'] !== 'on'){
                        $objTransform->select('div.pricebox')->removeElement();
                    }

                    // 一覧コメント
                    if ($param_array['listcomment'] !== 'on'){
                        $objTransform->select('div.listcomment')->removeElement();
                    }

                    // 詳細ボタン
                    if ($param_array['detail_btn'] !== 'on'){
                        $objTransform->select('div.detail_btn')->removeElement();
                    } else {
                        $objTransform->select('div.detail_btn')->replaceElement(file_get_contents($template_dir . 'lineoflistpage_products_list_line_detail_button.tpl'));
                    }

                    // カートインボタン
                    if ($param_array['cartin_btn'] !== 'on'){
                        $objTransform->select('div.cart_area')->removeElement();
                    } else {
                        $objTransform->select('div.cartin_btn')->replaceElement(file_get_contents($template_dir . 'lineoflistpage_products_list_line_button.tpl'));
                    }

                    // 在庫
                    if ($param_array['stock'] === 'on'){
                        $objTransform->select('div.list_area')->appendChild(file_get_contents($template_dir . 'lineoflistpage_products_list_line_stock.tpl'));
                    }

                    $objTransform->select('div.list_area')->insertAfter(file_get_contents($template_dir . 'lineoflistpage_products_list_line_end.tpl'));
                }
                break;
            case DEVICE_TYPE_ADMIN: // 管理画面
            default:
                break;
        }

        // 変更を実行します
        $source = $objTransform->getHTML();
    }
    
    //設定を取得する必要がある場合はコメントを外す
    function loadData() {
        $arrRet = array();
        $arrData = SC_Plugin_Util_Ex::getPluginByPluginCode("LineOfListpage");
        if (!SC_Utils_Ex::isBlank($arrData['free_field1'])) {
            $arrRet = unserialize($arrData['free_field1']);
        }
        return $arrRet;
    }
}

?>
