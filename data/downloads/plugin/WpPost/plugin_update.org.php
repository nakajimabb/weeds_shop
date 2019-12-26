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
 
/**
 * プラグイン のアップデート用クラス.
 *
 * @package KuronekoB2
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
 
class plugin_update{
	/**
	 *アップデート
	 *updateはアップデート時に実行されます。
	 *引数にはdtb_pluginのプラグイン情報が渡されます。
	 *
	 *@param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
	 *@return void
	 */
	function update($arrPlugin){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        // dtb_bloc変更
        $sqlval_plugin = array();
        $sqlval_plugin['plugin_version'] = "0.30";
        $where = "plugin_code = 'WpPost'";
        // UPDATEの実行
        $objQuery->update("dtb_plugin", $sqlval_plugin, $where);

        // dtb_bloc変更
        $sqlval_bloc_list = array();
        $sqlval_bloc_list['tpl_path'] = "plg_WpPost_list.tpl";
        $sqlval_bloc_list['filename'] = "plg_WpPost_list";
        $sqlval_bloc_list['update_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc_list['php_path'] = "frontparts/bloc/plg_WpPost_list.php";
        $where = "bloc_name = 'WordPressポストリスト'";
        // UPDATEの実行
        $objQuery->update("dtb_bloc", $sqlval_bloc_list, $where);

        // dtb_pagelayout ポスト用ページ変更
        $sqlval_post = array();
        $sqlval_post['url'] = "wppost/plg_WpPost_post.php";
        $sqlval_post['filename'] = "wppost/plg_WpPost_post";
        $sqlval_post['update_date'] = "CURRENT_TIMESTAMP";
        $where = "page_name = 'WordPressポスト表示'";
        // UPDATEの実行
        $objQuery->update("dtb_pagelayout", $sqlval_post, $where);

        // dtb_pagelayout カテゴリ用ページ変更
        $sqlval_category = array();
        $sqlval_category['url'] = "wppost/plg_WpPost_category.php";
        $sqlval_category['filename'] = "wppost/plg_WpPost_category";
        $sqlval_category['update_date'] = "CURRENT_TIMESTAMP";
        $where = "page_name = 'WordPressカテゴリ表示'";
        // UPDATEの実行
        $objQuery->update("dtb_pagelayout", $sqlval_category, $where);

        //plg_WpPost_comment
        if (!$objQuery->exists('plg_WpPost_comment')) {

            //コメント設定用のテーブル追加
            //show_comment コメントの受付と表示 しない:0 する:1
            //comment_turn 表示順 0:新着順 1:古いものから
            //comment_login 投稿にはログイン必要 不要:0 必要:1
            //comment_format 表示を入れ子にするか必要 しない:0 する:1
            //comment_num 表示するコメント数 全て:0
            $sql = "CREATE TABLE plg_WpPost_comment (
                         ID INTEGER NOT NULL,
                         show_comment smallint,
                         comment_turn smallint,
                         comment_login smallint,
                         comment_login_ec smallint,
                         comment_login_fb smallint,
                         comment_login_tw smallint,
                         fb_appid text,
                         fb_secret text,
                         tw_consumer_key text,
                         tw_consumer_secret text,
                         comment_format smallint,
                         comment_num smallint
                    );";
            $objQuery->query($sql);

            //コメント設定用のテーブルにデフォルトデータを入れる
            $sqlval_wppost_comment = array();
            $sqlval_wppost_comment['id'] = 1;
            $sqlval_wppost_comment['show_comment'] = 0;
            $sqlval_wppost_comment['comment_turn'] = 0;
            $sqlval_wppost_comment['comment_login'] = 0;
            $sqlval_wppost_comment['comment_login_ec'] = 1;
            $sqlval_wppost_comment['comment_login_fb'] = "";
            $sqlval_wppost_comment['comment_login_tw'] = "";
            $sqlval_wppost_comment['fb_appid'] = "";
            $sqlval_wppost_comment['fb_secret'] = "";
            $sqlval_wppost_comment['tw_consumer_key'] = "";
            $sqlval_wppost_comment['tw_consumer_secret'] = "";
            $sqlval_wppost_comment['comment_format'] = 0;
            $sqlval_wppost_comment['comment_num'] = 0;

            // INSERTの実行
            $objQuery->insert("plg_WpPost_comment", $sqlval_wppost_comment);
        }
        
        $objQuery->commit();

        //旧バージョンのCSSが存在した場合、新規分css追加を追加しtemp.cssで作成
        $filelist = array(PLUGIN_HTML_REALDIR . "WpPost/media/wppost.css", DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_update.css");
        $data = "";
        foreach ($filelist as $file) {
            $filedata = file_get_contents($file);
            $data .= $filedata;
        }
        file_put_contents(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/temp.css",$data);
        //新規CSSコピー
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/temp.css", PLUGIN_HTML_REALDIR . "WpPost/media/plg_WpPost_common.css") === false) print_r("失敗");

        //旧ファイル削除 PLUGIN_HTML_REALDIR
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "WpPost/media/wppost.css") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "WpPost/media/wppost.jpg") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "WpPost/media/wppost.js") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "WpPost/config.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "WpPost/logo.png") === false);

        //旧ファイル削除 PLUGIN_UPLOAD_REALDIR
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/config.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/logo.png") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/LC_Page_FrontParts_Bloc_WpPost_List.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/LC_Page_Plugin_WpPost_Config.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/LC_Page_WpPost.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/LC_Page_WpPost_Category.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/plugin_info.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/WpPost.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/wppost/category.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/wppost/post.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/templates/category.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/templates/config.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/templates/header.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/templates/post.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/templates/wppost_list.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/media/topicpath.gif") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/media/wppost.css") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/media/wppost.jpg") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/media/wppost.js") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost/bloc/wppost_list.php") === false);

        //旧ファイル削除 HTML_REALDIR
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "wppost/category.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "wppost/post.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "frontparts/bloc/wppost_list.php") === false);

        //旧ファイル削除 TEMPLATE_REALDIR
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "wppost/category.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "wppost/post.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "frontparts/bloc/wppost_list.tpl") === false);

        //ファイルコピー PLUGIN_HTML_REALDIR
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_comment.js", PLUGIN_HTML_REALDIR . "WpPost/media/plg_WpPost_comment.js") === false) print_r("失敗");
        //if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_common.css", PLUGIN_HTML_REALDIR . "WpPost/media/plg_WpPost_common.css") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_common.js", PLUGIN_HTML_REALDIR . "WpPost/media/plg_WpPost_common.js") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_head.jpg", PLUGIN_HTML_REALDIR . "WpPost/media/plg_WpPost_head.jpg") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_update.css", PLUGIN_HTML_REALDIR . "WpPost/media/plg_WpPost_update.css") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "config.php", PLUGIN_HTML_REALDIR . "WpPost/config.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "logo.png", PLUGIN_HTML_REALDIR . "WpPost/logo.png") === false) print_r("失敗");

        //ファイルコピー PLUGIN_UPLOAD_REALDIR
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "config.php", PLUGIN_UPLOAD_REALDIR . "WpPost/config.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "logo.png", PLUGIN_UPLOAD_REALDIR . "WpPost/logo.png") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plg_WpPost_Category_LC_Page.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plg_WpPost_Category_LC_Page.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plg_WpPost_LC_Page.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plg_WpPost_LC_Page.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plg_WpPost_LC_Page_Config.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plg_WpPost_LC_Page_Config.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plg_WpPost_List_LC_Page_FrontParts_Bloc.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plg_WpPost_List_LC_Page_FrontParts_Bloc.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plugin_info.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plugin_info.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plugin_update.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plugin_update.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "WpPost.php", PLUGIN_UPLOAD_REALDIR . "WpPost/WpPost.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "wppost/plg_WpPost_category.php", PLUGIN_UPLOAD_REALDIR . "WpPost/wppost/plg_WpPost_category.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "wppost/plg_WpPost_post.php", PLUGIN_UPLOAD_REALDIR . "WpPost/wppost/plg_WpPost_post.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "templates/plg_WpPost_category.tpl", PLUGIN_UPLOAD_REALDIR . "WpPost/templates/plg_WpPost_category.tpl") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "templates/plg_WpPost_config.tpl", PLUGIN_UPLOAD_REALDIR . "WpPost/templates/plg_WpPost_config.tpl") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "templates/plg_WpPost_header.tpl", PLUGIN_UPLOAD_REALDIR . "WpPost/templates/plg_WpPost_header.tpl") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "templates/plg_WpPost_list.tpl", PLUGIN_UPLOAD_REALDIR . "WpPost/templates/plg_WpPost_list.tpl") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "templates/plg_WpPost_post.tpl", PLUGIN_UPLOAD_REALDIR . "WpPost/templates/plg_WpPost_post.tpl") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_comment.js", PLUGIN_UPLOAD_REALDIR . "WpPost/media/plg_WpPost_comment.js") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_common.css", PLUGIN_UPLOAD_REALDIR . "WpPost/media/plg_WpPost_common.css") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_common.js", PLUGIN_UPLOAD_REALDIR . "WpPost/media/plg_WpPost_common.js") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_head.jpg", PLUGIN_UPLOAD_REALDIR . "WpPost/media/plg_WpPost_head.jpg") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_update.css", PLUGIN_UPLOAD_REALDIR . "WpPost/media/plg_WpPost_update.css") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/topicpath.gif", PLUGIN_UPLOAD_REALDIR . "WpPost/media/topicpath.gif") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "bloc/plg_WpPost_list.php", PLUGIN_UPLOAD_REALDIR . "WpPost/bloc/plg_WpPost_list.php") === false) print_r("失敗");

        mkdir(PLUGIN_UPLOAD_REALDIR . "WpPost/twitteroauth");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "twitteroauth/OAuth.php", PLUGIN_UPLOAD_REALDIR . "WpPost/twitteroauth/OAuth.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "twitteroauth/twitteroauth.php", PLUGIN_UPLOAD_REALDIR . "WpPost/twitteroauth/twitteroauth.php") === false) print_r("失敗");

        
        //ファイルコピー HTML_REALDIR
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "wppost/plg_WpPost_category.php", HTML_REALDIR . "wppost/plg_WpPost_category.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "wppost/plg_WpPost_post.php", HTML_REALDIR . "wppost/plg_WpPost_post.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "bloc/plg_WpPost_list.php", HTML_REALDIR . "frontparts/bloc/plg_WpPost_list.php") === false) print_r("失敗");

        //ファイルコピー TEMPLATE_REALDIR
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "templates/plg_WpPost_category.tpl", TEMPLATE_REALDIR . "wppost/plg_WpPost_category.tpl") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "templates/plg_WpPost_post.tpl", TEMPLATE_REALDIR . "wppost/plg_WpPost_post.tpl") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "templates/plg_WpPost_list.tpl", TEMPLATE_REALDIR . "frontparts/bloc/plg_WpPost_list.tpl") === false) print_r("失敗");
    }

}
?>