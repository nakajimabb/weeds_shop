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

require_once CLASS_REALDIR . 'SC_CartSession.php';

class SC_CartSession_Ex extends SC_CartSession
{
    // 全商品の合計価格
    public function getAllProductsTotal($productTypeId, $pref_id = 0, $country_id = 0)
    {
        // 税込み合計
        $price_total = 0;   // 総計（税抜）
        $max = $this->getMax($productTypeId);
        for ($i = 0; $i <= $max; $i++) {
            if (!isset($this->cartSession[$productTypeId][$i]['price'])) {
                $this->cartSession[$productTypeId][$i]['price'] = '';
            }

            $price = $this->cartSession[$productTypeId][$i]['price'];

            if (!isset($this->cartSession[$productTypeId][$i]['quantity'])) {
                $this->cartSession[$productTypeId][$i]['quantity'] = '';
            }
            $quantity = $this->cartSession[$productTypeId][$i]['quantity'];

            $price_total += $price * $quantity;
        }
        $total = SC_Helper_TaxRule_Ex::sfCalcIncTax($price_total, 0, 0, $pref_id, $country_id);

        return $total;
    }
    // 全商品の合計税金
    public function getAllProductsTax($productTypeId, $pref_id = 0, $country_id = 0)
    {
        // 税合計
        $price_total = 0;   // 総計（税抜）
        $max = $this->getMax($productTypeId);
        for ($i = 0; $i <= $max; $i++) {
            $price = $this->cartSession[$productTypeId][$i]['price'];
            $quantity = $this->cartSession[$productTypeId][$i]['quantity'];
            $price_total += $price * $quantity;
        }
        $total = SC_Helper_TaxRule_Ex::sfTax($price_total, 0, 0, $pref_id, $country_id);

        return $total;
    }

    /**
     * 商品種別ごとにカート内商品の一覧を取得する.
     *
     * @param  integer $productTypeId 商品種別ID
     * @param  integer $pref_id       税金計算用注文者都道府県ID
     * @param  integer $country_id    税金計算用注文者国ID
     * @return array   カート内商品一覧の配列
     */
    public function getCartList($productTypeId, $pref_id = 0, $country_id = 0)
    {
        $objProduct = new SC_Product_Ex();
        $max = $this->getMax($productTypeId);
        $arrRet = array();
/*

        $const_name = '_CALLED_SC_CARTSESSION_GETCARTLIST_' . $productTypeId;
        if (defined($const_name)) {
            $is_first = true;
        } else {
            define($const_name, true);
            $is_first = false;
        }

*/
        for ($i = 0; $i <= $max; $i++) {
            if (isset($this->cartSession[$productTypeId][$i]['cart_no'])
                && $this->cartSession[$productTypeId][$i]['cart_no'] != '') {

                // 商品情報は常に取得
                // TODO: 同一インスタンス内では1回のみ呼ぶようにしたい
                // TODO: ここの商品の合計処理は getAllProductsTotalや getAllProductsTaxとで類似重複なので統一出来そう
/*
                // 同一セッション内では初回のみDB参照するようにしている
                if (!$is_first) {
                    $this->setCartSession4getCartList($productTypeId, $i);
                }
*/

                $this->cartSession[$productTypeId][$i]['productsClass']
                    =& $objProduct->getDetailAndProductsClass($this->cartSession[$productTypeId][$i]['id']);

                $price = $this->cartSession[$productTypeId][$i]['productsClass']['price02'];
                $this->cartSession[$productTypeId][$i]['price'] = $price;

                $this->cartSession[$productTypeId][$i]['point_rate']
                    = $this->cartSession[$productTypeId][$i]['productsClass']['point_rate'];

                $quantity = $this->cartSession[$productTypeId][$i]['quantity'];

                $arrTaxRule = SC_Helper_TaxRule_Ex::getTaxRule(
                                    $this->cartSession[$productTypeId][$i]['productsClass']['product_id'],
                                    $this->cartSession[$productTypeId][$i]['productsClass']['product_class_id'],
                                    $pref_id,
                                    $country_id);
                $incTax = $price + SC_Helper_TaxRule_Ex::calcTax($price, $arrTaxRule['tax_rate'], $arrTaxRule['tax_rule'], $arrTaxRule['tax_adjust']);

                $total = $incTax * $quantity;
                $this->cartSession[$productTypeId][$i]['price_inctax'] = $incTax;
                $this->cartSession[$productTypeId][$i]['total_inctax'] = $total;
                $this->cartSession[$productTypeId][$i]['tax_rate'] = $arrTaxRule['tax_rate'];
                $this->cartSession[$productTypeId][$i]['tax_rule'] = $arrTaxRule['tax_rule'];
                $this->cartSession[$productTypeId][$i]['tax_adjust'] = $arrTaxRule['tax_adjust'];
                $this->cartSession[$productTypeId][$i]['total'] = $price * $quantity;       // add by naka

                $arrRet[] = $this->cartSession[$productTypeId][$i];

                // セッション変数のデータ量を抑制するため、一部の商品情報を切り捨てる
                // XXX 上で「常に取得」するのだから、丸ごと切り捨てて良さそうにも感じる。
                $this->adjustSessionProductsClass($this->cartSession[$productTypeId][$i]['productsClass']);
            }
        }

        return $arrRet;
    }

    /**
     * getCartList用にcartSession情報をセットする
     *
     * @param  integer $product_type_id 商品種別ID
     * @param  integer $key
     * @return void
     *
     * MEMO: せっかく一回だけ読み込みにされてますが、税率対応の関係でちょっと保留
     */
    public function setCartSession4getCartList($productTypeId, $key)
    {
        $objProduct = new SC_Product_Ex();

        $this->cartSession[$productTypeId][$key]['productsClass']
            =& $objProduct->getDetailAndProductsClass($this->cartSession[$productTypeId][$key]['id']);

        $price = $this->cartSession[$productTypeId][$key]['productsClass']['price02'];
        $this->cartSession[$productTypeId][$key]['price'] = $price;

        $this->cartSession[$productTypeId][$key]['point_rate']
            = $this->cartSession[$productTypeId][$key]['productsClass']['point_rate'];

        $quantity = $this->cartSession[$productTypeId][$key]['quantity'];
        $incTax = SC_Helper_TaxRule_Ex::sfCalcIncTax($price,
            $this->cartSession[$productTypeId][$key]['productsClass']['product_id'],
            $this->cartSession[$productTypeId][$key]['id'][0]);

        $total = $incTax * $quantity;

        $this->cartSession[$productTypeId][$key]['price_inctax'] = $incTax;
        $this->cartSession[$productTypeId][$key]['total_inctax'] = $total;
        $this->cartSession[$productTypeId][$key]['total'] = $price * $quantity;     // add by naka
    }
}
