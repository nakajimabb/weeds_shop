<?php
/*
 * SiteMaintenance
 *
 * Copyright(c) 2009-2012 CUORE CO.,LTD. All Rights Reserved.
 *
 * http://ec.cuore.jp/
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
 * メンテナンス切り替え機能プラグイン の情報クラス.
 *
 * @package SiteMaintenance
 * @author CUORE CO.,LTD.
 */

class SiteMaintenance extends SC_Plugin_Base {

    /**
     * コンストラクタ
     *
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
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        // カラム用意
        $objQuery->query('ALTER TABLE dtb_baseinfo ADD plg_sitemaintenance_maintenance smallint DEFAULT 1');
        $objQuery->query('ALTER TABLE dtb_baseinfo ADD plg_sitemaintenance_maintenance_msg text');

        // テーブル追加
        $mtb_maintenance = <<< __EOS__
CREATE TABLE plg_sitemaintenance_mtb_maintenance (
    id int2,
    name text,
    rank int2 NOT NULL,
    PRIMARY KEY (id)
);
__EOS__;
        $objQuery->query($mtb_maintenance);

        // mtb_maintenanceテーブル用のデータ追加
        $mtb_maintenance_data[] = array("id"=>0,"name"=>"通常営業中","rank"=>0);
        $mtb_maintenance_data[] = array("id"=>1,"name"=>"メンテナンス中","rank"=>1);
        foreach ($mtb_maintenance_data as $sqlval){
            $objQuery->insert("plg_sitemaintenance_mtb_maintenance",$sqlval);
        }

        // 必要なファイルをコピーします.
        mkdir(TEMPLATE_REALDIR . "site_maintenance");
        mkdir(MOBILE_TEMPLATE_REALDIR . "site_maintenance");
        mkdir(SMARTPHONE_TEMPLATE_REALDIR . "site_maintenance");
        if(copy(PLUGIN_UPLOAD_REALDIR . "SiteMaintenance/templates/default/plg_SiteMaintenance_maintenance.tpl", TEMPLATE_REALDIR . "site_maintenance/plg_SiteMaintenance_maintenance.tpl") === false) print_r("失敗");
        if(copy(PLUGIN_UPLOAD_REALDIR . "SiteMaintenance/templates/mobile/plg_SiteMaintenance_maintenance.tpl", MOBILE_TEMPLATE_REALDIR . "site_maintenance/plg_SiteMaintenance_maintenance.tpl") === false) print_r("失敗");
        if(copy(PLUGIN_UPLOAD_REALDIR . "SiteMaintenance/templates/sphone/plg_SiteMaintenance_maintenance.tpl", SMARTPHONE_TEMPLATE_REALDIR . "site_maintenance/plg_SiteMaintenance_maintenance.tpl") === false) print_r("失敗");
        if(copy(PLUGIN_UPLOAD_REALDIR . "SiteMaintenance/admin/basis/plg_SiteMaintenance_maintenance.php", HTML_REALDIR . "admin/basis/plg_SiteMaintenance_maintenance.php") === false) print_r("失敗");
        if(copy(PLUGIN_UPLOAD_REALDIR . "SiteMaintenance/logo.png", PLUGIN_HTML_REALDIR . "SiteMaintenance/logo.png") === false) print_r("失敗");;
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
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // カラム削除
        $objQuery->query("ALTER TABLE dtb_baseinfo DROP plg_sitemaintenance_maintenance");
        $objQuery->query("ALTER TABLE dtb_baseinfo DROP plg_sitemaintenance_maintenance_msg");

        // テーブル削除
        $objQuery->query("DROP TABLE plg_sitemaintenance_mtb_maintenance;");

        //プラグインファイル削除
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "site_maintenance") === false); // TODO エラー処理
        if(SC_Helper_FileManager_Ex::deleteFile(MOBILE_TEMPLATE_REALDIR . "site_maintenance") === false); // TODO エラー処理
        if(SC_Helper_FileManager_Ex::deleteFile(SMARTPHONE_TEMPLATE_REALDIR . "site_maintenance") === false); // TODO エラー処理
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "admin/basis/plg_SiteMaintenance_maintenance.php") === false); // TODO エラー処理
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "SiteMaintenance") === false); // TODO エラー処理
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
     * メンテナンス判定を行う.
     * メンテナンス中の場合はメンテナンスページへ遷移する
     *
     * @return void
     */
    function chkMaintenance() {

        //店舗基本情報を取得する
        $obj_hdb = new SC_Helper_DB();
        $arrRet = $obj_hdb->sfGetBasisData();

        //店舗情報からメンテナンス判断を行う
        if (isset($arrRet['plg_sitemaintenance_maintenance'])){
            if ( $arrRet['plg_sitemaintenance_maintenance'] == PLG_SITEMAINTENANCE_MAINTENANCE_TRUE){
                //メンテナンス中につきメンテナンスページへ
                $this->dispSiteMaintenance($arrRet['plg_sitemaintenance_maintenance_msg']);
            }
        }
    }

    /**
     * サイトメンテナンスページの表示
     *
     * @param string $maintenance_msg 表示メッセージ
     * @return void
     */
    function dispSiteMaintenance($maintenance_msg) {

        //メンテナンスページクラスを宣言します
        require_once PLUGIN_UPLOAD_REALDIR . 'SiteMaintenance/maintenance/plg_SiteMaintenance_LC_Page_Maintenance.php';

        $objPage = new plg_SiteMaintenance_LC_Page_Maintenance();
        register_shutdown_function(array($objPage, 'destroy'));
        $objPage->init();
        $objPage->gfSetMaintenanceMsg($maintenance_msg);
        $objPage->process();
        exit();
    }

    /**
     * プレプロセスコールバック関数
     * スーパーフックポイント
     *
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function preProcess (LC_Page $objPage) {

        /** メンテナンス中パラメータ */
        if (!defined('PLG_SITEMAINTENANCE_MAINTENANCE_TRUE')) {
            define('PLG_SITEMAINTENANCE_MAINTENANCE_TRUE', 1);
        }

        //基底クラス判定
        if(is_a($objPage,"LC_Page") && !is_a($objPage,"LC_Page_Admin") &&
           get_class($objPage) != 'LC_Page_ResizeImage_Ex' && get_class($objPage) != 'LC_Page_InputZip_Ex'){
            //フロントクラスでかつ、画像サイズ変更・住所入力以外の場合のみメンテナンス中画面表示判定を行う
            $this->chkMaintenance();
        }

    }

    /**
     * プレフィルタコールバック関数
     *
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . 'SiteMaintenance/templates/';
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC:
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                // 基本情報管理 サブナビ
                if (strpos($filename, 'admin/basis/subnavi.tpl') !== false) {
                    // フォーム内にtr要素を追加する
                    $objTransform->select('li#navi-basis-holiday')->insertAfter(file_get_contents($template_dir . 'admin/basis/plg_SiteMaintenance_snip_admin_basis_subnavi_add.tpl'));
                }
                break;
        }
        $source = $objTransform->getHTML();
    }

    /**
     * 処理の介入箇所とコールバック関数を設定
     * registはプラグインインスタンス生成時に実行されます
     *
     * @param SC_Helper_Plugin $objHelperPlugin
     * @return void
     */
    function register(SC_Helper_Plugin $objHelperPlugin) {
        $objHelperPlugin->addAction('prefilterTransform', array(&$this, 'prefilterTransform'), $this->arrSelfInfo['priority']);
    }
}
?>
