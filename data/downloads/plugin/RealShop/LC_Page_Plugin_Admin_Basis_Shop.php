<?php
/*
 * RealShop
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

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 店舗情報 のページクラス.
 *
 * @package Page
 * @author S.Nakajima
 * @version $Id: $
 */
class LC_Page_Plugin_Admin_Basis_Shop extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . 'RealShop/templates/real_shop.tpl';
        $this->tpl_mainno = 'basis';
        $this->tpl_subno = 'shop';
        $this->tpl_maintitle = '基本情報管理';
        $this->tpl_subtitle = '店舗情報';
        $this->arrShop = array();

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPref = $masterData->getMasterData('mtb_pref');
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

        $objFormParam = new SC_FormParam_Ex();

        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);

        // POST値をセット
        $objFormParam->setParam($_POST);

        // POST値の入力文字変換
        $objFormParam->convParam();

        // 選択中の店舗コード
        $this->tpl_shop_id = $objFormParam->getValue('tpl_shop_id');

        // 変換後のPOST値を取得
        $this->arrForm  = $objFormParam->getHashArray();

        // 店舗一覧情報
        $this->arrShop = SC_RealShop::GetDispList($this->arrPref);
        
        // モードによる処理切り替え
        switch ($this->getMode()) {

            // 編集処理
            case 'edit':
                // エラーチェック
                $this->arrErr = $this->lfCheckError($this->arrForm, $objFormParam, $this->tpl_shop_id);

                if (count($this->arrErr) <= 0) {

                    if ($this->tpl_shop_id == '') {
                        // 新規登録
                        $this->lfInsert($this->arrForm);
                    } else {
                        // 編集
                        $this->lfUpdate($this->tpl_shop_id, $this->arrForm);
                    }

                    $this->objDisplay->reload();
                }
                break;

            // 編集前処理
            case 'pre_edit':
                $this->lfPreEdit($this->arrForm, $this->tpl_shop_id, $this->arrShop);
                $this->tpl_shop_id = $this->arrForm['shop_id'];
                break;

            // 削除
            case 'delete':
                $this->lfDelete($this->tpl_shop_id);

                SC_Response_Ex::reload();
                break;
        }
    }

    function destroy() {
        parent::destroy();
    }

    // パラメーター情報の初期化を行う.
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam('店舗コード', 'shop_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('店舗名', 'name', SMTEXT_LEN, 'KVa', array('EXIST_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('郵便番号1', 'zip01', ZIP01_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK' ,'NUM_CHECK', 'NUM_COUNT_CHECK'));
        $objFormParam->addParam('郵便番号2', 'zip02', ZIP02_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK' ,'NUM_CHECK', 'NUM_COUNT_CHECK'));
        $objFormParam->addParam('都道府県', 'pref', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('住所1', 'addr01', MTEXT_LEN, 'aKV', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('住所2', 'addr02', MTEXT_LEN, 'aKV', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号1', 'tel01', TEL_ITEM_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号2', 'tel02', TEL_ITEM_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号3', 'tel03', TEL_ITEM_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('FAX番号1', 'fax01', TEL_ITEM_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('FAX番号2', 'fax02', TEL_ITEM_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('FAX番号3', 'fax03', TEL_ITEM_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('発送可能', 'valid', TEL_ITEM_LEN, 'n', array());
        $objFormParam->addParam('選択店舗コード', 'tpl_shop_id', INT_LEN, 'n', array());
    }

     // 編集前処理.
    function lfPreEdit(&$arrForm, $shop_id, $arrShop) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $result = $objQuery->select('*', 'dtb_real_shop', 'shop_id = ?', array($shop_id));
        if(count($result) == 0) return;

        $shop = $result[0];

        $arrForm['shop_id']     = $shop['shop_id'];
        $arrForm['name']        = $shop['name'];
        $arrForm['zip01']       = $shop['zip01'];
        $arrForm['zip02']       = $shop['zip02'];
        $arrForm['pref']        = $shop['pref'];
        $arrForm['addr01']      = $shop['addr01'];
        $arrForm['addr02']      = $shop['addr02'];
        $arrForm['tel01']       = $shop['tel01'];
        $arrForm['tel02']       = $shop['tel02'];
        $arrForm['tel03']       = $shop['tel03'];
        $arrForm['fax01']       = $shop['fax01'];
        $arrForm['fax02']       = $shop['fax02'];
        $arrForm['fax03']       = $shop['fax03'];
        $arrForm['valid']       = $shop['valid'];
    }

    // 入力エラーチェック.
    function lfCheckError(&$arrForm, &$objFormParam, $shop_id) {

        $arrErr = $objFormParam->checkError();
        if (!empty($arrErr)) {
            return $arrErr;
        }

        // shop_id の正当性チェック
        if($arrForm['shop_id'] != $shop_id) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();

            $ret = $objQuery->exists('dtb_real_shop', 'shop_id = ?', array($arrForm['shop_id']));
            if($ret) {
                $arrErr['shop_id'] = '既に存在する店舗コードです。';
            }
        }

        return $arrErr;
    }

    // 店舗情報新規登録.
    function lfInsert(&$arrForm) {

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // INSERTする値を作成する
        $sqlval['shop_id']     = $arrForm['shop_id'];
        $sqlval['name']        = $arrForm['name'];
        $sqlval['zip01']       = $arrForm['zip01'];
        $sqlval['zip02']       = $arrForm['zip02'];
        $sqlval['pref']        = $arrForm['pref'];
        $sqlval['addr01']      = $arrForm['addr01'];
        $sqlval['addr02']      = $arrForm['addr02'];
        $sqlval['tel01']       = $arrForm['tel01'];
        $sqlval['tel02']       = $arrForm['tel02'];
        $sqlval['tel03']       = $arrForm['tel03'];
        $sqlval['fax01']       = $arrForm['fax01'];
        $sqlval['fax02']       = $arrForm['fax02'];
        $sqlval['fax03']       = $arrForm['fax03'];
        $sqlval['valid']       = $arrForm['valid'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $sqlval['create_date'] = 'CURRENT_TIMESTAMP';

        // INSERTの実行
        $objQuery->insert('dtb_real_shop', $sqlval);
    }

    // 情報更新.
    function lfUpdate($shop_id, &$arrForm) {

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // UPDATEする値を作成する
        $sqlval['shop_id']     = $arrForm['shop_id'];
        $sqlval['name']        = $arrForm['name'];
        $sqlval['zip01']       = $arrForm['zip01'];
        $sqlval['zip02']       = $arrForm['zip02'];
        $sqlval['pref']        = $arrForm['pref'];
        $sqlval['addr01']      = $arrForm['addr01'];
        $sqlval['addr02']      = $arrForm['addr02'];
        $sqlval['tel01']       = $arrForm['tel01'];
        $sqlval['tel02']       = $arrForm['tel02'];
        $sqlval['tel03']       = $arrForm['tel03'];
        $sqlval['fax01']       = $arrForm['fax01'];
        $sqlval['fax02']       = $arrForm['fax02'];
        $sqlval['fax03']       = $arrForm['fax03'];
        $sqlval['valid']       = $arrForm['valid'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';

        $where = 'shop_id = ?';

        // UPDATEの実行
        $objQuery->update('dtb_real_shop', $sqlval, $where, array($shop_id));
    }

    // 店舗情報削除.
    function lfDelete($shop_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // UPDATEする値を作成する
        $sqlval['del_flg']     = '1';
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';

        $where = 'shop_id = ?';

        // UPDATEの実行
        $objQuery->update('dtb_real_shop', $sqlval, $where, array($shop_id));
    }
}
