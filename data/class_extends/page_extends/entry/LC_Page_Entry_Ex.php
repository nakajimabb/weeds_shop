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

require_once CLASS_REALDIR . 'pages/entry/LC_Page_Entry.php';

/**
 * 会員登録(入力ページ) のページクラス(拡張).
 *
 * LC_Page_Entry をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Entry_Ex extends LC_Page_Entry
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();

        $this->mode_entry = true;
        $this->arrShop = SC_RealShop::GetRealShopNameList();
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
        //決済処理中ステータスのロールバック
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objPurchase->cancelPendingOrder(PENDING_ORDER_CANCEL_FLAG);

        $objFormParam = new SC_FormParam_Ex();

        // PC時は規約ページからの遷移でなければエラー画面へ遷移する
        if ($this->lfCheckReferer() === false) {
            SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, '', true);
        }

        // SC_Helper_Customer_Ex::sfCustomerEntryParam($objFormParam);
        $this->lfInitParam($objFormParam);  // mod naka
        $objFormParam->setParam($_POST);

        // mobile用（戻るボタンでの遷移かどうかを判定）
        if (!empty($_POST['return'])) {
            $_REQUEST['mode'] = 'return';
        }

        switch ($this->getMode()) {
            case 'confirm':
                if (isset($_POST['submit_address'])) {
                    // 入力エラーチェック
                    $this->arrErr = $this->lfCheckError($_POST);
                    // --> comment by naka
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

                    //         // 該当無し
                    //     } else {
                    //         $this->arrErr['zip01'] = '※該当する住所が見つかりませんでした。<br>';
                    //     }
                    //}
                    // <--
                    break;
                }

                //-- 確認
                // $this->arrErr = SC_Helper_Customer_Ex::sfCustomerEntryErrorCheck($objFormParam);
                list($this->arrErr, $customer_id) = SC_Helper_Customer_Ex::sfCustomerEntryErrorCheck2($objFormParam);
                // 入力エラーなし
                if (empty($this->arrErr)) {
                    //パスワード表示
                    $this->passlen      = SC_Utils_Ex::sfPassLen(strlen($objFormParam->getValue('password')));

                    $this->tpl_mainpage = 'entry/confirm.tpl';
                    $this->tpl_title    = '会員登録(確認ページ)';
                }
                break;
            case 'complete':
                //-- 会員登録と完了画面
                // $this->arrErr = SC_Helper_Customer_Ex::sfCustomerEntryErrorCheck($objFormParam);
                list($this->arrErr, $customer_id) = SC_Helper_Customer_Ex::sfCustomerEntryErrorCheck2($objFormParam);   // mod naka

                if (empty($this->arrErr)) {
                    // $uniqid             = $this->lfRegistCustomerData($this->lfMakeSqlVal($objFormParam));
                    $uniqid             = $this->lfRegistCustomerData($this->lfMakeSqlVal($objFormParam), $customer_id);    // mod naka

                    $this->lfSendMail($uniqid, $objFormParam->getHashArray());

                    // 仮会員が無効の場合
                    if (CUSTOMER_CONFIRM_MAIL == false) {
                        // ログイン状態にする
                        $objCustomer = new SC_Customer_Ex();
                        $objCustomer->setLogin($objFormParam->getValue('email'));
                    }

                    // 完了ページに移動させる。
                    SC_Response_Ex::sendRedirect('complete.php', array('ci' => SC_Helper_Customer_Ex::sfGetCustomerId($uniqid)));
                }
                break;
            case 'return':
                // quiet.
                break;
            default:
                break;
        }
        $this->arrForm = $objFormParam->getFormParamList();
    }

    public function lfRegistCustomerData($sqlval, $customer_id)
    {
        unset($sqlval['staff_no']);     // 念のため社員番号は再登録させない
        // SC_Helper_Customer_Ex::sfEditCustomerData($sqlval);
        SC_Helper_Customer_Ex::sfEditCustomerData($sqlval, $customer_id);

        return $sqlval['secret_key'];
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

    function lfInitParam(&$objFormParam, $isAdmin = false) {
        $objFormParam->addParam('社員番号', 'staff_no', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'NO_SPTAB', 'SPTAB_CHECK' ,'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お名前(姓)', 'name01', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'SPTAB_CHECK' ,'MAX_LENGTH_CHECK'));
        //$objFormParam->addParam('お名前(名)', 'name02', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'NO_SPTAB', 'SPTAB_CHECK' , 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お名前(名)', 'name02', STEXT_LEN, 'aKV', array('NO_SPTAB', 'SPTAB_CHECK' , 'MAX_LENGTH_CHECK'));
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

    // kiyaku.php からの遷移の妥当性をチェックする
    // @return boolean kiyaku.php からの妥当な遷移であれば true
    // ⇒ チェックしない
    public function lfCheckReferer() {
        return true;
    }
}
