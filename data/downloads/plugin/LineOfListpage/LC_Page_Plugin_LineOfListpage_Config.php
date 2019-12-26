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

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * プラグインファイル自動生成のクラス
 *
 * @package NewsAnimationDisplayClass
 * @author BLUE STYLE.
 * @version $Id: $
 */
class LC_Page_Plugin_LineOfListpage_Config extends LC_Page_Admin_Ex {

    var $arrForm = array();

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR ."LineOfListpage/config.tpl";
        $this->tpl_subtitle = "横並び商品一覧プラグイン";
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
        //かならずPOST値のチェックを行う
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        $arrForm = array();
        switch ($this->getMode()) {
        case 'register':
            $arrForm = $objFormParam->getHashArray();
            $this->arrErr = $objFormParam->checkError();
            // エラーなしの場合にはデータを送信
            if (count($this->arrErr) == 0) {
                $this->arrErr = $this->registData($arrForm);
                if (count($this->arrErr) == 0) {
                    SC_Utils_Ex::clearCompliedTemplate();
                    $this->tpl_onload = "alert('設定が完了しました。');";
                }
            }
            break;
        default:
            $arrForm = $this->loadData();
            $this->tpl_is_init = true;
            break;
        }
        $this->arrForm = $arrForm;
        // ポップアップ用の画面は管理画面のナビゲーションを使わない
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
     *
     * コンバートオプション	意味
     *   r	 「全角」英字を「半角」に変換します。
     *   R	 「半角」英字を「全角」に変換します。
     *   n	 「全角」数字を「半角」に変換します。
     *   N	 「半角」数字を「全角」に変換します。
     *   a	 「全角」英数字を「半角」に変換します。
     *   A	 「半角」英数字を「全角」に変換します （"a", "A" オプションに含まれる文字は、U+0022, U+0027, U+005C, U+007Eを除く U+0021 - U+007E の範囲です）。
     *   s	 「全角」スペースを「半角」に変換します（U+3000 -> U+0020）。
     *   S	 「半角」スペースを「全角」に変換します（U+0020 -> U+3000）。
     *   k	 「全角カタカナ」を「半角カタカナ」に変換します。
     *   K	 「半角カタカナ」を「全角カタカナ」に変換します。
     *   h	 「全角ひらがな」を「半角カタカナ」に変換します。
     *   H	 「半角カタカナ」を「全角ひらがな」に変換します。
     *   c	 「全角カタカナ」を「全角ひらがな」に変換します。
     *   C	 「全角ひらがな」を「全角カタカナ」に変換します。
     *   V	 濁点付きの文字を一文字に変換します。"K", "H" と共に使用します。
     *
     *   //チェックオプション
     *   See class => data/class/SC_CheckError.php
     *
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam('商品コード'       , 'product_code'          ,  2, ''  , array());
        $objFormParam->addParam('商品画像'         , 'image'                 ,  2, ''  , array());
        $objFormParam->addParam('ステータス'       , 'status'                ,  2, ''  , array());
        $objFormParam->addParam('商品名'           , 'name'                  ,  2, ''  , array());
        $objFormParam->addParam('販売価格'         , 'price'                 ,  2, ''  , array());
        $objFormParam->addParam('一覧コメント'     , 'listcomment'           ,  2, ''  , array());
        $objFormParam->addParam('詳細ボタン'       , 'detail_btn'            ,  2, ''  , array());
        $objFormParam->addParam('カートインボタン' , 'cartin_btn'            ,  2, ''  , array());
        $objFormParam->addParam('在庫表示'         , 'stock'                 ,  2, ''  , array());
        $objFormParam->addParam('高さ揃え'         , 'jqueryAutoHeight'      ,  2, ''  , array());
        $objFormParam->addParam('3px の角丸枠線'   , 'line_list_css'         ,  2, ''  , array());
    }

    /**
     * プラグイン設定値をDBから取得.
     *
     * @return void
     */
    function loadData() {
        $arrRet = array();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = "plugin_code = 'LineOfListpage'";
        $arrData = $objQuery->getRow('*', 'dtb_plugin', $where);
        if (!SC_Utils_Ex::isBlank($arrData['free_field1'])) {
            $arrRet = unserialize($arrData['free_field1']);
        }
        return $arrRet;
    }

    /**
     * プラグイン設定値をDBに書き込み.
     *
     * @return void
     */
    function registData($arrData) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // UPDATEする値を作成する。
        $sqlval = array();
        $sqlval['free_field1'] = serialize($arrData);
        $sqlval['free_field2'] = '';
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = 'LineOfListpage'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);
        $objQuery->commit();
    }
}
?>