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

require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage_History.php';

/**
 * 購入履歴 のページクラス(拡張).
 *
 * LC_Page_Mypage_History をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_History_Ex extends LC_Page_Mypage_History
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
        //決済処理中ステータスのロールバック
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objPurchase->cancelPendingOrder(PENDING_ORDER_CANCEL_FLAG);

        $objCustomer    = new SC_Customer_Ex();
        $objProduct  = new SC_Product();

        if (!SC_Utils_Ex::sfIsInt($_GET['order_id'])) {
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
        }

        $order_id               = $_GET['order_id'];
        $this->is_price_change  = false;

        //受注データの取得
        $this->tpl_arrOrderData = $objPurchase->getOrder($order_id, $objCustomer->getValue('customer_id'));

        if (empty($this->tpl_arrOrderData)) {
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
        }

        $this->arrShipping      = $this->lfGetShippingDate($objPurchase, $order_id, $this->arrWDAY);

        $this->isMultiple       = count($this->arrShipping) > 1;
        // 支払い方法の取得
        $this->arrPayment       = SC_Helper_Payment_Ex::getIDValueList();
        // 受注商品明細の取得
        $this->tpl_arrOrderDetail = $objPurchase->getOrderDetail($order_id);
        foreach ($this->tpl_arrOrderDetail as $product_index => $arrOrderProductDetail) {
            //必要なのは商品の販売金額のみなので、遅い場合は、別途SQL作成した方が良い
            $arrTempProductDetail = $objProduct->getProductsClass($arrOrderProductDetail['product_class_id']);
            // 税計算
            $this->tpl_arrOrderDetail[$product_index]['price_inctax'] = $this->tpl_arrOrderDetail[$product_index]['price']  +
                SC_Helper_TaxRule_Ex::calcTax (
                    $this->tpl_arrOrderDetail[$product_index]['price'],
                    $this->tpl_arrOrderDetail[$product_index]['tax_rate'],
                    $this->tpl_arrOrderDetail[$product_index]['tax_rule']
                    );
            $arrTempProductDetail['price02_inctax'] = SC_Helper_TaxRule_Ex::sfCalcIncTax(
                    $arrTempProductDetail['price02'],
                    $arrTempProductDetail['product_id'],
                    $arrTempProductDetail['product_class_id']
                    );
            if ($this->tpl_arrOrderDetail[$product_index]['price_inctax'] != $arrTempProductDetail['price02_inctax']) {
                $this->is_price_change = true;
            }
            // comment out by naka
            // $this->tpl_arrOrderDetail[$product_index]['product_price_inctax'] = ($arrTempProductDetail['price02_inctax']) ? $arrTempProductDetail['price02_inctax'] : 0 ;
            // add by naka
            $this->tpl_arrOrderDetail[$product_index]['product_price'] = ($arrTempProductDetail['price02']) ? $arrTempProductDetail['price02'] : 0 ;
        }

        $this->tpl_arrOrderDetail = $this->setMainListImage($this->tpl_arrOrderDetail);
        $objPurchase->setDownloadableFlgTo($this->tpl_arrOrderDetail);
        // モバイルダウンロード対応処理
        $this->lfSetAU($this->tpl_arrOrderDetail);
        // 受注メール送信履歴の取得
        $this->tpl_arrMailHistory = $this->lfGetMailHistory($order_id);
    }
}
