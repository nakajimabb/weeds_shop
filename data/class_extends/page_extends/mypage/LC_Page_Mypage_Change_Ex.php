<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage_Change.php';

/**
 * 登録内容変更 のページクラス(拡張).
 *
 * LC_Page_Mypage_Change をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_Change_Ex extends LC_Page_Mypage_Change
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();

        $this->arrShop = SC_RealShop::GetRealShopNameList(true);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }

    public function action()
    {
        $objCustomer = new SC_Customer_Ex();
        $customer_id = $objCustomer->getValue('customer_id');

        // mobile用（戻るボタンでの遷移かどうかを判定）
        if (!empty($_POST['return'])) {
            $_REQUEST['mode'] = 'return';
        }

        // パラメーター管理クラス,パラメーター情報の初期化
        $objFormParam = new SC_FormParam_Ex();
        // SC_Helper_Customer_Ex::sfCustomerMypageParam($objFormParam);
        $this->lfInitParam($objFormParam);  // mod naka
        $objFormParam->setParam($_POST);    // POST値の取得

        switch ($this->getMode()) {
            // 確認
            case 'confirm':
                if (isset($_POST['submit_address'])) {
                    // 入力エラーチェック
                    $this->arrErr = $this->lfCheckError($_POST);
                    // comment out naka
                    // // 入力エラーの場合は終了
                    // if (count($this->arrErr) == 0) {
                    //     // 郵便番号検索文作成
                    //     $zipcode = $_POST['zip01'] . $_POST['zip02'];

                    //     // 郵便番号検索
                    //     $arrAdsList = SC_Utils_Ex::sfGetAddress($zipcode);

                    //     // 郵便番号が発見された場合
                    //     if (!empty($arrAdsList)) {
                    //         $data['pref'] = $arrAdsList[0]['state'];
                    //         $data['addr01'] = $arrAdsList[0]['city']. $arrAdsList[0]['town'];
                    //         $objFormParam->setParam($data);
                    //     }
                    //     // 該当無し
                    //     else {
                    //         $this->arrErr['zip01'] = '※該当する住所が見つかりませんでした。<br>';
                    //     }
                    // }
                    break;
                }
                $this->arrErr = SC_Helper_Customer_Ex::sfCustomerMypageErrorCheck($objFormParam);

                // 入力エラーなし
                if (empty($this->arrErr)) {
                    //パスワード表示
                    $this->passlen      = SC_Utils_Ex::sfPassLen(strlen($objFormParam->getValue('password')));

                    $this->tpl_mainpage = 'mypage/change_confirm.tpl';
                    $this->tpl_title    = '会員登録(確認ページ)';
                    $this->tpl_subtitle = '会員登録内容変更(確認ページ)';
                }
                break;
            // 会員登録と完了画面
            case 'complete':
                $this->arrErr = SC_Helper_Customer_Ex::sfCustomerMypageErrorCheck($objFormParam);

                // 入力エラーなし
                if (empty($this->arrErr)) {
                    // 会員情報の登録
                    $this->lfRegistCustomerData($objFormParam, $customer_id);

                    //セッション情報を最新の状態に更新する
                    $objCustomer->updateSession();

                    // 完了ページに移動させる。
                    SC_Response_Ex::sendRedirect('change_complete.php');
                }
                break;
            // 確認ページからの戻り
            case 'return':
                // quiet.
                break;
            default:
                $objFormParam->setParam(SC_Helper_Customer_Ex::sfGetCustomerData($customer_id));
                break;
        }
        $this->arrForm = $objFormParam->getFormParamList();
    }

    function lfInitParam(&$objFormParam, $isAdmin = false) {
        $objFormParam->addParam('社員番号', 'staff_no', STEXT_LEN, 'aKV', array('NO_SPTAB', 'SPTAB_CHECK' ,'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お名前(姓)', 'name01', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'NO_SPTAB', 'SPTAB_CHECK' ,'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お名前(名)', 'name02', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'NO_SPTAB', 'SPTAB_CHECK' , 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号1', 'tel01', TEL_ITEM_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号2', 'tel02', TEL_ITEM_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号3', 'tel03', TEL_ITEM_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));

        $objFormParam->addParam('パスワード', 'password', STEXT_LEN, 'a', array('EXIST_CHECK', 'SPTAB_CHECK', 'ALNUM_CHECK', 'MAX_LENGTH_CHECK'));
        //$objFormParam->addParam('パスワード確認用の質問の答え', 'reminder_answer', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        //$objFormParam->addParam('パスワード確認用の質問', 'reminder', STEXT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));

        $objFormParam->addParam('メールアドレス', 'email', null, 'a', array('NO_SPTAB', 'EXIST_CHECK', 'EMAIL_CHECK', 'SPTAB_CHECK' ,'EMAIL_CHAR_CHECK'));
        $objFormParam->addParam('パスワード(確認)', 'password02', STEXT_LEN, 'a', array('EXIST_CHECK', 'SPTAB_CHECK' ,'ALNUM_CHECK'), '', false);
        if (!$isAdmin) {
            $objFormParam->addParam('メールアドレス(確認)', 'email02', null, 'a', array('NO_SPTAB', 'EXIST_CHECK', 'EMAIL_CHECK','SPTAB_CHECK' , 'EMAIL_CHAR_CHECK'), '', false);
        }
        $objFormParam->addParam('受け取り店舗', 'default_shop_id', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    }    

    public function lfCheckError($arrRequest)
    {
        // // パラメーター管理クラス
        // $objFormParam = new SC_FormParam_Ex();
        // // パラメーター情報の初期化
        // $objFormParam->addParam('郵便番号1', 'zip01', ZIP01_LEN, 'n', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'NUM_CHECK'));
        // $objFormParam->addParam('郵便番号2', 'zip02', ZIP02_LEN, 'n', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'NUM_CHECK'));
        // // // リクエスト値をセット
        // $arrData['zip01'] = $arrRequest['zip01'];
        // $arrData['zip02'] = $arrRequest['zip02'];
        // $objFormParam->setParam($arrData);
        // エラーチェック
        $arrErr = $objFormParam->checkError();

        return $arrErr;
    }
}
