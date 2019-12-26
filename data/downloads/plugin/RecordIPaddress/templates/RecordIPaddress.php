<?php
/*
 * RecordIPaddress
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
 * IPアドレス記録プラグイン の情報クラス.
 *
 * @package RecordIPaddress
 * @author CUORE CO.,LTD.
 */

class RecordIPaddress extends SC_Plugin_Base {

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
        $objQuery->query('ALTER TABLE dtb_order ADD plg_recordipaddress_ip TEXT');
        $objQuery->query('ALTER TABLE dtb_review ADD plg_recordipaddress_ip TEXT');
        // CSV用データインサート
        $csv_no = $objQuery->nextVal('dtb_csv_no');
        $dtb_csv_val = array ('no'=>$csv_no,
        	'csv_id'=>3,
        	'col'=>'plg_recordipaddress_ip',
        	'disp_name'=>'IPアドレス',
        	'rank'=>58,
        	'rw_flg'=>1,
        	'status'=>1,
        	'create_date'=>'CURRENT_TIMESTAMP',
        	'update_date'=>'CURRENT_TIMESTAMP',
        	'mb_convert_kana_option'=>'',
        	'size_const_type'=>'',
        	'error_check_types'=>'') ;
        $objQuery->insert('dtb_csv',$dtb_csv_val);
        $csv_no = $objQuery->nextVal('dtb_csv_no');
        $dtb_csv_val = array ('no'=>$csv_no,
        	'csv_id'=>4,
        	'col'=>'A.plg_recordipaddress_ip',
        	'disp_name'=>'IPアドレス',
        	'rank'=>9,
        	'rw_flg'=>1,
        	'status'=>1,
        	'create_date'=>'CURRENT_TIMESTAMP',
        	'update_date'=>'CURRENT_TIMESTAMP',
        	'mb_convert_kana_option'=>'',
        	'size_const_type'=>'',
        	'error_check_types'=>'') ;
        $objQuery->insert('dtb_csv',$dtb_csv_val);
        if(copy(PLUGIN_UPLOAD_REALDIR . "RecordIPaddress/logo.png", PLUGIN_HTML_REALDIR . "RecordIPaddress/logo.png") === false);
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
        //プラグインをアンインストールしただけで
        //記録したIPを削除するのは微妙なきがしますが、とりあえず削除
        $objQuery->query("ALTER TABLE dtb_order DROP plg_recordipaddress_ip");
        $objQuery->query("ALTER TABLE dtb_review DROP plg_recordipaddress_ip");
        $csv_no = $objQuery->select('no', 'dtb_csv', 'col = ? AND csv_id = ?', array("plg_recordipaddress_ip", 3));
        $objQuery->delete('dtb_csv','no = ?',array ($csv_no[0]['no']));
        $csv_no = $objQuery->select('no', 'dtb_csv', 'col = ? AND csv_id = ?', array("A.plg_recordipaddress_ip", 4));
        $objQuery->delete('dtb_csv','no = ?',array ($csv_no[0]['no']));
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
     * レビュー書き込み時にクライアントのIPアドレスを記録します。
     *
     * このようにDBに対してのアクセスをフックする場合は、どのようにするのがベストでしょうか？
     * レビューに関しては一意を特定する事が困難かと思います。
     * レビューIDの現在値＋必須書き込み内容が一致とか、無理やりな更新になってます。
     *
     * @param LC_Page_Products_Review $objPage <フロント画面>レビュー書き込み.
     * @return void
     */
    function review_ip_set($objPage) {
        $objFormParam = new SC_FormParam_Ex();
        $objPage->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrPara = $objFormParam->getDbArray();
        $arrValues['plg_recordipaddress_ip'] = $_SERVER['REMOTE_ADDR'];
        $review_id = $objQuery->currVal('dtb_review_review_id');

        $where = 'review_id = ? AND '
            .'product_id = ? AND '
            .'reviewer_name = ? AND '
            .'recommend_level = ? AND '
            .'title = ? AND '
            .'comment = ?';
        $arrWhereVal = array($review_id,
            $arrPara['product_id'],
            $arrPara['reviewer_name'],
            $arrPara['recommend_level'],
            $arrPara['title'],
            $arrPara['comment']);

        $objQuery->update('dtb_review', $arrValues, $where, $arrWhereVal);
    }

