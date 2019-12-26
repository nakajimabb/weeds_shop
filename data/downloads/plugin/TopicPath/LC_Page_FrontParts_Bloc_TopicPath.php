<?php
/*
 * TopicPath
 * Copyright (C) 2012 LOCKON CO.,LTD. All Rights Reserved.
 * http://www.lockon.co.jp/
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
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

/**
 * パンくずブロックのブロッククラス
 *
 * @package TopicPath
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class LC_Page_FrontParts_Bloc_TopicPath extends LC_Page_FrontParts_Bloc {

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
    }

    /**
     * プロセス.
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
        // 実行元のスクリプトのパス
        $php_self = $_SERVER['SCRIPT_NAME'];
        // カテゴリID
        $category_id = $_GET['category_id'];
        // 商品ID
        $product_id = $_GET['product_id'];
        
        // カテゴリー表示
        if($php_self === ROOT_URLPATH . 'products/list.php' && empty($category_id) === false) {
            // カテゴリーのパンくず生成
            $this->arrTopicPath = $this->getArrTopicPathByCategoryId($category_id);

        // 商品詳細表示
        } else if($php_self === ROOT_URLPATH . 'products/detail.php' && empty($product_id) === false) {
            // カテゴリーのパンくず生成
            $this->arrTopicPath = $this->getArrTopicPathByProductId($product_id);
            // 商品名を取得します.
            $objProduct = new SC_Product_Ex();
            $productDetail = $objProduct->getDetail($product_id);
            $this->tpl_productname  = $productDetail['name'];
        }
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * カテゴリIDをキーにカテゴリ名とリンクの連想配列を作成します.
     * 
     * @param string $category_id カテゴリID
     * @return array カテゴリ名とリンクの連想配列
     */
    function getArrTopicPathByCategoryId($category_id) {
        $arrTopicPath = array();

        // 商品が属するカテゴリIDを縦に取得
        $objDb = new SC_Helper_DB_Ex();
        $arrCatID = $objDb->sfGetParents("dtb_category", "parent_category_id", "category_id", $category_id);

        $objQuery = new SC_Query();
        $index_no = 0;
        foreach($arrCatID as $val){
            // カテゴリー名称を取得
            $sql = "SELECT category_name FROM dtb_category WHERE category_id = ?";
            $arrVal = array($val);
            $CatName = $objQuery->getOne($sql, $arrVal);
            if($val != $category_id){
                $arrTopicPath[$index_no]['name'] = $CatName;
                $arrTopicPath[$index_no]['link'] = "./list.php?category_id=" .$val;
            } else {
                $arrTopicPath[$index_no]['name'] = $CatName;
            }
            $index_no++;
        }
        return $arrTopicPath;
    }

    /**
     * 商品IDをキーにカテゴリ名(商品名)とリンクの連想配列を作成します.
     * 
     * @param type $product_id
     * @return array カテゴリ名(商品名)とリンクの連想配列
     */
    function getArrTopicPathByProductId($product_id) {
        $arrTopicPath = array();
        // 商品のメインカテゴリIDを取得
        $category_id = $this->getMainCategoryIdByProductId($product_id);
        // 商品が属するカテゴリIDを縦に取得
        $objDb = new SC_Helper_DB_Ex();
        $arrCatID = $objDb->sfGetParents("dtb_category", "parent_category_id", "category_id", $category_id);

        $objQuery = new SC_Query();
        $index_no = 0;
        foreach($arrCatID as $val){
            // カテゴリー名称を取得
            $sql = "SELECT category_name FROM dtb_category WHERE category_id = ?";
            $arrVal = array($val);
            $CatName = $objQuery->getOne($sql, $arrVal);

            $arrTopicPath[$index_no]['name'] = $CatName;
            $arrTopicPath[$index_no]['link'] = "./list.php?category_id=" . $val;

            $index_no++;
        }
        $objProduct = new SC_Product_Ex();
        $productDetail = $objProduct->getDetail($product_id);
        $arrTopicPath[$index_no]['name'] = $productDetail['name'];
        
        return $arrTopicPath;
    }

    /**
     * 商品IDからメインカテゴリを取得します.
     * メインカテゴリ：最もカテゴリ階層が深く、表示順が上位のカテゴリ
     *
     * @param integer $product_id プロダクトID
     * @return array メインカテゴリ
     */
    function getMainCategoryIdByProductId($product_id) {
        // プラグイン情報を取得.
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("TopicPath");
        $level = $plugin['free_field1'];
        $level_sort = ($level == 1) ? "DESC": "ASC";
        
        $rank = $plugin['free_field2'];
        $rank_sort = ($rank == 1) ? "DESC": "ASC";
        
        $objQuery = new SC_Query();
        $col = "";
        $col .= " p_cat.category_id,";
        $col .= " cat.category_name,";
        $col .= " cat.parent_category_id,";
        $col .= " cat.level,";
        $col .= " cat.rank,";
        $col .= " cat.creator_id,";
        $col .= " cat.create_date,";
        $col .= " cat.update_date,";
        $col .= " cat.del_flg ";
        $from = " dtb_product_categories as p_cat left join dtb_category as cat on p_cat.category_id = cat.category_id";
        $where = "product_id = ?";
        
        $option = "ORDER BY level " . $level_sort . " ,rank " . $rank_sort;
        $objQuery->setoption($option);
        
        $arrCategory = $objQuery->select($col, $from, $where, array($product_id));
        return $arrCategory[0]['category_id'];
    }
}
?>
