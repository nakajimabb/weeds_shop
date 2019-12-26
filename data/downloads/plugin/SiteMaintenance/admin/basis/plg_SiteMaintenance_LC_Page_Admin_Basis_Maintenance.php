<?php
/*
 * SiteMaintenance
 *
 * Copyright(c) 2009-2012 CUORE CO.,LTD. All Rights Reserved.
 *
 * http://ec.cuore.jp/
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
 * 店舗基本情報　メンテナンス管理 のページクラス.
 *
 * @package Page
 * @author CUORE CO.,LTD.
 */
class plg_SiteMaintenance_LC_Page_Admin_Basis_Maintenance extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . 'SiteMaintenance/templates/admin/basis/plg_SiteMaintenance_maintenance.tpl';
        $this->tpl_mainno = 'basis';
        $this->tpl_subno = 'maintenance';
        $this->tpl_maintitle = '基本情報管理';
        $this->tpl_subtitle = 'メンテナンス管理';
    }

    /**
     * Page のプロセス.
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
        //メンテナンスマスタ情報を取得する
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrMainte = $masterData->getMasterData("plg_SiteMaintenance_mtb_maintenance");

        //処理モードを取得する
        $mode = $this->getMode();

        //店舗の基本情報数から登録方法を決定する
        $objDb = new SC_Helper_DB_Ex();
        $cnt = $objDb->sfGetBasisCount();
        if ($cnt > 0) {
            $this->tpl_mode = 'update';    //登録データがあるのでUPDATE
        } else {
            $this->tpl_mode = 'insert';    //登録データがないのでINSERT
        }

        //メンテナンス設定
        if(isset($_POST['mode']) && !empty($_POST["mode"])) {
            // POSTデータの引き継ぎ
            $this->arrForm = $_POST;

            // 入力データの変換
            $this->arrForm = $this->lfConvertParam($this->arrForm);
            // 入力データのエラーチェック
            $this->arrErr = $this->lfErrorCheck($this->arrForm);

            if(count($this->arrErr) == 0) {
                switch ($mode) {
                case 'update':
                    $this->lfUpdateData($this->arrForm);	// 既存編集
                    break;
                case 'insert':
                    $this->lfInsertData($this->arrForm);	// 新規作成
                    break;
                default:
                    break;
                }
                $this->tpl_onload = "window.alert('メンテナンスの登録が完了しました。');";
            }
        //メンテナンス管理ページ表示
        } else {
            //登録情報から画面に表示に必要なデータを取得する
            $arrCol = $this->lfGetCol();
            $col    = SC_Utils_Ex::sfGetCommaList($arrCol);
            $arrRet = $objDb->sfGetBasisData(true, $col);
            $this->arrForm = $arrRet;
            $this->tpl_onload = "";
        }
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
     * 基本情報用のカラムを取り出す。
     * @return array 基本情報用のカラム配列
     */
    function lfGetCol() {
        $arrCol = array(
            "plg_sitemaintenance_maintenance_msg",
            "plg_sitemaintenance_maintenance"
        );
        return $arrCol;
    }

    /**
     * UPDATE処理
     * @param $array フォーム情報
     */
    function lfUpdateData($array) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrCol = $this->lfGetCol();
        foreach($arrCol as $val) {
            //$sqlval[$val] = $array[$val];
            //配列の場合は、パイプ区切りの文字列に変換
            if(is_array($array[$val])) {
                $sqlval[$val] = implode("|", $array[$val]);
            } else {
                $sqlval[$val] = $array[$val];
            }
        }
        $sqlval['update_date'] = 'Now()';
        // UPDATEの実行
        $ret = $objQuery->update("dtb_baseinfo", $sqlval);
    }

    /**
     * INSERT処理
     * @param $array フォーム情報
     */
    function lfInsertData($array) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrCol = $this->lfGetCol();
        foreach($arrCol as $val) {
            $sqlval[$val] = $array[$val];
        }
        $sqlval['update_date'] = 'Now()';
        // INSERTの実行
        $ret = $objQuery->insert("dtb_baseinfo", $sqlval);
    }

    /**
     * 取得文字列の変換
     * @param $array フォーム情報
     * @return array 変換後のフォーム情報
     */
    function lfConvertParam($array) {
        /*
         *  文字列の変換
         *  K :  「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
         *  C :  「全角ひら仮名」を「全角かた仮名」に変換
         *  V :  濁点付きの文字を一文字に変換。"K","H"と共に使用します
         *  n :  「全角」数字を「半角(ﾊﾝｶｸ)」に変換
         *  a :  全角英数字を半角英数字に変換する
         */

        //メンテナンス管理フォームで入力された値を変換します
        $arrConvList['plg_sitemaintenance_maintenance_msg'] = "";
        $arrConvList['plg_sitemaintenance_maintenance'] = "n";

        return SC_Utils_Ex::mbConvertKanaWithArray($array, $arrConvList);
    }

    /**
     * 入力エラーチェック
     * @param $array フォーム情報
     * @return array 入力エラ情報
     */
    function lfErrorCheck($array) {
        //メンテナンス管理フォームで入力された値をチェックします
        $objErr = new SC_CheckError_Ex($array);
        $objErr->doFunc(array("メンテナンスメッセージ", "plg_sitemaintenance_maintenance_msg", LLTEXT_LEN), array("MAX_LENGTH_CHECK"));
        return $objErr->arrErr;
    }
}
?>
