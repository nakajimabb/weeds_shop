<?php
/*
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
 
// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * よく一緒に購入されている商品の設定クラス
 *
 * @package BuyTogether
 * @author DELIGHT Inc.
 * @version $Id: $
 */
class LC_Page_Plugin_BuyTogether_Config extends LC_Page_Admin_Ex {
    
    var $arrForm = array();

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR ."BuyTogether/templates/config.tpl";
        $this->tpl_subtitle = "よく一緒に購入されている商品";
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
            $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("BuyTogether");
            $arrForm['search_date'] = $plugin['free_field1'];
            $arrForm['order_count'] = $plugin['free_field3'];
            
            if($plugin['free_field2']){
                $arrDispCnt = unserialize($plugin['free_field2']);
                $arrForm['disp_count_pc'] = $arrDispCnt['pc'];
                $arrForm['disp_count_mb'] = $arrDispCnt['mb'];
                $arrForm['disp_count_sp'] = $arrDispCnt['sp'];
            }
            
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

        $objFormParam->addParam('検索対象期間', 'search_date', INT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('検索対象受注数', 'order_count', INT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('PC表示件数', 'disp_count_pc', INT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('モバイル表示件数', 'disp_count_mb', INT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('スマートフォン表示件数', 'disp_count_sp', INT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
        
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
        $arrDispCnt['pc'] = $arrData['disp_count_pc'] ? $arrData['disp_count_pc'] : '';
        $arrDispCnt['mb'] = $arrData['disp_count_mb'] ? $arrData['disp_count_mb'] : '';
        $arrDispCnt['sp'] = $arrData['disp_count_sp'] ? $arrData['disp_count_sp'] : '';
        
        $sqlval['free_field1'] = $arrData['search_date'];
        $sqlval['free_field2'] = serialize($arrDispCnt);
        $sqlval['free_field3'] = $arrData['order_count'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = 'BuyTogether'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);

        $objQuery->commit();
        return $arrErr;
    }
}
?>
