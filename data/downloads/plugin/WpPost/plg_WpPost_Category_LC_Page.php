<?php
/*
 * WPPost
 * Copyright (C) 2012 GIZMO CO.,LTD. All Rights Reserved.
 * http://www.gizmo.co.jp/
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
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
$plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("WpPost");
$wp_install_dir = $plugin['free_field1'];
require_once($_SERVER['DOCUMENT_ROOT'].$wp_install_dir.'/wp-load.php' );
/**
 * WordPressPost取得のクラス
 *
 * @package WpPost
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class LC_Page_WpPost_Category extends LC_Page_Ex {

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
        if ($_GET["catid"]) {
            $catid = $_GET["catid"];
        }

        //第2階層取得
        $child_cats =  explode('/',get_category_children($catid));
        array_shift($child_cats); //配列の最初の1つ取り出し削除
        $catid_parent = $catid.","."-".implode(",-", $child_cats);

        query_posts("cat=$catid_parent&order=DESC&orderby=date");

        $wp_postcats = array();
        $idx=0;
        $arr_catID = get_category($catid);
        if ($arr_catID->parent){
            $this->wp_parent_catID = $arr_catID->parent;
            $this->wp_parent_catName = get_category($this->wp_parent_catID)->cat_name;
        }
        $this->wp_catname = $arr_catID->cat_name;

        while (have_posts()) : the_post();
            //$wp_postcats[$idx]["categoryName"]=$arr_catID[0]->cat_name;
            $wp_postcats[$idx]["postid"]=get_the_ID();
            $wp_postcats[$idx]["date"]=get_the_date();
            $wp_postcats[$idx]["title"] = the_title('','',false);
            $wp_postcats[$idx]["summary"] = mb_substr(get_the_excerpt(), 0, 30);
            $idx++;  
        endwhile;

        if ($child_cats) {

            $idy=0;
            $wp_sec_postcats = array();
            foreach($child_cats as $value){
                query_posts("cat=$value&order=DESC&orderby=date");
                $idx=0;
                while (have_posts()) : the_post();
                    $arr_catID = get_the_category();
                    $wp_sec_postcats[$idy][$idx]["categoryID"]=$arr_catID[0]->cat_ID;
                    $wp_sec_postcats[$idy][$idx]["categoryName"]=$arr_catID[0]->cat_name;
                    $wp_sec_postcats[$idy][$idx]["postid"]=get_the_ID();
                    $wp_sec_postcats[$idy][$idx]["date"]=get_the_date();
                    $wp_sec_postcats[$idy][$idx]["title"] = the_title('','',false);
                    $wp_sec_postcats[$idy][$idx]["summary"] = mb_substr(get_the_excerpt(), 0, 30);
                    //$wp_sec_postcats[$idx]["content"]=get_the_content();
                    $idx++;  
                endwhile;
                $idy++;
            }
        }

        $this->wp_postcats = $wp_postcats;
        $this->wp_sec_postcats = $wp_sec_postcats;
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /*
    $objPage = new LC_Page_User();
    register_shutdown_function(array($objPage, 'destroy'));
    $objPage->init();
    $objPage->process();
    */

    function get_all_category_all() {
        if ( ! $cat_all = wp_cache_get( 'all_category_all', 'category' ) ) {
	        $cat_all = get_terms( 'category', 'fields=all&get=all&orderby=id' );
	        wp_cache_add( 'all_category_all', $cat_all, 'category' );
        }
        return $cat_all;
    }
}
?>
