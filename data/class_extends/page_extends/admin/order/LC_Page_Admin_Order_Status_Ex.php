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

require_once CLASS_REALDIR . 'pages/admin/order/LC_Page_Admin_Order_Status.php';

/**
 * 対応状況管理 のページクラス(拡張).
 *
 * LC_Page_Admin_Order_Status をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Order_Status_Ex extends LC_Page_Admin_Order_Status
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
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

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        $objDb = new SC_Helper_DB_Ex();

        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        // 入力値の変換
        $objFormParam->convParam();

        $this->arrForm = $objFormParam->getHashArray();

        //支払方法の取得
        $this->arrPayment = SC_Helper_Payment_Ex::getIDValueList();

        switch ($this->getMode()) {
            case 'update':
                switch ($objFormParam->getValue('change_status')) {
                    // // 削除
                    // case 'delete':
                    //     $this->lfDelete($objFormParam->getValue('move'));
                    //     break;
                    // 発送済
                    case ORDER_DELIV:
                        $this->lfStatusMove($objFormParam->getValue('change_status'), $objFormParam->getValue('move'));
                        $this->lfSendMail($objFormParam->getValue('move'));
                        $this->tpl_onload = "window.alert('発送完了メールを送信しました。');";
                        break;
                    default:
                        $this->lfStatusMove($objFormParam->getValue('change_status'), $objFormParam->getValue('move'));
                        break;
                }
                break;

            case 'search':
            default:
                break;
        }

        // 対応状況
        $status = $objFormParam->getValue('status');
        if (strlen($status) === 0) {
                //デフォルトで新規受付一覧表示
                $status = ORDER_NEW;
        }
        $this->SelectedStatus = $status;
        //検索結果の表示
        $this->lfStatusDisp($status, $objFormParam->getValue('search_pageno'));
    }

    // メール送信（対応状況が発送済に変更されたとき）  
    function lfSendMail($arrOrderId) {

        if (!isset($arrOrderId) || !is_array($arrOrderId)) {
            return false;
        }

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $cols = '*';
        $table = 'dtb_order';
        $where = 'order_id = ? AND del_flg = 0';
        
        foreach ($arrOrderId as $orderId) {
            $whereVal = array($orderId);
            $arrOrder = $objQuery->select($cols, $table, $where, $whereVal);

            if(empty($arrOrder)) continue;
            $arrOrder[0]['receive_shop'] = $this->arrShop[$arrOrder[0]['receive_shop_id']];
            
            $CONF           = SC_Helper_DB_Ex::sfGetBasisData();
            $arrOrderDetail = $this->lfGetOrderDetail($arrOrder[0]['order_id']);

            $objMailText    = new SC_SiteView_Ex();
            $objMailText->setPage($this);
            $objMailText->assign('CONF', $CONF);
            $objMailText->assign('arrOrder', $arrOrder[0]);
            $objMailText->assign('arrOrderDetail', $arrOrderDetail);
            $objMailText->assignobj($this);//

            $objHelperMail  = new SC_Helper_Mail_Ex();
            $objHelperMail->setPage($this);//

            $subject        = $objHelperMail->sfMakeSubject('受取店舗への発送のお知らせ');
            $toCustomerMail = $objMailText->fetch('mail_templates/order_shipment.tpl');
//            SC_Utils::sfPrintR($toCustomerMail);

            $objMail = new SC_SendMail_Ex();
            $objMail->setItem(
                ''                    // 宛先
                , $subject              // サブジェクト
                , $toCustomerMail       // 本文
                , $CONF['email03']      // 配送元アドレス
                , $CONF['shop_name']    // 配送元 名前
                , $CONF['email03']      // reply_to
                , $CONF['email04']      // return_path
                , $CONF['email04']      // Errors_to
                , $CONF['email01']      // Bcc
            );
            // 宛先の設定
            $objMail->setTo($arrOrder['order_email'],
                            $arrOrder['order_name01'] . $arrOrder['order_name02'] .' 様');

            $objMail->sendMail();
        }
    }

    // 受注詳細データの取得
    function lfGetOrderDetail($order_id) {
        $objQuery       = SC_Query_Ex::getSingletonInstance();

        $order_count    = $objQuery->count('dtb_order', 'order_id = ?', array($order_id));
        if ($order_count != 1) return array();

        $col    = 'dtb_order_detail.product_name, dtb_order_detail.price, dtb_order_detail.product_class_id, dtb_order_detail.quantity, dtb_products_class.product_code';
        $table  = 'dtb_order_detail LEFT JOIN dtb_products_class ON dtb_order_detail.product_class_id = dtb_products_class.product_class_id';
        $where  = 'order_id = ?';
        $objQuery->setOrder('order_detail_id');
        $arrOrderDetail = $objQuery->select($col, $table, $where, array($order_id));
        return $arrOrderDetail;
    }

}
