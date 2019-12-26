<?php
/*
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
 
// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';
require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/MainImage.php';

/**
 * まとめて購入機能ブロックの設定クラス
 *
 * @package MainImage
 * @author DELIGHT Inc.
 * @version $Id: $
 */
class plg_MainImage_LC_Page_Plugin_MainImage_Config extends LC_Page_Admin_Ex {
    
    var $arrForm = array();
    
    var $plugin_name = 'MainImage';

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR .'MainImage/templates/plg_MainImage_admin_main_image_config.tpl';
        $this->tpl_subtitle = 'メインイメージ設定';
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
                    $this->tpl_onload = 'alert("登録が完了しました。");';
                    $this->tpl_onload .= 'window.close();';
                }
            }
            break;
        default:
            // プラグイン情報を取得.
            $arrForm = MainImage::getNamedPluginInfo();
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
        $objFormParam->addParam('画像(横)', 'image_width', STEXT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('画像(縦)', 'image_height', STEXT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('最大登録数', 'max_registration', STEXT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('アニメーション方法', 'effect', STEXT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('アニメーション間隔', 'interval', STEXT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('アニメーション速度', 'speed', STEXT_LEN, 'n', array('EXIST_CHECK','NUM_CHECK'));
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
        
        $arrOtherData = array_diff_key($arrData,array_flip(array('image_width','image_height','max_registration')));
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // UPDATEする値を作成する。
        $arrSqlVal = array();
        $arrSqlVal['free_field1'] = $arrData['image_width'];
        $arrSqlVal['free_field2'] = $arrData['image_height'];
        $arrSqlVal['free_field3'] = $arrData['max_registration'];
        $arrSqlVal['free_field4'] = serialize($arrOtherData);
        $arrSqlVal['update_date'] = 'CURRENT_TIMESTAMP';
        $where = 'plugin_code = ?';
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $arrSqlVal, $where,array($this->plugin_name));

        $objQuery->commit();
        return $arrErr;
    }
}
?>
