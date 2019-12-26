<?php
/*
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
 
// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 最近チェックした商品の設定クラス
 *
 * @package CheckedItems
 * @author DELIGHT Inc.
 * @version $Id: $
 */
class LC_Page_Plugin_CheckedItems_Config extends LC_Page_Admin_Ex {
    
    var $arrForm = array();

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR ."CheckedItems/templates/config.tpl";
        $this->tpl_subtitle = "最近チェックした商品";
    }

    /**
     * プロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        
        $arrForm = array();
        
        switch ($this->getMode()) {
        case 'edit':
            $arrForm = $objFormParam->getHashArray();
            $this->arrErr = $objFormParam->checkError();
            // エラーなしの場合にはデータを更新
            if (count($this->arrErr) == 0) {
                // データ更新
                $this->arrErr = $this->updateData($arrForm);
                if (count($this->arrErr) == 0) {
                    $this->tpl_onload = "alert('登録が完了しました。');";
                    $this->tpl_onload .= 'window.close();';
                }
            }
            break;
        default:
            // プラグイン情報を取得.
            $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("CheckedItems");
            $arrForm['save_limit'] = $plugin['free_field1'];
            $arrForm['save_count'] = $plugin['free_field2'];
            break;
        }
        $this->arrForm = $arrForm;
        $this->setTemplate($this->tpl_mainpage);
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
    
    /**
     * パラメーター情報の初期化
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {

        $objFormParam->addParam('履歴保存日数', 'save_limit', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('履歴保存個数', 'save_count', array('EXIST_CHECK','NUM_CHECK'));
    }
    

    /**
     * ページデータを取得する.
     *
     * @param integer $device_type_id 端末種別ID
     * @param integer $page_id ページID
     * @param SC_Helper_PageLayout $objLayout SC_Helper_PageLayout インスタンス
     * @return array ページデータの配列
     */
    function getTplMainpage($file_path) {

        if (file_exists($file_path)) {
            $arrfileData = file_get_contents($file_path);
        }
        return $arrfileData;
    }
    
    /**
     *
     * @param type $arrData
     * @return type 
     */
    function updateData($arrData) {
        $arrErr = array();
        
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // UPDATEする値を作成する。
        $sqlval = array();
        $sqlval['free_field1'] = $arrData['save_limit'];
        $sqlval['free_field2'] = $arrData['save_count'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = 'CheckedItems'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);

        $objQuery->commit();
        return $arrErr;
    }
}
?>
