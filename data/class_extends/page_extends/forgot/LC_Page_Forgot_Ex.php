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

require_once CLASS_REALDIR . 'pages/forgot/LC_Page_Forgot.php';

/**
 * パスワード発行 のページクラス(拡張).
 *
 * LC_Page_Forgot をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Forgot_Ex extends LC_Page_Forgot
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
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
        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();

        switch ($this->getMode()) {
            case 'mail_check':
                $this->lfInitMailCheckParam($objFormParam, $this->device_type);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $objFormParam->toLower('email');
                $this->arrForm = $objFormParam->getHashArray();
                $this->arrErr = $objFormParam->checkError();
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    $this->errmsg = $this->lfCheckForgotMail($this->arrForm, $this->arrReminder);
                    if (SC_Utils_Ex::isBlank($this->errmsg)) {

                        // --> mod naka
                        // $this->tpl_mainpage = 'forgot/secret.tpl';

                        // 完了ページへ移動する
                        $this->tpl_mainpage = 'forgot/complete.tpl';
                        // transactionidを更新させたいので呼び出し元(ログインフォーム側)をリロード。
                        $this->tpl_onload .= 'opener.location.reload(true);';
                        // <--
                    }
                }
                break;
            default:
                break;
        }

        // ポップアップ用テンプレート設定
        if ($this->device_type == DEVICE_TYPE_PC) {
            $this->setTemplate($this->tpl_mainpage);
        }

    }

    public function lfCheckForgotMail(&$arrForm, &$arrReminder)
    {
        $errmsg = NULL;
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $cols = 'customer_id, reminder, reminder_answer, salt, status';     // add naka
        $where = '(email = ? OR email_mobile = ?) AND name01 = ? AND name02 = ? AND del_flg = 0';
        $arrVal = array($arrForm['email'], $arrForm['email'], $arrForm['name01'], $arrForm['name02']);
        // $result = $objQuery->select('reminder, status', 'dtb_customer', $where, $arrVal);
        $result = $objQuery->select($cols, 'dtb_customer', $where, $arrVal);    // mod naka

        // mod
        // if (isset($result[0]['reminder']) and isset($arrReminder[$result[0]['reminder']])) {
        if(count($result) === 1) {

            // 会員状態の確認
            if ($result[0]['status'] == '2') {
                // 正会員
                $arrForm['reminder'] = $result[0]['reminder'];
                // --> add naka
                $new_password = GC_Utils_Ex::gfMakePassword(8);
                if (FORGOT_MAIL == 1) {
                    // メールで変更通知をする
                    $objDb = new SC_Helper_DB_Ex();
                    $CONF = $objDb->sfGetBasisData();
                    $this->lfSendMail($CONF, $arrForm['email'], $arrForm['name01'], $new_password);
                }
                $sqlval = array();
                $sqlval['password'] = $new_password;
                SC_Helper_Customer_Ex::sfEditCustomerData($sqlval, $result[0]['customer_id']);
                $arrForm['new_password'] = $new_password;
                // <--
            } elseif ($result[0]['status'] == '1') {
                // 仮会員
                $errmsg = 'ご入力のemailアドレスは現在仮登録中です。<br/>登録の際にお送りしたメールのURLにアクセスし、<br/>本会員登録をお願いします。';
            }
        } else {
            $errmsg = 'お名前に間違いがあるか、このメールアドレスは登録されていません。';
        }

        return $errmsg;
    }
}
