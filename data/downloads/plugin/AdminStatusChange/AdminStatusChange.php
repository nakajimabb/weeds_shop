<?php
/*
 * RankingSales
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

/**
 * プラグインのメインクラス
 *
 * @package RankingSales
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class AdminStatusChange extends SC_Plugin_Base {

    /**
     * コンストラクタ
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }
    
    /**
     * インストール
     * installはプラグインのインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin plugin_infoを元にDBに登録されたプラグイン情報(dtb_plugin)
     * @return void
     */
    function install($arrPlugin) {
        copy(PLUGIN_UPLOAD_REALDIR . "AdminStatusChange/logo.png", PLUGIN_HTML_REALDIR . "AdminStatusChange/logo.png");
    }
    
    /**
     * アンインストール
     * uninstallはアンインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     * 
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
	 
    function uninstall($arrPlugin) {
    }
    
    /**
     * 稼働
     * enableはプラグインを有効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function enable($arrPlugin) {
        // nop
    }

    /**
     * 停止
     * disableはプラグインを無効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function disable($arrPlugin) {
        // nop
    }
    function register(SC_Helper_Plugin $objHelperPlugin) {
		$objHelperPlugin->addAction('LC_Page_Admin_Products_action_before', array(&$this, 'updatestatus'));
		$objHelperPlugin->addAction('LC_Page_Admin_Products_action_after' , array(&$this, 'changestatus'));

    }
	
	
	function updatestatus($objPage){
		$objDb = new SC_Helper_DB_Ex();
		$objFormParam = new SC_FormParam_Ex();
		$objProduct = new SC_Product_Ex();
		$objQuery =& SC_Query_Ex::getSingletonInstance();
        // パラメーター情報の初期化
		$objPage->lfInitParam($objFormParam);
		$objFormParam->setParam($_POST);
		$objPage->arrHidden = $objFormParam->getSearchArray();
		$objPage->arrForm = $objFormParam->getFormParamList();

        switch ($objPage->getMode()) {
            case 'statuschange':
                $objFormParam->convParam();
                $objFormParam->trimParam();
                $objPage->arrErr = $objPage->lfCheckError($objFormParam);
                $arrParam = $objFormParam->getHashArray();
				
                if (count($objPage->arrErr) == 0) {
                    $where = 'del_flg = 0';
                    $arrWhereVal = array();
                    foreach ($arrParam as $key => $val) {
                        if ($val == '') {
                            continue;
                        }
                        $objPage->buildQuery($key, $where, $arrWhereVal, $objFormParam, $objDb);
                    }

                    $order = 'update_date DESC';

                    /* -----------------------------------------------
                     * 処理を実行
                     * ----------------------------------------------- */
                    switch ($objPage->getMode()) {
                        // CSVを送信する。
						
                        case 'statuschange':
							$objQuery = SC_Query_Ex::getSingletonInstance();
                        	$upquery = "UPDATE  dtb_products SET status = ? WHERE product_id = ? LIMIT 1";
							foreach($_POST["statusdata"] as $product_id => $status){
								if(preg_match('/^[0-9]+$/',$status)){
									$objQuery->query($upquery,array($status,$product_id));
								}
							}
                            // 行数の取得
                            $objPage->tpl_linemax = $objPage->getNumberOfLines($where, $arrWhereVal);
                            // ページ送りの処理
                            $page_max = SC_Utils_Ex::sfGetSearchPageMax($objFormParam->getValue('search_page_max'));
                            // ページ送りの取得
                            $objNavi = new SC_PageNavi_Ex($objPage->arrHidden['search_pageno'],
                                                          $objPage->tpl_linemax, $page_max,
                                                          'fnNaviSearchPage', NAVI_PMAX);
                            $objPage->arrPagenavi = $objNavi->arrPagenavi;

                            // 検索結果の取得
                            $objPage->arrProducts = $objPage->findProducts($where, $arrWhereVal, $page_max, $objNavi->start_row,
                                                                     $order, $objProduct);
                            // 各商品ごとのカテゴリIDを取得
                            if (count($objPage->arrProducts) > 0) {
                                foreach ($objPage->arrProducts as $key => $val) {
                                    $objPage->arrProducts[$key]['categories'] = $objDb->sfGetCategoryId($val['product_id'], 0, true);
                                    $objDb->g_category_on = false;
                                }
                            }
							$_GET['mode'] = "search";
                    }
                }
                break;
        }
	}
	
	function changestatus($objPage){
		$objPage->tpl_mainpage =  PLUGIN_UPLOAD_REALDIR . "AdminStatusChange/templates/index.tpl";
        $objQuery = new SC_Query_Ex();;
		$product_ids = array();
		foreach($objPage->arrProducts as $key => $value){
			$product_ids[$key] = $value["product_id"];
		}
		if(count($product_ids) > 0){
			$query = "
			SELECT 
				c1.name as c1name,
				c2.name as c2name,
				product_id, 
				product_class_id,  
				stock_unlimited ,  
				stock 
			FROM  
				dtb_products_class pc LEFT JOIN
				dtb_classcategory c1 ON(c1.classcategory_id = pc.classcategory_id1) LEFT JOIN
				dtb_classcategory c2 ON(c2.classcategory_id = pc.classcategory_id2)
			WHERE 
				product_id IN ( ".implode(",",$product_ids)." ) AND 
				pc.del_flg = 0
			";
			
	
			$data = $objQuery->getAll($query,array());
			$tmpdata = array();
			$objPage->submitflag = false;
			
			foreach($data as $key => $value){
				if($value["stock_unlimited"] == 0){
					$objPage->submitflag = true;
				}
				if(empty($value["c1name"])){
					$value["setname"] = "";
				}else{
					$value["setname"] = $value["c1name"];
				}
				if(!empty($value["c1name"])){
					$value["setname"] .= "/".$value["c2name"];
				}
				$tmpdata[$value["product_id"]][] = $value;
			}
			foreach($product_ids as $key => $product_id){
				$objPage->arrProducts[$key]["stock_class"] = $tmpdata[$product_id];
			}
		}
	}
}
?>
