<?php
/*
 * AutoBackUp
 * Copyright(c) 2012 SUNATMARK CO.,LTD. All Rights Reserved.
 * http://www.sunatmark.co.jp/
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

require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * ランクの設定クラス
 *
 * @package AutoBackUp
 * @author Sunatmark
 * @version $Id: $
 */
class plg_AutoBackUp_LC_Page_Config extends LC_Page_Admin_Ex {

    protected $arrLimitCond;
    
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . 'AutoBackUp/templates/config.tpl';
        $this->tpl_subtitle = '自動バックアップ初期設定';
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process() {
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
            //$this->arrErr = $objFormParam->checkError();
            $this->arrErr = $this->lfCheckError($objFormParam);
            
            // エラーなしの場合にはデータを更新
            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                // データ更新
                $this->updateData($arrForm);
                $this->tpl_onload = "alert('登録が完了しました。');";
                $this->tpl_onload .= 'window.close();';
            }
            break;
        default:
            // プラグイン情報を取得.
            $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("AutoBackUp");
			$arrExec = explode(':', $plugin['free_field2']);
            $arrForm['auto_backup']  = $plugin['free_field1'];
            $arrForm['exec_time']    = $arrExec[0];
            $arrForm['exec_minutes'] = $arrExec[1];
            $arrForm['email']        = $plugin['free_field3'];
            $arrForm['backup_limit'] = $plugin['free_field4'];
            break;
        }

        for ($i = 0; $i <= 23; $i++) {
            $this->arrExecTime[sprintf("%02d", $i)] = sprintf("%02d", $i);
		}

        for ($i = 0; $i <= 59; $i++) {
            $this->arrExecMinutes[sprintf("%02d", $i)] = sprintf("%02d", $i);
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
    function lfInitParam(SC_FormParam &$objFormParam) {
        $objFormParam->addParam('自動バックアップ', 'auto_backup', INT_LEN, 'n', array('EXIST_CHECK','SELECT_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('実行時', 'exec_time', INT_LEN, 'n', array('EXIST_CHECK','SELECT_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('実行分', 'exec_minutes', INT_LEN, 'n', array('EXIST_CHECK','SELECT_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('メールアドレス', 'email', null, 'a', array('NO_SPTAB', 'EMAIL_CHECK', 'SPTAB_CHECK' ,'EMAIL_CHAR_CHECK'));
        $objFormParam->addParam('登録商品数上限', 'backup_limit', INT_LEN, 'n', array('NUM_CHECK'));
    }
    
    /**
     * エラーチェック
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @return array エラー配列
     */
    function lfCheckError(SC_FormParam &$objFormParam) {
        $arrErr = $objFormParam->checkError();
        return $arrErr;
    }
    

    /**
     * ページデータを取得する.
     *
     * @param string $file_path ファイルパス
     * @return array ファイルデータの配列
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
        
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // UPDATEする値を作成する。
        $sqlval = array();
        $sqlval['free_field1'] = $arrData['auto_backup'];
        $sqlval['free_field2'] = $arrData['exec_time'] . ':' . $arrData['exec_minutes'];
        $sqlval['free_field3'] = $arrData['email'];
        $sqlval['free_field4'] = $arrData['backup_limit'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = 'AutoBackUp'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);

        $objQuery->commit();
        
    }
}
?>
