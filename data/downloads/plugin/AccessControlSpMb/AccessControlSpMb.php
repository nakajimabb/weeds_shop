<?php
/*
 * AccessControlSpMb
 * Copyright(c) C-Rowl Co., Ltd. All Rights Reserved.
 * http://www.c-rowl.com/
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
 * スマートフォン・携帯アクセス制御 のメインクラス.
 *
 * @package AccessControlSpMb
 * @author C-Rowl Co., Ltd.
 */
class AccessControlSpMb extends SC_Plugin_Base {

    /**
     * コンストラクタ
     */
    public function __construct(array $arrSelfInfo) {

        // 2.12.2のみ対応 (2.12.1は使用できません)
        // SC_Displayの変更により、今後動作しなくなるかもしれません。

        // 設定を取得する
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("AccessControlSpMb");
        $plg_sp = $plugin['free_field1'];
        $plg_mb = $plugin['free_field2'];

        // 画面の制御を行う
        $objDisplay = new SC_Display_Ex();
        $device = $objDisplay->detectDevice();
        // 本来のアクセス端末を保持しておく。受注データに入れるので必ず取得
        $_SESSION['plg_accesscontrolspmb_device_session'] = $device;

        switch ($device) {
            case DEVICE_TYPE_MOBILE:
                // 携帯
                if ($plg_mb == 1) {
                    // 固定でHTMLを表示する
                    SC_Response_Ex::sendRedirect(HTTP_URL."plg_mobile.html");
                }
                break;
            case DEVICE_TYPE_SMARTPHONE:
                // スマートフォン
                if ($plg_sp == 1) {
                    // PC画面を表示する
                    SC_Display_Ex::$device = DEVICE_TYPE_PC;
                }
                if ($plg_sp == 2) {
                    // PC画面を表示するボタンを用意する場合
                    $sp_flg = $_GET['plg_accesscontrolspmb'];
                    if( strcmp( $sp_flg, "pc" ) === 0 ){ 
                        SC_Display_Ex::$device = DEVICE_TYPE_PC;
                        $_SESSION['plg_accesscontrolspmb_session'] = "pc";
                    } else 
                    if( strcmp( $sp_flg, "sp" ) === 0 ){ 
                        unset($_SESSION['plg_accesscontrolspmb_session']);
                    } else {
                        // セッションがある場合は、PC画面を表示する
                        $chk = $_SESSION['plg_accesscontrolspmb_session'];
                        if( strcmp( $chk, "pc" ) === 0 ){ 
                            SC_Display_Ex::$device = DEVICE_TYPE_PC;
                        }
                    }
                }
                break;
            case DEVICE_TYPE_PC:
                // PC
                break;
            case DEVICE_TYPE_ADMIN:
                // 管理画面
                break;
        }
        parent::__construct($arrSelfInfo);
    }

    function process(LC_Page_EX $objPage) {
        //この関数をプラグイン内に定義するだけで実行されます。
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
        // 設定の初期化
        AccessControlSpMb::insertFreeField();
        // ファイルコピー
        if(copy(PLUGIN_UPLOAD_REALDIR . "AccessControlSpMb/logo.png", PLUGIN_HTML_REALDIR . "AccessControlSpMb/logo.png") === false);
        // 携帯用のHTMLをコピーする
        if(copy(PLUGIN_UPLOAD_REALDIR . "AccessControlSpMb/plg_mobile.html", HTML_REALDIR . "plg_mobile.html") === false);
    }

