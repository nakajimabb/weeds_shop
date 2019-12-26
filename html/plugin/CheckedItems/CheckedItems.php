<?php
/*
 *
 * CheckedItems
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
 * @package ProductsCoordinator
 * @author DELIGHT Inc.
 * @version $Id: $
 */
class CheckedItems extends SC_Plugin_Base {

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
        if(copy(PLUGIN_UPLOAD_REALDIR . "CheckedItems/logo.png", PLUGIN_HTML_REALDIR . "CheckedItems/logo.png") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "CheckedItems/CheckedItems.php", PLUGIN_HTML_REALDIR . "CheckedItems/CheckedItems.php") === false);

        // ブロック
        if(copy(PLUGIN_UPLOAD_REALDIR . "CheckedItems/templates/plg_checkeditems.tpl", TEMPLATE_REALDIR . "frontparts/bloc/plg_checkeditems.tpl") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "CheckedItems/bloc/plg_checkeditems.php", HTML_REALDIR . "frontparts/bloc/plg_checkeditems.php") === false);

        if(mkdir(PLUGIN_HTML_REALDIR . "CheckedItems/media") === false);
        SC_Utils_Ex::sfCopyDir(PLUGIN_UPLOAD_REALDIR . "CheckedItems/media/", PLUGIN_HTML_REALDIR . "CheckedItems/media/");

        // 初期設定値を挿入
        CheckedItems::insertFreeField();

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

        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "CheckedItems/media") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR  . "frontparts/bloc/plg_checkeditems.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "frontparts/bloc/plg_checkeditems.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "CheckedItems") === false);

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
        CheckedItems::insertBloc($arrPlugin);

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
        CheckedItems::deleteBloc($arrPlugin);

    }

    // プラグイン独自の設定データを追加
    function insertFreeField() {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $sqlval = array();
        $sqlval['free_field1'] = "30";	// クッキー保存時間
        $sqlval['free_field2'] = "5";	// データ取得個数
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = ?";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where, array('CheckedItems'));
    }

    function insertBloc($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        // dtb_blocにブロックを追加する.
        $sqlval_bloc = array();
        $sqlval_bloc['device_type_id'] = DEVICE_TYPE_PC;
        $sqlval_bloc['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = " . DEVICE_TYPE_PC) + 1;
        $sqlval_bloc['bloc_name'] = $arrPlugin['plugin_name'];
        $sqlval_bloc['tpl_path'] = "plg_checkeditems.tpl";
        $sqlval_bloc['filename'] = "plg_checkeditems";
        $sqlval_bloc['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc['update_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc['php_path'] = "frontparts/bloc/plg_checkeditems.php";
        $sqlval_bloc['deletable_flg'] = 0;
        $sqlval_bloc['plugin_id'] = $arrPlugin['plugin_id'];
        $objQuery->insert("dtb_bloc", $sqlval_bloc);
    }

    function deleteBloc($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrBlocId = $objQuery->getCol('bloc_id', "dtb_bloc", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_PC , "plg_checkeditems"));
        $bloc_id = (int) $arrBlocId[0];
        // ブロックを削除する.
        $where = "bloc_id = ? AND device_type_id = ?";
        $objQuery->delete("dtb_bloc", $where, array($bloc_id, DEVICE_TYPE_PC));
        $objQuery->delete("dtb_blocposition", $where, array($bloc_id, DEVICE_TYPE_PC));
    }

    function LC_Page_Products_Detail_action_after($objPage) {

        // プロダクトIDの正当性チェック
        $product_id = $objPage->lfCheckProductId($objPage->objFormParam->getValue('admin'),$objPage->objFormParam->getValue('product_id'));

        // 商品閲覧履歴取得（最近見た商品）
        CheckedItems::setItemHistory($product_id);

    }

    function setItemHistory($product_id) {

        $cnt = 0;

        // プラグイン情報を取得.
        $plugin     = SC_Plugin_Util_Ex::getPluginByPluginCode("CheckedItems");
        //保存期間
        $save_limit = is_numeric($plugin['free_field1']) ? $plugin['free_field1'] : 0;
        //保存件数
        $item_count = is_numeric($plugin['free_field2']) ? $plugin['free_field2'] : 0;

        $arrDisp = $_COOKIE['product'];
        $cnt     = count($arrDisp);

        //重複項目のチェック
        $DispFlg = true;
        if (isset($_COOKIE['product'])) {
            foreach ($_COOKIE['product'] as $name => $value) {
                if($value == $product_id){
                    $DispFlg = false;
                }
            }
        }
 
        //クッキーにセット
        if($DispFlg){
            $disp_num = $item_count;
            if($cnt == 0){
                setcookie('product[' .$cnt .']', $product_id,time()+60*60*24*$save_limit,"/" );
            }else{
                $arrCookie = $_COOKIE['product'];
                $arrCookie[] = $product_id;

                //商品保存処理
                if(count($arrCookie) > $disp_num){
                    array_shift($arrCookie);
                }
                foreach ($arrCookie as $key => $val) {
                    setcookie('product[' .$key .']', $val,time()+60*60*24*$save_limit,"/" );
                }
            }
        }
    }

}
?>