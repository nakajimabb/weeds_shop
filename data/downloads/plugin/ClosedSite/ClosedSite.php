<?php
/*
 *
 * ClosedSite
 * Copyright(c) 2012 Cyber-Will Inc. All Rights Reserved.
 *
 * http://www.cyber-will.co.jp/
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
 * @package ClosedSite
 * @author Cyber-Will Inc. YAMASAKI Yutaka
 * @version $Id: $
 */
class ClosedSite extends SC_Plugin_Base {

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
        if(copy(PLUGIN_UPLOAD_REALDIR . "ClosedSite/logo.png", PLUGIN_HTML_REALDIR . "ClosedSite/logo.png") === false);
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
        // ロゴ削除
        if(unlink(PLUGIN_HTML_REALDIR . "ClosedSite/logo.png") === false);
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


    function preProcess(LC_Page_Ex $objPage) {
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("ClosedSite");
        if($plugin['enable'] == 1){
            switch($objPage->arrPageLayout['device_type_id']){
                case DEVICE_TYPE_MOBILE:
                case DEVICE_TYPE_SMARTPHONE:
                case DEVICE_TYPE_PC:
                    // ログインしていなかったら、ログイン画面に遷移
                    $objCustomer = new SC_Customer_Ex();
                    // 強制転送除外ファイル
                    $arrFiles = array('mypage/login.php',
                                      'entry/kiyaku.php', 'entry/index.php', 'entry/complete.php',
                                      'regist/complete.php',
                                      'abouts/index.php',
                                      'order/index.php',
                                      'guide/about.php', 'guide/charge.php', 'guide/index.php', 'guide/kiyaku.php', 'guide/privacy.php',
                                      );
                    if ($objCustomer->isLoginSuccess(true) === false &&
                        !in_array($objPage->arrPageLayout['url'], $arrFiles)) {
                        $url = "/mypage/login.php";
                        SC_Response_Ex::sendRedirect($objPage->getLocation($url));
                        exit;
                    }
                    break;
                case DEVICE_TYPE_ADMIN:
                default:
                    break;
            }
        }
    }

    /**
     * 処理の介入箇所とコールバック関数を設定
     * registerはプラグインインスタンス生成時に実行されます
     * 
     * @param SC_Helper_Plugin $objHelperPlugin 
     */
    function register(SC_Helper_Plugin $objHelperPlugin) {
    }
}
?>