    /**
     * プラグイン独自の設定データを追加
     *
     * @return void
     */
    function insertFreeField() {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $sqlval = array();
        $sqlval['free_field1'] = '0';
        $sqlval['free_field2'] = '0';
        $sqlval['free_field3'] = '0';
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = 'plugin_code = ?';
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where, array('AccessControlSpMb'));
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
        // ファイル削除
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "AccessControlSpMb/logo.png") === false); //print_r("失敗");
        // 携帯用のHTMLを削除する
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "plg_mobile.html") === false); //print_r("失敗");
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
     * @return void
     */
    function register(SC_Helper_Plugin $objHelperPlugin) {
        $objHelperPlugin->addAction("prefilterTransform", array(&$this, "prefilterTransform"), $this->arrSelfInfo['priority']);
        $objHelperPlugin->addAction("loadClassFileChange", array(&$this, "loadClassFileChange"), $this->arrSelfInfo['priority']);
    }

    /**
     * テンプレートをフックする
     *
     * @param string &$source
     * @param LC_Page_Ex $objPage
     * @param string $filename
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR ."AccessControlSpMb/templates/";
        switch($objPage->arrPageLayout['device_type_id']) {
            case DEVICE_TYPE_PC:
                $template_dir .= "default/";
                if(strpos($filename, "footer.tpl") !== false) {
                    $objTransform->select("div#copyright", 0)->insertAfter( file_get_contents($template_dir . "plg_AccessControlSpMb_index.tpl") );
                }
                break;
            case DEVICE_TYPE_MOBILE:
                break;
            case DEVICE_TYPE_SMARTPHONE:
                $template_dir .= "sphone/";
                if(strpos($filename, "footer.tpl") !== false) {
                    $objTransform->select("p.copyright", 0)->insertAfter( file_get_contents($template_dir . "plg_AccessControlSpMb_index.tpl") );
                }
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                break;
        }
        $source = $objTransform->getHTML();
    }

    /**
     * この関数をプラグイン内に定義するだけで実行されます。
     *
     * @param string &$classname
     * @param string &$classpath
     * @return void
     */
    function preProcess(LC_Page_Ex $objPage) {
        switch($objPage->arrPageLayout['device_type_id']) {
            case DEVICE_TYPE_PC:
                // 設定を取得する
                $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("AccessControlSpMb");
                $plg_sp = $plugin['free_field1'];
                // スマートフォンの場合のみ表示する。PC固定の場合は不要
                $ori_device = $_SESSION['plg_accesscontrolspmb_device_session'];
                if (($ori_device == DEVICE_TYPE_SMARTPHONE) && ($plg_sp == 2)) {
                    $objPage->plg_accesscontrolspmb_device_flg = true;
                    $objPage->plg_accesscontrolspmb_url = $this->getSwitchURL('sp', $plugin['free_field3']);
                }
                break;
            case DEVICE_TYPE_MOBILE:
                break;
            case DEVICE_TYPE_SMARTPHONE:
                // 設定を取得する
                $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("AccessControlSpMb");
                $plg_sp = $plugin['free_field1'];
                if ($plg_sp == 2) {
                    $objPage->plg_accesscontrolspmb_device_flg = true;
                    $objPage->plg_accesscontrolspmb_url = $this->getSwitchURL('pc', $plugin['free_field3']);
                }
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                break;
        }
    }

    /**
     * SC_系のクラスをフックする
     *
     * @param string &$classname
     * @param string &$classpath
     * @return void
     */
    function loadClassFileChange(&$classname, &$classpath) {
        if($classname == "SC_Helper_Purchase_Ex") {
            $classpath = PLUGIN_UPLOAD_REALDIR . "AccessControlSpMb/plg_AccessControlSpMb_SC_Helper_Purchase.php";
            $classname = "plg_AccessControlSpMb_SC_Helper_Purchase";
        }
    }

    /**
     * 切替ボタンのURLを取得
     */
    function getSwitchURL($type, $flg) {
        if ($flg == 1) {
            // 同じページヘ遷移
            $rtnUrl = '';
            $tmpGet = $_GET;
            $tmpGet['plg_accesscontrolspmb'] = $type;
            $tmpQuery = http_build_query($tmpGet);
            $rtnUrl = $_SERVER['PHP_SELF'] . '?' . $tmpQuery;
        } else {
            // トップページへ遷移
            $tmpGet = array();
            $tmpGet['plg_accesscontrolspmb'] = $type;
            $tmpQuery = http_build_query($tmpGet);
            $rtnUrl = HTTP_URL . '?' . $tmpQuery;
        }
        return $rtnUrl;
    }

}
?>
