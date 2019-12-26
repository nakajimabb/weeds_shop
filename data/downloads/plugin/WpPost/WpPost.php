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
 * プラグインのメインクラス
 *
 * @package WpPost
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class WpPost extends SC_Plugin_Base {

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
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        // dtb_blocにリスト用ブロックを追加する.
        $sqlval_bloc_list = array();
        $sqlval_bloc_list['device_type_id'] = DEVICE_TYPE_PC;
        $sqlval_bloc_list['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = " . DEVICE_TYPE_PC) + 1;
        $sqlval_bloc_list['bloc_name'] = "WordPressポストリスト";
        $sqlval_bloc_list['tpl_path'] = "plg_WpPost_list.tpl";
        $sqlval_bloc_list['filename'] = "plg_WpPost_list";
        $sqlval_bloc_list['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc_list['update_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc_list['php_path'] = "frontparts/bloc/plg_WpPost_list.php";
        $sqlval_bloc_list['deletable_flg'] = 0;
        $sqlval_bloc_list['plugin_id'] = $arrPlugin['plugin_id'];
        // INSERTの実行
        $objQuery->insert("dtb_bloc", $sqlval_bloc_list);

        // dtb_blocにリスト用ブロックを追加する.
        $sqlval_bloc_list = array();
        $sqlval_bloc_list['device_type_id'] = DEVICE_TYPE_SMARTPHONE;
        $sqlval_bloc_list['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = " . DEVICE_TYPE_SMARTPHONE) + 1;
        $sqlval_bloc_list['bloc_name'] = "WordPressポストリスト";
        $sqlval_bloc_list['tpl_path'] = "plg_WpPost_list.tpl";
        $sqlval_bloc_list['filename'] = "plg_WpPost_list";
        $sqlval_bloc_list['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc_list['update_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc_list['php_path'] = "frontparts/bloc/plg_WpPost_list.php";
        $sqlval_bloc_list['deletable_flg'] = 0;
        $sqlval_bloc_list['plugin_id'] = $arrPlugin['plugin_id'];
        // INSERTの実行
        $objQuery->insert("dtb_bloc", $sqlval_bloc_list);

        // dtb_pagelayoutにポスト用ページを追加する.
        $sqlval_post = array();
        $sqlval_post['device_type_id'] = DEVICE_TYPE_PC;
        $sqlval_post['page_id'] = $objQuery->max('page_id', "dtb_pagelayout", "device_type_id = " . DEVICE_TYPE_PC) + 1;
        $sqlval_post['page_name'] = "WordPressポスト表示";
        $sqlval_post['url'] = "wppost/plg_WpPost_post.php";
        $sqlval_post['filename'] = "wppost/plg_WpPost_post";
        $sqlval_post['header_chk'] = "1";
        $sqlval_post['footer_chk'] = "1";
        $sqlval_post['edit_flg'] = "2";
        $sqlval_post['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_post['update_date'] = "CURRENT_TIMESTAMP";
        // INSERTの実行
        $objQuery->insert("dtb_pagelayout", $sqlval_post);

        // dtb_pagelayoutにカテゴリ用ページを追加する.
        $sqlval_category = array();
        $sqlval_category['device_type_id'] = DEVICE_TYPE_PC;
        $sqlval_category['page_id'] = $objQuery->max('page_id', "dtb_pagelayout", "device_type_id = " . DEVICE_TYPE_PC) + 1;
        $sqlval_category['page_name'] = "WordPressカテゴリ表示";
        $sqlval_category['url'] = "wppost/plg_WpPost_category.php";
        $sqlval_category['filename'] = "wppost/plg_WpPost_category";
        $sqlval_category['header_chk'] = "1";
        $sqlval_category['footer_chk'] = "1";
        $sqlval_category['edit_flg'] = "2";
        $sqlval_category['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_category['update_date'] = "CURRENT_TIMESTAMP";
        // INSERTの実行
        $objQuery->insert("dtb_pagelayout", $sqlval_category);

        // プラグイン独自の設定データを追加
        $sqlval = array();
        $sqlval['free_field1'] = "";
        $sqlval['free_field2'] = "";
        $sqlval['free_field3'] = "";
        $sqlval['free_field4'] = "";
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = 'WpPost'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);

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

        $objQuery->commit();

        // 必要なファイルをコピーします.
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
        if(copy(PLUGIN_UPLOAD_REALDIR . "WpPost/config.php", PLUGIN_HTML_REALDIR . "WpPost/config.php") === false) print_r("失敗");
        if(copy(PLUGIN_UPLOAD_REALDIR . "WpPost/logo.png", PLUGIN_HTML_REALDIR . "WpPost/logo.png") === false) print_r("失敗");
        mkdir(PLUGIN_HTML_REALDIR . "WpPost/media");
        if(SC_Utils_Ex::sfCopyDir(PLUGIN_UPLOAD_REALDIR . "WpPost/media/", PLUGIN_HTML_REALDIR . "WpPost/media/") === false) print_r("失敗");
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
        $objQuery = SC_Query_Ex::getSingletonInstance();

        //dtb_bloc、dtb_blocpositionリスト用ブロックの削除
        $arrBlocIdList = $objQuery->getCol('bloc_id', "dtb_bloc", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_PC , "plg_WpPost_list"));
        $bloc_id_list = (int) $arrBlocIdList[0];
        $where = "bloc_id = ?";
        $objQuery->delete("dtb_bloc", $where, array($bloc_id_list));
        $objQuery->delete("dtb_blocposition", $where, array($bloc_id_list));

        //plg_WpPost_commentコメント設定用のテーブル削除
        $sql_drop = "DROP TABLE plg_WpPost_comment;";
        $objQuery->query($sql_drop);

        //dtb_pagelayoutポスト用ページの削除
        $arrPageIdPost = $objQuery->getCol('page_id', "dtb_pagelayout", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_PC , "wppost/plg_WpPost_post"));
        $page_id_post = (int) $arrPageIdPost[0];
        $where = "page_id = ?";
        $objQuery->delete("dtb_pagelayout", $where, array($page_id_post));

        //dtb_pagelayoutカテゴリ用ページの削除
        $arrPageIdCat = $objQuery->getCol('page_id', "dtb_pagelayout", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_PC , "wppost/plg_WpPost_category"));
        $page_id_cat = (int) $arrPageIdCat[0];
        $where = "page_id = ?";
        $objQuery->delete("dtb_pagelayout", $where, array($page_id_cat));

        // PLUGIN_HTML_REALDIRディレクトリ削除.
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "WpPost") === false); // TODO エラー処理
        //HTML_REALDIRディレクトリ削除
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "wppost") === false); // TODO エラー処理
        //TEMPLATE_REALDIRディレクトリ削除
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "wppost") === false); // TODO エラー処理
        //PLUGIN_UPLOAD_REALDIRディレクトリ削除
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_UPLOAD_REALDIR . "WpPost") === false);
        //ブロック用ファイル用
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "frontparts/bloc/plg_WpPost_list.tpl") === false); // TODO エラー処理
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR  . "frontparts/bloc/plg_WpPost_list.php") === false); // TODO エラー処理

    }
    
    /**
     * アップデート
     * updateはアップデート時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     * 
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function update($arrPlugin) {
        // nop
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

    /**
     * 処理の介入箇所とコールバック関数を設定
     * registerはプラグインインスタンス生成時に実行されます
     * 
     * @param SC_Helper_Plugin $objHelperPlugin 
     */
    function register(SC_Helper_Plugin $objHelperPlugin) {
        // ヘッダへの追加
		$template_dir = PLUGIN_UPLOAD_REALDIR . 'WpPost/templates/';
        $objHelperPlugin->setHeadNavi($template_dir . 'plg_WpPost_header.tpl');
    }
}
?>
