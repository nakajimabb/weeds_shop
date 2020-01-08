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

require_once CLASS_REALDIR . 'helper/SC_Helper_Mail.php';

/**
 * メール関連のヘルパークラス(拡張).
 *
 * LC_Helper_Mail をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author EC-CUBE CO.,LTD.
 * @version $Id:SC_Helper_DB_Ex.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class SC_Helper_Mail_Ex extends SC_Helper_Mail
{
    /* DBに登録されたテンプレートメールの送信 */
    public function sfSendTemplateMail($to, $to_name, $template_id, &$objPage, $from_address = '', $from_name = '', $reply_to = '', $bcc = '')
    {
        $arrShop = SC_RealShop::GetRealShopNameList();  // add naka
        parent::sfSendTemplateMail($to, $to_name, $template_id, $objPage, $from_address, $from_name, $reply_to, $bcc);
    }

    /* 受注完了メール送信 */
    public function sfSendOrderMail($order_id, $template_id, $subject = '', $header = '', $footer = '', $send = true)
    {
        $arrTplVar = new stdClass();
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $arrTplVar->arrInfo = $arrInfo;

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        if ($subject == '' && $header == '' && $footer == '') {
            // メールテンプレート情報の取得
            $objMailtemplate = new SC_Helper_Mailtemplate_Ex();
            $mailtemplate = $objMailtemplate->get($template_id);
            $arrTplVar->tpl_header = $mailtemplate['header'];
            $arrTplVar->tpl_footer = $mailtemplate['footer'];
            $tmp_subject = $mailtemplate['subject'];
        } else {
            $arrTplVar->tpl_header = $header;
            $arrTplVar->tpl_footer = $footer;
            $tmp_subject = $subject;
        }

        // 受注情報の取得
        $where = 'order_id = ? AND del_flg = 0';
        $arrOrder = $objQuery->getRow('*', 'dtb_order', $where, array($order_id));

        if (empty($arrOrder)) {
            trigger_error("該当する受注が存在しない。(注文番号: $order_id)", E_USER_ERROR);
        }
        // <-- add naka
        else {
            // 受け取り店舗名
            $arrShop = SC_RealShop::GetRealShopNameList();
            $arrOrder['receive_shop'] = $arrShop[$arrOrder['receive_shop_id']];
        }
        // <--

        $where = 'order_id = ?';
        $objQuery->setOrder('order_detail_id');
        $arrTplVar->arrOrderDetail = $objQuery->select('*', 'dtb_order_detail', $where, array($order_id));

        $objProduct = new SC_Product_Ex();
        $objQuery->setOrder('shipping_id');
        $arrRet = $objQuery->select('*', 'dtb_shipping', 'order_id = ?', array($order_id));
        foreach ($arrRet as $key => $value) {
            $objQuery->setOrder('shipping_id');
            $arrItems = $objQuery->select('*', 'dtb_shipment_item', 'order_id = ? AND shipping_id = ?',
                                          array($order_id, $arrRet[$key]['shipping_id']));
            foreach ($arrItems as $arrDetail) {
                foreach ($arrDetail as $detailKey => $detailVal) {
                    $arrRet[$key]['shipment_item'][$arrDetail['product_class_id']][$detailKey] = $detailVal;
                }

                $arrRet[$key]['shipment_item'][$arrDetail['product_class_id']]['productsClass'] =& $objProduct->getDetailAndProductsClass($arrDetail['product_class_id']);
            }
        }
        $arrTplVar->arrShipping = $arrRet;

        $arrTplVar->Message_tmp = $arrOrder['message'];

        // 会員情報の取得
        $customer_id = $arrOrder['customer_id'];
        $objQuery->setOrder('customer_id');
        $arrRet = $objQuery->select('point', 'dtb_customer', 'customer_id = ?', array($customer_id));
        $arrCustomer = isset($arrRet[0]) ? $arrRet[0] : '';

        $arrTplVar->arrCustomer = $arrCustomer;
        $arrTplVar->arrOrder = $arrOrder;

        //その他決済情報
        if ($arrOrder['memo02'] != '') {
            $arrOther = unserialize($arrOrder['memo02']);

            foreach ($arrOther as $other_key => $other_val) {
                if (SC_Utils_Ex::sfTrim($other_val['value']) == '') {
                    $arrOther[$other_key]['value'] = '';
                }
            }

            $arrTplVar->arrOther = $arrOther;
        }

        // 都道府県変換
        $arrTplVar->arrPref = $this->arrPref;
        // 国変換
        $arrTplVar->arrCountry = $this->arrCountry;

        $objCustomer = new SC_Customer_Ex();
        $arrTplVar->tpl_user_point = $objCustomer->getValue('point');

        $objMailView = null;
        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            $objMailView = new SC_MobileView_Ex();
        } else {
            $objMailView = new SC_SiteView_Ex();
        }
        // メール本文の取得
        $objMailView->setPage($this->getPage());
        $objMailView->assignobj($arrTplVar);
        $body = $objMailView->fetch($this->arrMAILTPLPATH[$template_id]);

        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $bcc = $arrInfo['email01'];
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $tosubject = $this->sfMakeSubject($tmp_subject, $objMailView);

        $objSendMail->setItem('', $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
        $objSendMail->setTo($arrOrder['order_email'], $arrOrder['order_name01'] . ' '. $arrOrder['order_name02'] .' 様');

        // 送信フラグ:trueの場合は、送信する。
        if ($send) {
            if ($objSendMail->sendMail()) {
                $this->sfSaveMailHistory($order_id, $template_id, $tosubject, $body);
            }
        }

        return $objSendMail;
    }
}