    /**
     * 購入時にクライアントのIPアドレスを記録します。
     *
     * @param LC_Page_Shopping_Confirm $objPage <フロント画面>購入確認画面.
     * @return void
     */
    function shopping_ip_set($objPage) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrValues['plg_recordipaddress_ip'] = $_SERVER['REMOTE_ADDR'];
        $where = 'order_id = ?';
        $arrWhereVal = array($objPage->arrForm['order_id']);
        $objQuery->update('dtb_order', $arrValues, $where, $arrWhereVal);
    }

    /**
     * レビュー書き込み時のクライアントのIPアドレスを取得します。
     *
     * @param LC_Page_Admin_Products_ReviewEdit $objPage <管理画面>レビュー編集.
     * @return void
     */
    function review_ip_get($objPage) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = 'review_id = ?';
        $arrWhereVal = array($objPage->arrForm['review_id']);
        $array_review = $objQuery->select('plg_recordipaddress_ip','dtb_review', $where, $arrWhereVal);
        $objPage->arrForm['plg_recordipaddress_ip'] = $array_review[0]['plg_recordipaddress_ip'];
    }


    /**
     * 購入時のクライアントのIPアドレスを取得します。
     *
     * @param LC_Page_Admin_Order_Edit $objPage <管理画面>受注修正.
     * @return void
     */
    function order_ip_get($objPage) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = 'order_id = ?';
        $arrWhereVal = array($_REQUEST['order_id']);
        $array_order = $objQuery->select('plg_recordipaddress_ip','dtb_order', $where, $arrWhereVal);
        $objPage->arrForm['plg_recordipaddress_ip'] = array( 'keyname' => 'plg_recordipaddress_ip' , 'disp_name' => 'IPアドレス' , 'value' => $array_order[0]['plg_recordipaddress_ip']);
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
        $template_dir = PLUGIN_UPLOAD_REALDIR . 'RecordIPaddress/templates/';
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC:
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                // 受注修正画面
                if (strpos($filename, 'order/edit.tpl') !== false) {
                    $objTransform->select('div#order.contents-main table.form tr',4)->insertAfter(file_get_contents($template_dir . 'plg_RecordIPaddress_snip_admin_order_edit_tr.tpl'));
                }
                // レビュー編集画面
                if (strpos($filename, 'products/review_edit.tpl') !== false) {
                    $objTransform->select('div#products.contents-main table tr',6)->insertAfter(file_get_contents($template_dir . 'plg_RecordIPaddress_snip_admin_products_review_edit_tr.tpl'));
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
        // IPアドレス記録側フックポイント
        $objHelperPlugin->addAction('LC_Page_Products_Review_action_complete', array($this, 'review_ip_set'), $this->arrSelfInfo['priority']);
        $objHelperPlugin->addAction('LC_Page_Shopping_Confirm_action_confirm', array($this, 'shopping_ip_set'), $this->arrSelfInfo['priority']);
        $objHelperPlugin->addAction('LC_Page_Shopping_Confirm_action_confirm_module', array($this, 'shopping_ip_set'), $this->arrSelfInfo['priority']);

        // IPアドレス表示側フックポイント
        $objHelperPlugin->addAction('LC_Page_Admin_Products_ReviewEdit_action_after', array($this, 'review_ip_get'), $this->arrSelfInfo['priority']);
        $objHelperPlugin->addAction('LC_Page_Admin_Order_Edit_action_after', array($this, 'order_ip_get'), $this->arrSelfInfo['priority']);
        $objHelperPlugin->addAction('prefilterTransform', array(&$this, 'prefilterTransform'), $this->arrSelfInfo['priority']);
    }
}
?>
