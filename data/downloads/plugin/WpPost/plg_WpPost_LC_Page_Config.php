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
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * WordPressPost取得のブロッククラス
 *
 * @package WpPost
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class LC_Page_Plugin_WpPost_Config extends LC_Page_Admin_Ex {
    
    var $arrForm = array();

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR ."WpPost/templates/plg_WpPost_config.tpl";
        $this->tpl_subtitle = "ワードプレスポスト表示";
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
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
    
        $css_file_path = PLUGIN_HTML_REALDIR . "WpPost/media/plg_WpPost_common.css";
        $arrForm = array();

        switch ($this->getMode()) {
        case 'edit':
            $arrForm = $objFormParam->getHashArray();
            $this->arrErr = $objFormParam->checkError();
            // エラーなしの場合にはデータを更新
            if (count($this->arrErr) == 0) {
                // データ更新
                $this->arrErr = $this->updateData($arrForm, $css_file_path);
                if (count($this->arrErr) == 0) {
                    if ($arrForm["comment_login_ec"] !=1 && $arrForm["comment_login_fb"] !=1 && $arrForm["comment_login_tw"] !=1){
                        $this->tpl_onload = "alert('ログイン方法が設定されていません');";
                    } elseif ($arrForm["comment_login_fb"] ==1 && ($arrForm["fb_appid"] == "" || $arrForm["fb_secret"] == "")){
                        $this->tpl_onload = "alert('ログイン方法にFacebook認証を選択した場合、App IDとApp Secretを設定してください。');";
                    } elseif ($arrForm["comment_login_tw"] ==1 && ($arrForm["tw_consumer_key"] == "" || $arrForm["tw_consumer_secret"] == "")){
                        $this->tpl_onload = "alert('ログイン方法にTwitter認証を選択した場合、Consumer keyとConsumer secretを設定してください。');";
                    } else {
                        $this->tpl_onload = "alert('登録が完了しました。');";
                    }
                }
            }
            break;
        default:
            // プラグイン情報を取得.
            $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("WpPost");
            // WordPressインストールディレクトリ
            $arrForm['wp_install_dir'] = $plugin['free_field1'];
            // CSSファイル.
            $arrForm['css_data'] = $this->getTplMainpage($css_file_path);

            // ポスト
            // 表示件数
            $arrForm['postnum'] = $plugin['free_field2'];
            // 表示形式
            $arrForm['format'] = $plugin['free_field3'];
            // Category ID
            $arrForm['category'] = $plugin['free_field4'];

            //コメント
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $wppost_comment = $objQuery->select("*",plg_WpPost_comment);
            // show_comment
            $arrForm['show_comment'] = $wppost_comment[0]['show_comment'];
            // comment_turn
            $arrForm['comment_turn'] = $wppost_comment[0]['comment_turn'];
            // comment_login
            $arrForm['comment_login'] = $wppost_comment[0]['comment_login'];
            // comment_login_ec
            $arrForm['comment_login_ec'] = $wppost_comment[0]['comment_login_ec'];
            // comment_login_fb
            $arrForm['comment_login_fb'] = $wppost_comment[0]['comment_login_fb'];
            // comment_login_tw
            $arrForm['comment_login_tw'] = $wppost_comment[0]['comment_login_tw'];
            // fb_appid
            $arrForm['fb_appid'] = $wppost_comment[0]['fb_appid'];
            // fb_secret
            $arrForm['fb_secret'] = $wppost_comment[0]['fb_secret'];
            // tw_consumer_key
            $arrForm['tw_consumer_key'] = $wppost_comment[0]['tw_consumer_key'];
            // tw_consumer_secret
            $arrForm['tw_consumer_secret'] = $wppost_comment[0]['tw_consumer_secret'];
            // comment_format
            $arrForm['comment_format'] = $wppost_comment[0]['comment_format'];
            // comment_num
            $arrForm['comment_num'] = $wppost_comment[0]['comment_num'];
            break;
        }
        $this->arrForm = $arrForm;
        $this->setTemplate($this->tpl_mainpage);
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
     * パラメーター情報の初期化
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        // ポスト
        $objFormParam->addParam('WordPressインストール位置', 'wp_install_dir', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('CSS', 'css_data', LLTEXT_LEN, '', array('EXIST_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('表示件数', 'postnum', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('表示形式', 'format', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('category', 'category', array('EXIST_CHECK','NUM_CHECK'));
        //コメント
        $objFormParam->addParam('コメント表示', 'show_comment', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('コメント表示順', 'comment_turn', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('コメントログイン', 'comment_login', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('ログイン方法会員ログイン', 'comment_login_ec', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('ログイン方法Facebook認証', 'comment_login_fb', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('ログイン方法Twitter認証', 'comment_login_tw', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('Facebook App ID', 'fb_appid', MTEXT_LEN, na, array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('Facebook App Secret', 'fb_secret', MTEXT_LEN, na, array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('Twitter Consumer key', 'tw_consumer_key', MTEXT_LEN, na, array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('Twitter Consumer Secret', 'tw_consumer_secret', MTEXT_LEN, na, array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('コメント表示形式', 'comment_format', array('EXIST_CHECK','NUM_CHECK'));
        $objFormParam->addParam('コメント表示数', 'comment_num', array('EXIST_CHECK','NUM_CHECK'));
    }

    /**
     * ファイルパラメーター初期化.
     *
     * @param SC_UploadFile_Ex $objUpFile SC_UploadFileのインスタンス.
     * @param string $key 登録するキー.
     * @return void
     */
    function initUploadFile(&$objUpFile, $key) {
        $objUpFile->addFile('WordPress Post', $key, explode(',', "jpg"), FILE_SIZE, true, 0, 0, false);
    }

    /**
     * ページデータを取得する.
     *
     * @param integer $device_type_id 端末種別ID
     * @param integer $page_id ページID
     * @param SC_Helper_PageLayout $objLayout SC_Helper_PageLayout インスタンス
     * @return array ページデータの配列
     */
    function getTplMainpage($file_path) {

        if (file_exists($file_path)) {
            $arrfileData = file_get_contents($file_path);
        }
        return $arrfileData;
    }
    
    /**
     *
     * @param type $arrData
     * @return type 
     */
    function updateData($arrData, $css_file_path) {
        $arrErr = array();

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // dtb_plugin WpPostのUPDATEする値を作成する。
        $sqlval = array();
        $sqlval['free_field1'] = $arrData['wp_install_dir'];
        $sqlval['free_field2'] = $arrData['postnum'];
        $sqlval['free_field3'] = $arrData['format'];
        $sqlval['free_field4'] = $arrData['category'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        //$where = "plugin_code = 'WpPost'";
        $where = 'plugin_code = ?';

        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where, array('WpPost'));
        //ファイル更新
        if (!SC_Helper_FileManager_Ex::sfWriteFile($css_file_path, $arrData['css_data'])) {
            $arrErr['plugin_code'] = '※ CSSファイルの書き込みに失敗しました<br />';
            $objQuery->rollback();
            return $arrErr;
        }

        // コメント設定用のテーブルのUPDATE値を作成する。
        $sqlval_wppost_comment = array();
        $sqlval_wppost_comment['show_comment'] = $arrData['show_comment'];
        $sqlval_wppost_comment['comment_turn'] = $arrData['comment_turn'];
        $sqlval_wppost_comment['comment_login'] = $arrData['comment_login'];
        $sqlval_wppost_comment['comment_login_ec'] = $arrData['comment_login_ec'];
        $sqlval_wppost_comment['comment_login_fb'] = $arrData['comment_login_fb'];
        $sqlval_wppost_comment['comment_login_tw'] = $arrData['comment_login_tw'];
        $sqlval_wppost_comment['fb_appid'] = $arrData['fb_appid'];
        $sqlval_wppost_comment['fb_secret'] = $arrData['fb_secret'];
        $sqlval_wppost_comment['tw_consumer_key'] = $arrData['tw_consumer_key'];
        $sqlval_wppost_comment['tw_consumer_secret'] = $arrData['tw_consumer_secret'];
        $sqlval_wppost_comment['comment_format'] = $arrData['comment_format'];
        $sqlval_wppost_comment['comment_num'] = $arrData['comment_num'];

        $where_wppost_comment = 'id = ?';
        // UPDATEの実行
        $objQuery->update('plg_WpPost_comment', $sqlval_wppost_comment, $where_wppost_comment, array(1));

        $objQuery->commit();
        return $arrErr;
    }
}
?>
