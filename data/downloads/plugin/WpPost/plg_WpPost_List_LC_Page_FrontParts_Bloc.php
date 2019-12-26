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
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';
$plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("WpPost");
$wp_install_dir = $plugin['free_field1'];
require_once($_SERVER['DOCUMENT_ROOT'].$wp_install_dir.'/wp-load.php' );

/**
 * WordPressPost取得のブロッククラス
 *
 * @package WpPost
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class LC_Page_FrontParts_Bloc_WpPost_List extends LC_Page_FrontParts_Bloc {

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

        //表示条件取得
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("WpPost");
        $wp_install_dir = $plugin['free_field1'];
        $postnum = $plugin['free_field2'];
        $format = $plugin['free_field3'];
        $category = $plugin['free_field4'];

        //Post・Page取得
        if ($format == 1) {
            query_posts("post_type=post&post_status=publish&cat=$category&showposts=$postnum");
        }
        if ($format == 2) {
            query_posts("post_type=page&post_status=publish&showposts=$postnum");
        }
        if ($format == 3) {
            query_posts("post_type=any&post_status=publish&cat=$category&showposts=$postnum");
        }
        $wp_postlist = array();
        $idx=0;

        while (have_posts()) : the_post();
            $wp_postlist[$idx]["ID"]=get_the_ID();
            $wp_postlist[$idx]["date"]=get_the_date();
            $wp_postlist[$idx]["title"]=the_title('','',false);
            $wp_postlist[$idx]["content"]=get_the_content();
            $wp_postlist[$idx]["meta"]=get_post_custom();
            $wp_postlist[$idx]["guid"]=get_the_guid();
            $idx++;
        endwhile;

        $this->wp_postlist = $wp_postlist;
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

}
?>
