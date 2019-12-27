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

require_once CLASS_REALDIR . 'SC_Product.php';

class SC_Product_Ex extends SC_Product
{
    // brand 情報を追加
    public function alldtlSQL($where_products_class = '')
    {
        if (!SC_Utils_Ex::isBlank($where_products_class)) {
            $where_products_class = 'AND (' . $where_products_class . ')';
        }
        /*
         * point_rate, deliv_fee は商品規格(dtb_products_class)ごとに保持しているが,
         * 商品(dtb_products)ごとの設定なので MAX のみを取得する.
         */
        $sql = <<< __EOS__
            (
                SELECT
                     dtb_products.product_id
                    ,dtb_products.name
                    ,dtb_products.maker_id
                    ,dtb_products.brand_id
                    ,dtb_products.status
                    ,dtb_products.comment1
                    ,dtb_products.comment2
                    ,dtb_products.comment3
                    ,dtb_products.comment4
                    ,dtb_products.comment5
                    ,dtb_products.comment6
                    ,dtb_products.note
                    ,dtb_products.main_list_comment
                    ,dtb_products.main_list_image
                    ,dtb_products.main_comment
                    ,dtb_products.main_image
                    ,dtb_products.main_large_image
                    ,dtb_products.sub_title1
                    ,dtb_products.sub_comment1
                    ,dtb_products.sub_image1
                    ,dtb_products.sub_large_image1
                    ,dtb_products.sub_title2
                    ,dtb_products.sub_comment2
                    ,dtb_products.sub_image2
                    ,dtb_products.sub_large_image2
                    ,dtb_products.sub_title3
                    ,dtb_products.sub_comment3
                    ,dtb_products.sub_image3
                    ,dtb_products.sub_large_image3
                    ,dtb_products.sub_title4
                    ,dtb_products.sub_comment4
                    ,dtb_products.sub_image4
                    ,dtb_products.sub_large_image4
                    ,dtb_products.sub_title5
                    ,dtb_products.sub_comment5
                    ,dtb_products.sub_image5
                    ,dtb_products.sub_large_image5
                    ,dtb_products.sub_title6
                    ,dtb_products.sub_comment6
                    ,dtb_products.sub_image6
                    ,dtb_products.sub_large_image6
                    ,dtb_products.del_flg
                    ,dtb_products.creator_id
                    ,dtb_products.create_date
                    ,dtb_products.update_date
                    ,dtb_products.deliv_date_id
                    ,T4.product_code_min
                    ,T4.product_code_max
                    ,T4.price01_min
                    ,T4.price01_max
                    ,T4.price02_min
                    ,T4.price02_max
                    ,T4.stock_min
                    ,T4.stock_max
                    ,T4.stock_unlimited_min
                    ,T4.stock_unlimited_max
                    ,T4.point_rate
                    ,T4.deliv_fee
                    ,T4.class_count
                    ,dtb_maker.name AS maker_name
                    ,dtb_maker.maker_image AS maker_image
                    ,dtb_brand.name AS brand_name
                    ,dtb_brand.brand_image AS brand_image
                FROM dtb_products
                    JOIN (
                        SELECT product_id,
                            MIN(product_code) AS product_code_min,
                            MAX(product_code) AS product_code_max,
                            MIN(price01) AS price01_min,
                            MAX(price01) AS price01_max,
                            MIN(price02) AS price02_min,
                            MAX(price02) AS price02_max,
                            MIN(stock) AS stock_min,
                            MAX(stock) AS stock_max,
                            MIN(stock_unlimited) AS stock_unlimited_min,
                            MAX(stock_unlimited) AS stock_unlimited_max,
                            MAX(point_rate) AS point_rate,
                            MAX(deliv_fee) AS deliv_fee,
                            COUNT(*) as class_count
                        FROM dtb_products_class
                        WHERE del_flg = 0 $where_products_class
                        GROUP BY product_id
                    ) AS T4
                        ON dtb_products.product_id = T4.product_id
                    LEFT JOIN dtb_maker
                        ON dtb_products.maker_id = dtb_maker.maker_id
                    LEFT JOIN dtb_brand
                        ON dtb_products.brand_id = dtb_brand.brand_id
            ) AS alldtl
__EOS__;

        return $sql;
    }

    public function lists(&$objQuery)
    {
        // add maker_id, brand_id
        $col = <<< __EOS__
             product_id
            ,product_code_min
            ,product_code_max
            ,name
            ,maker_id
            ,brand_id
            ,comment1
            ,comment2
            ,comment3
            ,main_list_comment
            ,main_image
            ,main_list_image
            ,price01_min
            ,price01_max
            ,price02_min
            ,price02_max
            ,stock_min
            ,stock_max
            ,stock_unlimited_min
            ,stock_unlimited_max
            ,deliv_date_id
            ,status
            ,del_flg
            ,update_date
__EOS__;
        $res = $objQuery->select($col, $this->alldtlSQL());

        return $res;
    }

