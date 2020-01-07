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

require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_Payment.php';

/**
 * 支払い方法選択 のページクラス(拡張).
 *
 * LC_Page_Shopping_Payment をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Shopping_Payment_Ex extends LC_Page_Shopping_Payment
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();

        $this->tpl_title = '受け取り店舗・お支払方法等の指定';

        // 受け取り店舗一覧の取得
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

        $objSiteSess = new SC_SiteSession_Ex();
        $objCartSess = new SC_CartSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objFormParam = new SC_FormParam_Ex();
        $objDelivery = new SC_Helper_Delivery_Ex();

        $this->is_multiple = $objPurchase->isMultiple();

        // カートの情報を取得
        $this->arrShipping = $objPurchase->getShippingTemp($this->is_multiple);

        $this->tpl_uniqid = $objSiteSess->getUniqId();
        $cart_key = $objCartSess->getKey();
        $this->cartKey = $cart_key;
        $objPurchase->verifyChangeCart($this->tpl_uniqid, $objCartSess);

        // 配送業者を取得
        $this->arrDeliv = $objDelivery->getList($cart_key);
        $this->is_single_deliv = $this->isSingleDeliv($this->arrDeliv);

        // 会員情報の取得
        if ($objCustomer->isLoginSuccess(true)) {
            $this->tpl_login = '1';
            $this->tpl_user_point = $objCustomer->getValue('point');
            $this->name01 = $objCustomer->getValue('name01');
            $this->name02 = $objCustomer->getValue('name02');
            $this->default_shop_id = $objCustomer->getValue('default_shop_id'); // add naka
        }

        // 戻り URL の設定
        // @deprecated 2.12.0 テンプレート直書きに戻した
        $this->tpl_back_url = '?mode=return';

        $arrOrderTemp = $objPurchase->getOrderTemp($this->tpl_uniqid);
        // 正常に受注情報が格納されていない場合はカート画面へ戻す
        if (SC_Utils_Ex::isBlank($arrOrderTemp)) {
            SC_Response_Ex::sendRedirect(CART_URL);
            SC_Response_Ex::actionExit();
        }

        // カート内商品の妥当性チェック
        $this->tpl_message = $objCartSess->checkProducts($cart_key);
        if (strlen($this->tpl_message) >= 1) {
            SC_Response_Ex::sendRedirect(CART_URL);
            SC_Response_Ex::actionExit();
        }

        /*
         * 購入金額の取得
         * ここでは送料を加算しない
         */
        $this->arrPrices = $objCartSess->calculate($cart_key, $objCustomer);

        // お届け日一覧の取得
        $this->arrDelivDate = $objPurchase->getDelivDate($objCartSess, $cart_key);

        switch ($this->getMode()) {
            /*
             * 配送業者選択時のアクション
             * モバイル端末以外の場合は, JSON 形式のデータを出力し, ajax で取得する.
             */
            case 'select_deliv':
                $this->setFormParams($objFormParam, $arrOrderTemp, true, $this->arrShipping);
                $objFormParam->setParam($_POST);
                $this->arrErr = $objFormParam->checkError();
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    $deliv_id = $objFormParam->getValue('deliv_id');
                    $arrSelectedDeliv = $this->getSelectedDeliv($objCartSess, $deliv_id);
                    $arrSelectedDeliv['error'] = false;
                } else {
                    $arrSelectedDeliv = array('error' => true);
                    $this->tpl_mainpage = 'shopping/select_deliv.tpl'; // モバイル用
                }

                if (SC_Display_Ex::detectDevice() != DEVICE_TYPE_MOBILE) {
                    echo SC_Utils_Ex::jsonEncode($arrSelectedDeliv);
                    SC_Response_Ex::actionExit();
                } else {
                    $this->arrPayment = $arrSelectedDeliv['arrPayment'];
                    $this->arrDelivTime = $arrSelectedDeliv['arrDelivTime'];
                }
                break;

            // 登録処理
            case 'confirm':
                // パラメーター情報の初期化
                $this->setFormParams($objFormParam, $_POST, false, $this->arrShipping);

                $deliv_id = $objFormParam->getValue('deliv_id');
                $arrSelectedDeliv = $this->getSelectedDeliv($objCartSess, $deliv_id);
                $this->arrPayment = $arrSelectedDeliv['arrPayment'];
                $this->arrDelivTime = $arrSelectedDeliv['arrDelivTime'];
                $this->img_show = $arrSelectedDeliv['img_show'];

                $this->arrErr = $this->lfCheckError($objFormParam, $this->arrPrices['subtotal'], $this->tpl_user_point);

                if (empty($this->arrErr)) {
                    $this->saveShippings($objFormParam, $this->arrDelivTime);
                    $this->lfRegistData($this->tpl_uniqid, $objFormParam->getDbArray(), $objPurchase, $this->arrPayment);

                    // 正常に登録されたことを記録しておく
                    $objSiteSess->setRegistFlag();

                    // 確認ページへ移動
                    SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
                    SC_Response_Ex::actionExit();
                }

                break;

            // 前のページに戻る
            case 'return':

                // 正常な推移であることを記録しておく
                $objSiteSess->setRegistFlag();

                $url = null;
                if ($this->is_multiple) {
                    $url = MULTIPLE_URLPATH . '?from=multiple';
                } elseif ($objCustomer->isLoginSuccess(true)) {
                    // if ($this->cartKey == PRODUCT_TYPE_DOWNLOAD) {
                    //     $url = CART_URL;
                    // } else {
                    //     $url = DELIV_URLPATH;
                    // }
                    $url = CART_URLPATH;
                } else {
                    $url = SHOPPING_URL . '?from=nonmember';
                }

                SC_Response_Ex::sendRedirect($url);
                SC_Response_Ex::actionExit();
                break;

            default:
                // FIXME 前のページから戻ってきた場合は別パラメーター(mode)で処理分岐する必要があるのかもしれない
                $this->setFormParams($objFormParam, $arrOrderTemp, false, $this->arrShipping);

                if (!$this->is_single_deliv) {
                    $deliv_id = $objFormParam->getValue('deliv_id');
                } else {
                    $deliv_id = $this->arrDeliv[0]['deliv_id'];
                }

                if (!SC_Utils_Ex::isBlank($deliv_id)) {
                    $objFormParam->setValue('deliv_id', $deliv_id);
                    $arrSelectedDeliv = $this->getSelectedDeliv($objCartSess, $deliv_id);
                    $this->arrPayment = $arrSelectedDeliv['arrPayment'];
                    $this->arrDelivTime = $arrSelectedDeliv['arrDelivTime'];
                    $this->img_show = $arrSelectedDeliv['img_show'];
                }
                break;
        }

        // モバイル用 ポストバック処理
        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE
            && SC_Utils_Ex::isBlank($this->arrErr)) {
            $this->tpl_mainpage = $this->getMobileMainpage($this->is_single_deliv, $this->getMode());
        }

        $this->arrForm = $objFormParam->getFormParamList();
    }

    public function lfInitParam(&$objFormParam, $deliv_only, &$arrShipping)
    {
        $objFormParam->addParam('配送業者', 'deliv_id', INT_LEN, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('ポイント', 'use_point', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'ZERO_START'));
        $objFormParam->addParam('その他お問い合わせ', 'message', LTEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ポイントを使用する', 'point_check', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '2');
        $objFormParam->addParam('受け取り店舗', 'receive_shop_id', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK'), '2');     // add naka

        if ($deliv_only) {
            $objFormParam->addParam('お支払い方法', 'payment_id', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        } else {
            $objFormParam->addParam('お支払い方法', 'payment_id', INT_LEN, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));

            foreach ($arrShipping as $val) {
                $objFormParam->addParam('お届け時間', 'deliv_time_id' . $val['shipping_id'], INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
                $objFormParam->addParam('お届け日', 'deliv_date' . $val['shipping_id'], STEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
            }
        }

        $objFormParam->setParam($arrShipping);
        $objFormParam->convParam();
    }
}
