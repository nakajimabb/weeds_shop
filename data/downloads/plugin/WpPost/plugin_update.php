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

        //旧バージョンのCSSが存在した場合、新規分css追加を追加しtemp.cssで保存
        if (file_exists(PLUGIN_HTML_REALDIR . "WpPost/media/wppost.css")){
            $filelist = array(PLUGIN_HTML_REALDIR . "WpPost/media/wppost.css", DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/plg_WpPost_update.css");
            $data = "";
            foreach ($filelist as $file) {
                $filedata = file_get_contents($file);
                $data .= $filedata;
            }
            file_put_contents(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/temp.css",$data);
        }
        //新バージョンのCSSが存在した場合、temp.cssで保存
        if (file_exists(PLUGIN_HTML_REALDIR . "WpPost/media/plg_WpPost_common.css")){
            if(copy(PLUGIN_HTML_REALDIR . "WpPost/media/plg_WpPost_common.css", DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/temp.css") === false) print_r("失敗");
        }

        //PLUGIN_HTML_REALDIR削除
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "WpPost") === false);

        //PLUGIN_UPLOAD_REALDIR
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost") === false);

        //HTML_REALDIR削除
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "wppost") === false);

        //HTML_REALDIRのブロック用ファイルが存在した場合削除
        //旧バージョン
        if (file_exists(HTML_REALDIR . "frontparts/bloc/wppost_list.php")){
            if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "frontparts/bloc/wppost_list.php") === false);
        //新バージョン
        } elseif (file_exists(HTML_REALDIR . "frontparts/bloc/plg_WpPost_list.php")){
            if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "frontparts/bloc/plg_WpPost_list.php") === false);
        }

        //TEMPLATE_REALDIR削除
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "wppost") === false);
        ///TEMPLATE_REALDIRのブロック用ファイルが存在した場合削除
        //旧バージョン
        if (file_exists(TEMPLATE_REALDIR . "frontparts/bloc/wppost_list.tpl")){
            if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "frontparts/bloc/wppost_list.tpl") === false);
        //新バージョン
        } elseif (file_exists(TEMPLATE_REALDIR . "frontparts/bloc/plg_WpPost_list.tpl")){
            if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "frontparts/bloc/plg_WpPost_list.tpl") === false);
        }



        // 必要なファイルをコピー

        //ファイルコピー PLUGIN_UPLOAD_REALDIR
        mkdir(PLUGIN_UPLOAD_REALDIR . "WpPost");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "config.php", PLUGIN_UPLOAD_REALDIR . "WpPost/config.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "logo.png", PLUGIN_UPLOAD_REALDIR . "WpPost/logo.png") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plg_WpPost_Category_LC_Page.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plg_WpPost_Category_LC_Page.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plg_WpPost_LC_Page.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plg_WpPost_LC_Page.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plg_WpPost_LC_Page_Config.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plg_WpPost_LC_Page_Config.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plg_WpPost_List_LC_Page_FrontParts_Bloc.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plg_WpPost_List_LC_Page_FrontParts_Bloc.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plugin_info.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plugin_info.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "plugin_update.php", PLUGIN_UPLOAD_REALDIR . "WpPost/plugin_update.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "WpPost.php", PLUGIN_UPLOAD_REALDIR . "WpPost/WpPost.php") === false) print_r("失敗");
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "wppost", PLUGIN_UPLOAD_REALDIR . "WpPost/wppost") === false) print_r("失敗");

        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "templates", PLUGIN_UPLOAD_REALDIR . "WpPost/templates") === false) print_r("失敗");


        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "bloc", PLUGIN_UPLOAD_REALDIR . "WpPost/bloc") === false) print_r("失敗");

        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media", PLUGIN_UPLOAD_REALDIR . "WpPost/media") === false) print_r("失敗");
        //temp.cssがある場合コピー
        if (file_exists(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/temp.css")){
            if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/temp.css", PLUGIN_UPLOAD_REALDIR . "WpPost/media/plg_WpPost_common.css") === false) print_r("失敗");
        }

        //twitter auth用ライブラリ
        if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "twitteroauth", PLUGIN_UPLOAD_REALDIR . "WpPost/twitteroauth") === false) print_r("失敗");

        //リスト用ブロック
        if(copy(PLUGIN_UPLOAD_REALDIR . "WpPost/templates/plg_WpPost_list.tpl", TEMPLATE_REALDIR . "frontparts/bloc/plg_WpPost_list.tpl") === false) print_r("失敗");
        if(copy(PLUGIN_UPLOAD_REALDIR . "WpPost/bloc/plg_WpPost_list.php", HTML_REALDIR . "frontparts/bloc/plg_WpPost_list.php") === false) print_r("失敗");

        //ポスト用ファイル
        mkdir(TEMPLATE_REALDIR . "wppost");
        if(copy(PLUGIN_UPLOAD_REALDIR . "WpPost/templates/plg_WpPost_post.tpl", TEMPLATE_REALDIR . "wppost/plg_WpPost_post.tpl") === false) print_r("失敗");
        mkdir(HTML_REALDIR . "wppost");
        if(copy(PLUGIN_UPLOAD_REALDIR . "WpPost/wppost/plg_WpPost_post.php", HTML_REALDIR . "wppost/plg_WpPost_post.php") === false) print_r("失敗");

        //カテゴリ用ファイル
        if(copy(PLUGIN_UPLOAD_REALDIR . "WpPost/templates/plg_WpPost_category.tpl", TEMPLATE_REALDIR . "wppost/plg_WpPost_category.tpl") === false) print_r("失敗");
        if(copy(PLUGIN_UPLOAD_REALDIR . "WpPost/wppost/plg_WpPost_category.php", HTML_REALDIR . "wppost/plg_WpPost_category.php") === false) print_r("失敗");

        //PLUGIN_HTML_REALDIR
        //設定用ファイル
        mkdir(PLUGIN_HTML_REALDIR . "WpPost/config.php");
        if(copy(PLUGIN_UPLOAD_REALDIR . "WpPost/config.php", PLUGIN_HTML_REALDIR . "WpPost/config.php") === false) print_r("失敗");
        //ロゴ
        if(copy(PLUGIN_UPLOAD_REALDIR . "WpPost/logo.png", PLUGIN_HTML_REALDIR . "WpPost/logo.png") === false) print_r("失敗");
        //JS
        mkdir(PLUGIN_HTML_REALDIR . "WpPost/media");
        if(SC_Utils_Ex::sfCopyDir(PLUGIN_UPLOAD_REALDIR . "WpPost/media/", PLUGIN_HTML_REALDIR . "WpPost/media/") === false) print_r("失敗");
        //temp.cssがある場合コピー
        if (file_exists(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/temp.css")){
            if(copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "media/temp.css", PLUGIN_HTML_REALDIR . "WpPost/media/plg_WpPost_common.css") === false) print_r("失敗");
        }

    } //function update

}
?>