    public function setProductsClassByProductIds($arrProductId, $has_deleted = false)
    {
        foreach ($arrProductId as $productId) {
            $arrProductClasses = $this->getProductsClassFullByProductId($productId, $has_deleted);

            $classCats1 = array();
            $classCats1['__unselected'] = '選択してください';

            // 規格1クラス名
            $this->className1[$productId] =
                isset($arrProductClasses[0]['class_name1'])
                ? $arrProductClasses[0]['class_name1']
                : '';

            // 規格2クラス名
            $this->className2[$productId] =
                isset($arrProductClasses[0]['class_name2'])
                ? $arrProductClasses[0]['class_name2']
                : '';

            // 規格1が設定されている
            $this->classCat1_find[$productId] = $arrProductClasses[0]['classcategory_id1'] > 0; // 要変更ただし、他にも改修が必要となる
            // 規格2が設定されている
            $this->classCat2_find[$productId] = $arrProductClasses[0]['classcategory_id2'] > 0; // 要変更ただし、他にも改修が必要となる

            $this->stock_find[$productId] = false;
            $classCategories = array();
            $classCategories['__unselected']['__unselected']['name'] = '選択してください';
            $classCategories['__unselected']['__unselected']['product_class_id'] = $arrProductClasses[0]['product_class_id'];
            // 商品種別
            $classCategories['__unselected']['__unselected']['product_type'] = $arrProductClasses[0]['product_type_id'];
            $this->product_class_id[$productId] = $arrProductClasses[0]['product_class_id'];
            // 商品種別
            $this->product_type[$productId] = $arrProductClasses[0]['product_type_id'];
            foreach ($arrProductClasses as $arrProductsClass) {
                $arrClassCats2 = array();
                $classcategory_id1 = $arrProductsClass['classcategory_id1'];
                $classcategory_id2 = $arrProductsClass['classcategory_id2'];
                // 在庫
                $stock_find_class = ($arrProductsClass['stock_unlimited'] || $arrProductsClass['stock'] > 0);

                $arrClassCats2['classcategory_id2'] = $classcategory_id2;
                $arrClassCats2['name'] = $arrProductsClass['classcategory_name2'] . ($stock_find_class ? '' : ' (品切れ中)');

                $arrClassCats2['stock_find'] = $stock_find_class;

                if ($stock_find_class) {
                    $this->stock_find[$productId] = true;
                }

                if (!in_array($classcat_id1, $classCats1)) {
                    $classCats1[$classcategory_id1] = $arrProductsClass['classcategory_name1']
                        . ($classcategory_id2 == 0 && !$stock_find_class ? ' (品切れ中)' : '');
                }

                // 価格
                // TODO: ここでprice01,price02を税込みにしてよいのか？ _inctax を付けるべき？要検証
                // --> 税抜表記に変更 by naka
                // $arrClassCats2['price01']
                //     = strlen($arrProductsClass['price01'])
                //     ? number_format(SC_Helper_TaxRule_Ex::sfCalcIncTax($arrProductsClass['price01'], $productId, $arrProductsClass['product_class_id']))
                //     : '';
                $arrClassCats2['price01'] = strlen($arrProductsClass['price01']) ? number_format($arrProductsClass['price01']) : '';

                // $arrClassCats2['price02']
                //     = strlen($arrProductsClass['price02'])
                //     ? number_format(SC_Helper_TaxRule_Ex::sfCalcIncTax($arrProductsClass['price02'], $productId, $arrProductsClass['product_class_id']))
                //     : '';
                $arrClassCats2['price02'] = strlen($arrProductsClass['price02']) ? number_format($arrProductsClass['price02']) : '';
                // <--

                // ポイント
                $arrClassCats2['point']
                    = number_format(SC_Utils_Ex::sfPrePoint($arrProductsClass['price02'], $arrProductsClass['point_rate']));

                // 商品コード
                $arrClassCats2['product_code'] = $arrProductsClass['product_code'];
                // 商品規格ID
                $arrClassCats2['product_class_id'] = $arrProductsClass['product_class_id'];
                // 商品種別
                $arrClassCats2['product_type'] = $arrProductsClass['product_type_id'];

                // #929(GC8 規格のプルダウン順序表示不具合)対応のため、2次キーは「#」を前置
                if (!$this->classCat1_find[$productId]) {
                    $classcategory_id1 = '__unselected2';
                }
                $classCategories[$classcategory_id1]['#'] = array(
                    'classcategory_id2' => '',
                    'name' => '選択してください',
                );
                $classCategories[$classcategory_id1]['#' . $classcategory_id2] = $arrClassCats2;
            }

            $this->classCategories[$productId] = $classCategories;

            // 規格1
            $this->classCats1[$productId] = $classCats1;
        }
    }
}
