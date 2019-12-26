<?php
/*
 *
 * AdminAuth
 * Copyright(c) 2012 Cyber-Will Inc. All Rights Reserved.
 *
 * http://www.cyber-will.co.jp/
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
 * パンくずブロックの設定クラス
 *
 * @package AdminAuth
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class LC_Page_Plugin_AdminAuth_Config extends LC_Page_Admin_Ex {
    
    var $arrForm = array();

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrAUTHORITY = $masterData->getMasterData('mtb_authority');
        define("ADMIN_AUTH_PAGE_MAX", 50);
        // 管理者は修正不可能にする
        unset($this->arrAUTHORITY[0]);
        $this->arrMember = $this->getMember();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR ."AdminAuth/templates/config.tpl";
        $this->tpl_subtitle = "管理画面管理者権限設定";
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
        
        $this->arrAdminAuth = $this->getAuthStore();
        switch ($this->getMode()) {
        case 'person':
            $arrForm = $objFormParam->getHashArray();
            $this->arrErr = $objFormParam->checkError();
            $arrForm['auth_id'] = "";
            break;
        case 'auth':
            $arrForm = $objFormParam->getHashArray();
            $this->arrErr = $objFormParam->checkError();
            // エラーなしの場合にはdtb_plg_adminauthからデータ取得
            if (count($this->arrErr) == 0) {
                // データの有無調べ
                $objQuery =& SC_Query_Ex::getSingletonInstance();
                $store_text = $objQuery->getOne("SELECT store_body FROM plg_adminauth WHERE member_id = ? AND personal_flg = ?", array($arrForm['auth_id'], $arrForm['person_flg']));
                if($store_text){
                    $arrStore = unserialize($store_text);
                    foreach($arrStore as $key => $val){
                        $arrForm['store'. $key] = $val;
                    }
                }
            }
            break;
        case 'edit':
            $arrForm = $objFormParam->getHashArray();
            
            $this->arrErr = $objFormParam->checkError();
            // エラーなしの場合にはデータを更新
            if (count($this->arrErr) == 0) {
                // データ更新
                $this->arrErr = $this->updateData($arrForm);
                if (count($this->arrErr) == 0) {
                    $this->tpl_onload = "alert('登録が完了しました。');";
                }
            }
            break;
        default:
            // プラグイン情報を取得.
            $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("AdminAuth");
            $arrForm['level'] = $plugin['free_field1'];
            $arrForm['rank'] = $plugin['free_field2'];
            // CSSファイル.
            $arrForm['css_data'] = $this->getTplMainpage($css_file_path);
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
        $objFormParam->addParam('個人/管理', 'person_flg', INT_LEN, 'n', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ユーザー選択', 'auth_id', INT_LEN, 'n', array('NUM_CHECK'));
        for ($cnt = 1; $cnt <= ADMIN_AUTH_PAGE_MAX; $cnt++) {
            $objFormParam->addParam('ストアID' . $cnt, 'store' . $cnt, INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        }
    }
    
    /**
     * ファイルパラメーター初期化.
     *
     * @param SC_UploadFile_Ex $objUpFile SC_UploadFileのインスタンス.
     * @param string $key 登録するキー.
     * @return void
     */
    function initUploadFile(&$objUpFile, $key) {
        $objUpFile->addFile('パンくず画像', $key, explode(',', "gif"), FILE_SIZE, true, 0, 0, false);
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
     * dtb_memberのリストを取得
     * @return array 
     */
    function getMember() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        
        $where = "authority <> ? AND del_flg = ?";
        $arrval = array('0', '0');
        $objQuery->setOrder('member_id');
        
        $array = $objQuery->select("member_id, name", "dtb_member", $where, $arrval);
        
        foreach($array as $val){
            $arrMember[$val['member_id']] = $val['name'];
        }
        
        return $arrMember;
    }

    /**
     * 管理画面の情報を取得
     * @return array 
     */
    function getAuthStore() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        
        $array = $objQuery->select("*", "plg_authstore");
        
        foreach($array as $val){
            $arrAuthStore[$val['parent_url']][] = array('store_id' => $val['store_id'], 'child_name' => $val['child_name'], 'parent_name' => $val['parent_name']);
        }
        return $arrAuthStore;
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
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = 'AdminAuth'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);
        
        $arrStore = array();
        for ($cnt = 1; $cnt <= ADMIN_AUTH_PAGE_MAX; $cnt++) {
            $arrStore[$cnt] = $arrData['store'.$cnt];
        }
        
        // 内容をシリアライズ
        $arrStore = serialize($arrStore);
        
        $where = "member_id = ? AND personal_flg = ?";
        $arrval = array($arrData['auth_id'], $arrData['person_flg']);
        
        // データの有無調べ
        $count = $objQuery->count("plg_adminauth", $where, $arrval);
        
        // 管理権限を格納
        $sqlval = array();
        $sqlval['store_body'] = $arrStore;
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['update_date'] = "NOW()";
        
        if($count > 0){
        $objQuery->update("plg_adminauth", $sqlval, $where, $arrval);
        }else{
        $sqlval['create_date'] = "NOW()";
        $sqlval['personal_flg'] = $arrData['person_flg'];
        $sqlval['member_id'] = $arrData['auth_id'];
        $objQuery->insert("plg_adminauth", $sqlval);
        }
        $objQuery->commit();
        SC_Helper_FileManager_Ex::deleteFile(COMPILE_ADMIN_REALDIR, false);
        return $arrErr;
    }

}
?>
