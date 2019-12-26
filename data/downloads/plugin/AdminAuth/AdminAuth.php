<?php
/*
 *
 * AdminAuth
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
 * @package AdminAuth
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class AdminAuth extends SC_Plugin_Base {

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
        $create_table = <<< EOSQL
CREATE TABLE plg_adminauth (
member_id int NOT NULL,
personal_flg smallint NOT NULL default 0,
store_body text,
creator_id int NOT NULL,
create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
update_date timestamp NOT NULL,
UNIQUE(personal_flg, member_id)
);
EOSQL;

        $objQuery->query($create_table);
        $seq_name = 'plg_adminauth_member_id';
        $objManager =& $objQuery->conn->loadModule('Manager');
        $objManager->createSequence($seq_name, 1);

        $create_table = <<< EOSQL
CREATE TABLE plg_authstore (
store_id int NOT NULL,
parent_url text NOT NULL,
parent_name text NOT NULL,
child_url text NOT NULL,
child_name text NOT NULL,
template_name text,
create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
update_date timestamp NOT NULL,
PRIMARY KEY (store_id)
);
EOSQL;
        $objQuery->query($create_table);
        $seq_name = 'plg_authstore_store_id';
        $objManager->createSequence($seq_name, 1);

        $arrInsertData = AdminAuth::getInsertData($objQuery);

        $objQuery->commit();

        // ロゴ画像コピー
        if(copy(PLUGIN_UPLOAD_REALDIR . "AdminAuth/logo.png", PLUGIN_HTML_REALDIR . "AdminAuth/logo.png") === false) print_r("失敗");
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
        $sql = "DROP TABLE plg_adminauth";
        $objQuery->query($sql);
        $sql = "DROP TABLE plg_authstore";
        $objQuery->query($sql);
        
        $objManager =& $objQuery->conn->loadModule('Manager');
        $seq_name = 'plg_adminauth_member_id';
        $objManager->dropSequence($seq_name);
        $seq_name = 'plg_authstore_store_id';
        $objManager->dropSequence($seq_name);

        // 不要なファイル削除.
        if(file_exists(PLUGIN_HTML_REALDIR . "AdminAuth/logo.png")) SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "AdminAuth/logo.png");
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
        // 必要なファイルをコピーします.
        if(copy(PLUGIN_UPLOAD_REALDIR . "AdminAuth/config.php", PLUGIN_HTML_REALDIR . "AdminAuth/config.php") === false) print_r("失敗");
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
        if(count($_SESSION['admin_auth']) <= 0) return;
        $objTransform = new SC_Helper_Transform($source);
        //$template_dir = PLUGIN_UPLOAD_REALDIR . 'BoundioReminder/templates/';
        $arrAdminAuth = $_SESSION['admin_auth'];
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_ADMIN:
            default:
                // 大グローバルメニュー
                if (strpos($filename, 'main_frame.tpl') !== false) {
                    foreach($arrAdminAuth['parent'] as $key => $val){
                        if($val['store_id'] == "1"){
                            $objTransform->select('li#navi-'.$key)->removeElement();
                        }
                    }
                }
                
                // 小メニュー
                foreach($arrAdminAuth['child'] as $val){
                    if($val['store_id'] == "1"){
                        if (strpos($filename, $val['parent_url']. '/subnavi.tpl') !== false) {
                            $objTransform->select('li#'.$val['template_name'])->removeElement();
                        }
                    }
                }
                
                break;
        }
        $source = $objTransform->getHTML();
    }

    /**
     * 処理の介入箇所とコールバック関数を設定
     * registerはプラグインインスタンス生成時に実行されます
     * 
     * @param SC_Helper_Plugin $objHelperPlugin 
     * @return void
     */
    function register(SC_Helper_Plugin $objHelperPlugin) {
        $objHelperPlugin->addAction('LC_Page_process', array($this, 'enableAdminPage'));
        $objHelperPlugin->addAction('prefilterTransform', array(&$this, 'prefilterTransform'));
        $objHelperPlugin->addAction('LC_Page_Admin_Logout_action_before', array($this, 'authsessionTrash'));
    }
    
    function authsessionTrash(){
         unset($_SESSION['admin_auth']);
    }
    
    function enableAdminPage(){
         if(!SC_Utils_Ex::sfIsInt($_SESSION['member_id'])) return;
         
         SC_Helper_FileManager_Ex::deleteFile(COMPILE_ADMIN_REALDIR, false);
         
         if($_SESSION['authority'] == '0') return;
         
         if(count($_SESSION['admin_auth']) <= 0){
             $_SESSION['admin_auth'] = $this->getAdminAuth();
         }
         if(count($_SESSION['admin_auth']) > 0){
             $this->enableAdminAuth($_SESSION['admin_auth']);
         }
    }
    
     /**
     * 管理画面の情報を取得
     * @return array 
     */
    function getAdminAuth() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $array = $objQuery->select("*", "plg_authstore");
        
        $where = "member_id = ? AND personal_flg = ?";
        $arrval = array($_SESSION['member_id'], "1");
        
        // 個人データの有無調べ
        $store_body = $objQuery->getOne("SELECT store_body FROM plg_adminauth WHERE ". $where, $arrval);
        
        // ない場合は権限特有データの有無調べ
        if(!$store_body){
            $arrval = array($_SESSION['authority'], "0");
            $store_body = $objQuery->getOne("SELECT store_body FROM plg_adminauth WHERE ".$where, $arrval);
        }
        // データが正常に取得できた場合はunserialize
        if($store_body){
            // store_bodyをunserialize
            $arrStoreBody = unserialize($store_body);
        }else{
            // store_bodyに何も無ければアクセス不可無し
            return;
        }
        
        foreach($array as $val){
            // グローバル大メニュー表示用 0:表示 1:非表示
            if(is_null($arrAdminAuth['parent'][$val['parent_url']])) $arrAdminAuth['parent'][$val['parent_url']] = "1";

            if($arrStoreBody[$val['store_id']] != "1"){
                $arrAdminAuth['parent'][$val['parent_url']] = "0";
                $arrStoreBody[$val['store_id']] = "0";
            }
            
            // 小メニュー権限/表示用 0:表示 1:非表示
            $arrAdminAuth['child']['/admin/'.$val['parent_url'].'/'.$val['child_url']] = array(
                                                                                            'store_id' => $arrStoreBody[$val['store_id']],
                                                                                            'template_name' => $val['template_name'],
                                                                                            'parent_url' => $val['parent_url']
                                                                                         );
        }
        return $arrAdminAuth;
    }

     /**
     * 管理画面の情報を取得
     * @param array $arrAdminAuth 管理画面ページアクセス可能不可能
     * @return array 
     */
    function enableAdminAuth($arrAdminAuth) {
        if($arrAdminAuth['child'][$_SERVER['SCRIPT_NAME']]['store_id'] == "1") SC_Utils_Ex::sfDispError(AUTH_ERROR);
    }

     /**
     * plg_authstoreに初期でinsertする値を取得
     * 
     * @return array 
     */
    function getInsertData($objQuery){
// basis
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('1', 'basis', '基本情報管理', 'index.php', 'SHOPマスター', 'navi-basis-index', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('2', 'basis', '基本情報管理', 'tradelaw.php', '特定商取引法', 'navi-basis-tradelaw', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('3', 'basis', '基本情報管理', 'delivery.php', '配送方法設定', 'navi-basis-delivery', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('4', 'basis', '基本情報管理', 'payment.php', '支払方法設定', 'navi-basis-payment', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('5', 'basis', '基本情報管理', 'point.php', 'ポイント設定', 'navi-basis-point', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('6', 'basis', '基本情報管理', 'mail.php', 'メール設定', 'navi-basis-mail', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('7', 'basis', '基本情報管理', 'seo.php', 'SEO設定', 'navi-basis-seo', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('8', 'basis', '基本情報管理', 'kiyaku.php', '会員規約設定', 'navi-basis-kiyaku', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('9', 'basis', '基本情報管理', 'zip_install.php', '郵便番号DB登録', 'navi-basis-zip', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('10', 'basis', '基本情報管理', 'holiday.php', '定休日設定', 'navi-basis-holiday', 'now()', 'now()');");
// products
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('11', 'products', '商品管理', 'index.php', '商品マスター', 'navi-products-index', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('12', 'products', '商品管理', 'product.php', '商品登録', 'navi-products-product', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('13', 'products', '商品管理', 'upload_csv.php', '商品登録CSV', 'navi-products-uploadcsv', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('14', 'products', '商品管理', 'class.php', '企画管理', 'navi-products-class', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('15', 'products', '商品管理', 'category.php', 'カテゴリ登録', 'navi-products-category', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('16', 'products', '商品管理', 'upload_csv_category.php', '商品', 'navi-products-upload-csv-category', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('17', 'products', '商品管理', 'maker.php', 'メーカー登録', 'navi-products-maker', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('18', 'products', '商品管理', 'product_rank.php', '商品並び替え', 'navi-products-rank', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('19', 'products', '商品管理', 'review.php', 'レビュー管理', 'navi-products-review', 'now()', 'now()');");
// customer
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('20', 'customer', '顧客管理', 'index.php', '顧客マスター', 'navi-customer-index', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('21', 'customer', '顧客管理', 'edit.php', '顧客登録', 'navi-customer-customer', 'now()', 'now()');");
// order
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('22', 'order', '受注管理', 'index.php', '受注管理', 'navi-order-index', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('23', 'order', '受注管理', 'edit.php', '受注登録', 'navi-order-add', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('24', 'order', '受注管理', 'status.php', '対応状況管理', 'navi-order-status', 'now()', 'now()');");
// total
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('25', 'total', '売上集計', 'index.php', '売上集計', NULL, 'now()', 'now()');");
// mail
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('26', 'mail', 'メルマガ管理', 'index.php', '配信内容設定', 'navi-mail-index', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('27', 'mail', 'メルマガ管理', 'template.php', 'テンプレート設定', 'navi-mail-template', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('28', 'mail', 'メルマガ管理', 'history.php', '配信履歴', 'navi-mail-history', 'now()', 'now()');");
// contents
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('29', 'contents', 'コンテンツ管理', 'index.php', '新着情報管理', 'navi-contents-index', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('30', 'contents', 'コンテンツ管理', 'recommend.php', 'おすすめ商品管理', 'navi-contents-recommend', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('31', 'contents', 'コンテンツ管理', 'file_manager.php', 'ファイル管理', 'navi-contents-file', 'now()', 'now()');");
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('32', 'contents', 'コンテンツ管理', 'csv.php', 'CSV出力設定', 'navi-contents-csv', 'now()', 'now()');");
// design
$objQuery->query("INSERT INTO plg_authstore (store_id, parent_url, parent_name, child_url, child_name, template_name, create_date, update_date) VALUES ('33', 'design', 'デザイン管理', 'index.php', 'デザイン管理', NULL, 'now()', 'now()');");
    }
}
?>
