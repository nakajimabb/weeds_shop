<?php
/*
 * TplAsYouLike
 * Copyright(c) 2012 SUNATMARK CO.,LTD. All Rights Reserved.
 * http://www.sunatmark.co.jp/
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
 * @package TplAsYouLike
 * @author SUNATMARK CO.,LTD.
 * @version $Id: $
 */
class TplAsYouLike extends SC_Plugin_Base {

    const TAYL_PAGE_LAYOUT_URL = 'TAYL_%03d_%010d';
    
    /**
     * コンストラクタ
     * プラグイン情報(dtb_plugin)をメンバ変数をセットします.
     * @param array $arrSelfInfo プラグイン情報
     * @return void
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
        // ファイルコピー
        if (copy(PLUGIN_UPLOAD_REALDIR . "TplAsYouLike/logo.png", PLUGIN_HTML_REALDIR . "TplAsYouLike/logo.png") === false) {
            $objQuery->delete('dtb_plugin', 'plugin_code = ?', array(get_class()));
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, '', false, PLUGIN_HTML_REALDIR.' に書き込めません。パーミッションをご確認ください。');
        }
        $objQuery->begin();

        $objQuery->exec("ALTER TABLE dtb_pagelayout ADD COLUMN plg_tplasyoulike_tayl_type INTEGER;");
        $objQuery->exec("ALTER TABLE dtb_products ADD COLUMN plg_tplasyoulike_tayl_template_pc INTEGER;");
        $objQuery->exec("ALTER TABLE dtb_products ADD COLUMN plg_tplasyoulike_tayl_template_mb INTEGER;");
        $objQuery->exec("ALTER TABLE dtb_products ADD COLUMN plg_tplasyoulike_tayl_template_sp INTEGER;");
        $objQuery->exec("ALTER TABLE dtb_category ADD COLUMN plg_tplasyoulike_tayl_template_pc INTEGER;");
        $objQuery->exec("ALTER TABLE dtb_category ADD COLUMN plg_tplasyoulike_tayl_template_mb INTEGER;");
        $objQuery->exec("ALTER TABLE dtb_category ADD COLUMN plg_tplasyoulike_tayl_template_sp INTEGER;");
        
        $objQuery->commit();

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
        $objQuery->exec("ALTER TABLE dtb_pagelayout DROP COLUMN plg_tplasyoulike_tayl_type;");
        $objQuery->exec("ALTER TABLE dtb_products DROP COLUMN plg_tplasyoulike_tayl_template_pc;");
        $objQuery->exec("ALTER TABLE dtb_products DROP COLUMN plg_tplasyoulike_tayl_template_mb;");
        $objQuery->exec("ALTER TABLE dtb_products DROP COLUMN plg_tplasyoulike_tayl_template_sp;");
        $objQuery->exec("ALTER TABLE dtb_category DROP COLUMN plg_tplasyoulike_tayl_template_pc;");
        $objQuery->exec("ALTER TABLE dtb_category DROP COLUMN plg_tplasyoulike_tayl_template_mb;");
        $objQuery->exec("ALTER TABLE dtb_category DROP COLUMN plg_tplasyoulike_tayl_template_sp;");

        SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "TplAsYouLike");
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
        // NOP
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
        // NOP
    }

    /**
     * プレフィルタコールバック関数
     *
     * @param string     &$source  テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage  ページオブジェクト
     * @param string     $filename テンプレートのファイル名
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . get_class() . '/templates/';
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC:
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                //サブメニューの追加
                if (strpos($filename, 'design/subnavi.tpl') !== false) {
                    $objTransform->select('.level2', 0)->find('li', 6)->insertAfter(
                        file_get_contents($template_dir . 'plg_tplasyoulike_snip_design_subnavi_pc.tpl'));
                    $objTransform->select('.level2', 1)->find('li', 6)->insertAfter(
                        file_get_contents($template_dir . 'plg_tplasyoulike_snip_design_subnavi_mb.tpl'));
                    $objTransform->select('.level2', 2)->find('li', 6)->insertAfter(
                        file_get_contents($template_dir . 'plg_tplasyoulike_snip_design_subnavi_sp.tpl'));
                }
                
                // products/product.tpl の更新
                if (strpos($filename, 'products/product.tpl') !== false) {
                    $objTransform->select('form')->find('table', 0)->appendChild(
                        file_get_contents($template_dir . 'plg_tplasyoulike_snip_admin_products_product.tpl'));
                }

                // products/confirm.tpl の更新
                if (strpos($filename, 'products/confirm.tpl') !== false) {
                    $objTransform->select('form')->find('table', 0)->appendChild(
                        file_get_contents($template_dir . 'plg_tplasyoulike_snip_admin_products_confirm.tpl'));
                }

                // products/category.tpl の更新
                if (strpos($filename, 'products/category.tpl') !== false) {
                    $objTransform->select('div.now_dir')->find('.btn-normal', 0)->insertBefore(
                        file_get_contents($template_dir . 'plg_tplasyoulike_snip_admin_products_category.tpl'));
                }
                                
                // design/main_edit.tpl の更新
                if (strpos($filename, 'design/main_edit.tpl') !== false) {
                    $objTransform->select('#form_edit')->find('table', 0)->appendChild(
                        file_get_contents($template_dir . 'plg_tplasyoulike_snip_admin_design_main_edit_tr_tayl_type.tpl'));
                    $objTransform->select('#form_edit')->find('table', 0)->find('tr', 1)->replaceElement(
                        file_get_contents($template_dir . 'plg_tplasyoulike_snip_admin_design_main_edit_tr_url.tpl'));                    
                }
                
                break;
        }
        $source = $objTransform->getHTML();
    }

    /**
     * 商品詳細ページのメインテンプレートを差し替える
     *
     * @param LC_Page_Ex $objPage Pageオブジェクト
     * @return void
     */
    public function productsDetailActionAfter(LC_Page_Ex $objPage) {
        $device = SC_Display_Ex::detectDevice();
        
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrResult = $objQuery->select(
            "plg_tplasyoulike_tayl_template_pc, plg_tplasyoulike_tayl_template_mb, plg_tplasyoulike_tayl_template_sp",
            "dtb_products",
            "product_id = ? AND del_flg = 0",
            array($objPage->arrProduct['product_id'])
        );

        $pageLayoutId = NULL;
        if ($device == DEVICE_TYPE_PC && $arrResult[0]['plg_tplasyoulike_tayl_template_pc'])
            $pageLayoutId = $arrResult[0]['plg_tplasyoulike_tayl_template_pc'];
        elseif ($device == DEVICE_TYPE_MOBILE && $arrResult[0]['plg_tplasyoulike_tayl_template_mb'])
            $pageLayoutId = $arrResult[0]['plg_tplasyoulike_tayl_template_mb'];
        elseif ($device == DEVICE_TYPE_SMARTPHONE && $arrResult[0]['plg_tplasyoulike_tayl_template_sp'])
            $pageLayoutId = $arrResult[0]['plg_tplasyoulike_tayl_template_sp'];

        if ($pageLayoutId) {
            $url = sprintf(self::TAYL_PAGE_LAYOUT_URL, $device, $pageLayoutId);
            $objLayout = new SC_Helper_PageLayout_Ex();
            $objLayout->sfGetPageLayout($objPage, false, $url, $device);
        }
    }

    /**
     * 商品一覧ページのテンプレートを差し替える
     *
     * @param LC_Page_Ex $objPage Pageオブジェクト
     * @return void
     */
    public function productsListActionAfter(LC_Page_Ex $objPage) {
        $device = SC_Display_Ex::detectDevice();
        $arrSearchData = $objPage->arrSearchData;
        $pageLayoutId = NULL;
        
        // カテゴリが指定された
        if (array_key_exists('category_id', $arrSearchData) && $arrSearchData['category_id']) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $arrResult = $objQuery->select(
                "plg_tplasyoulike_tayl_template_pc, plg_tplasyoulike_tayl_template_mb, plg_tplasyoulike_tayl_template_sp",
                "dtb_category",
                "category_id = ? AND del_flg = 0",
                array($arrSearchData['category_id'])
            );

            if ($device == DEVICE_TYPE_PC && $arrResult[0]['plg_tplasyoulike_tayl_template_pc'])
                $pageLayoutId = $arrResult[0]['plg_tplasyoulike_tayl_template_pc'];
            elseif ($device == DEVICE_TYPE_MOBILE && $arrResult[0]['plg_tplasyoulike_tayl_template_mb'])
                $pageLayoutId = $arrResult[0]['plg_tplasyoulike_tayl_template_mb'];
            elseif ($device == DEVICE_TYPE_SMARTPHONE && $arrResult[0]['plg_tplasyoulike_tayl_template_sp'])
                $pageLayoutId = $arrResult[0]['plg_tplasyoulike_tayl_template_sp'];
        }
        
        if ($pageLayoutId) {
            $url = sprintf(self::TAYL_PAGE_LAYOUT_URL, $device, $pageLayoutId);
            $objLayout = new SC_Helper_PageLayout_Ex();
            $objLayout->sfGetPageLayout($objPage, false, $url, $device);
        }
    }

    /**
     * 管理者側 デザイン管理ページ詳細設定 actionの最後にテンプレート種類をassign
     * @param LC_Page_Ex $objPage Pageオブジェクト
     * @return void
     */
    function adminDesignMainEditActionAfter(LC_Page_Ex $objPage) {
        if ($this->isAdminDesignMainEditTayl($objPage)) {
            $objPage->arrTaylTypeList = array(
                1 => '商品詳細',
                2 => '商品一覧'
            );
            $objPage->tayl_dummy_filename = time();
            $objPage->plg_TplAsYouLike_isTaylMode = true;
        }
        else {
            $objPage->plg_TplAsYouLike_isTaylMode = false;
        }
        $this->setPageLayoutEditFlg($objPage, 'arrPageList');
    }
    
    /**
     * パラメーター情報の初期化
     * @param LC_Page_Ex $objPage      Pageオブジェクト
     * @param object     $objFormParam FormParamオブジェクト
     * @return void
     */
    public function formParamConstruct($class, SC_FormParam_Ex $objFormParam) {
        if ($class == 'LC_Page_Admin_Design_MainEdit') {
            $objFormParam->addParam("独自テンプレート種類", "plg_tplasyoulike_tayl_type", 
                INT_LEN, 'n', array("NUM_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        }
        else if ($class == 'LC_Page_Admin_Products_Category' || $class == 'LC_Page_Admin_Products_Product') {
            $objFormParam->addParam("PC用商品テンプレート", "plg_tplasyoulike_tayl_template_pc", 
                INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("モバイル用商品テンプレート", "plg_tplasyoulike_tayl_template_mb", 
                INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("スマートフォン用商品テンプレート", "plg_tplasyoulike_tayl_template_sp", 
                INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        }
    }
    
    /**
     * 管理者側 デザイン管理 登録･編集処理
     * @param LC_Page_Ex $objPage      Pageオブジェクト
     * @return void
     */
    public function adminDesignMainEditActionConfirm(LC_Page_Ex $objPage) {
        
        if (!$this->isAdminDesignMainEditTayl($objPage)) return;
        
        $device = $objPage->device_type_id;
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $table = 'dtb_pagelayout';
        $arrSqlVal = array();
        $objQuery->begin();
        
        if (SC_Utils_Ex::isBlank($_POST['page_id'])) {
            //新規なので最大page_idを取得
            $page_id = $objQuery->max('page_id', $table, 'device_type_id = ?', array($device));
        }
        else {
            $page_id = $_POST['page_id'];
        }
        
        $url = sprintf(self::TAYL_PAGE_LAYOUT_URL, $device, $page_id);
        $arrSqlVal['url'] = $url;
        $arrSqlVal['filename'] = USER_DIR . $url;
        $arrSqlVal['update_url'] = NULL;
        $arrSqlVal['plg_tplasyoulike_tayl_type'] = $_POST['plg_tplasyoulike_tayl_type'];

        $objQuery->update($table, $arrSqlVal, 'page_id = ? AND device_type_id = ?', array($page_id, $device));

        $objLayout = new SC_Helper_PageLayout_Ex();
        $tpl_path = $objLayout->getTemplatePath($device) . $arrSqlVal['filename'] . '.tpl';

        $objLayout->lfDelFile(USER_DIR . $_POST['filename'], $device);
        if (!SC_Helper_FileManager_Ex::sfWriteFile($tpl_path, $_POST['tpl_data'])) {
            $objPage->arrErr['err'] = '※ TPLファイルの書き込みに失敗しました<br />';
            $objQuery->rollback();
        }
        $objQuery->commit();
    }

    /**
     * 管理者側 デザイン管理 編集可能ページ一覧取得
     *
     * @param LC_Page_Ex $objPage      Pageオブジェクト
     * @return void
     */
    public function adminDesignActionAfter(LC_Page_Ex $objPage) {
        $this->setPageLayoutEditFlg($objPage, 'arrEditPage');
    }

    /**
     * 管理者側 デザイン管理ページ詳細設定 独自テンプレートの登録、編集かを判定
     *
     * @param LC_Page_Ex $objPage Pageオブジェクト
     * @return void
     */
    protected function isAdminDesignMainEditTayl(LC_Page_Ex $objPage) {
        $arrForm = $objPage->arrForm;
        if (isset($arrForm['plg_tplasyoulike_tayl_type']) && 
            isset($arrForm['plg_tplasyoulike_tayl_type']['value']) && $arrForm['plg_tplasyoulike_tayl_type']['value'])
            return true;
        elseif (array_key_exists('plg_tplasyoulike_tayl_type', $_REQUEST) && $_REQUEST['plg_tplasyoulike_tayl_type'])
            return true;
        else
            return false;
    }
    
    /**
     * 管理者側 デザイン管理ページ詳細設定 独自テンプレートの削除可否判定
     *
     * @param LC_Page_Ex $objPage   Pageオブジェクト
     * @param string     $list_name プロパティ名
     * @return void
     */
    protected function setPageLayoutEditFlg(LC_Page_Ex $objPage, $list_name) {
        $device = $objPage->device_type_id;
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        if ($device == DEVICE_TYPE_PC) $colname = 'plg_tplasyoulike_tayl_template_pc';
        elseif ($device == DEVICE_TYPE_MOBILE) $colname = 'plg_tplasyoulike_tayl_template_mb';
        elseif ($device == DEVICE_TYPE_SMARTPHONE) $colname = 'plg_tplasyoulike_tayl_template_sp';
        else return;
        
        $arrLayoutIds = array();
        
        // 詳細に使用済みのもの
        $arrResult = $objQuery->select(
            $colname,
            "dtb_products",
            "$colname IS NOT NULL AND del_flg = 0"
        );
        foreach ($arrResult as $arrRow) $arrLayoutIds[$arrRow[$colname]] = 1;
        
        // 一覧に使用済みのもの
        $arrResult = $objQuery->select(
            $colname,
            "dtb_category",
            "$colname IS NOT NULL AND del_flg = 0"
        );

        foreach ($arrResult as $arrRow) $arrLayoutIds[$arrRow[$colname]] = 1;

        $arrList = $objPage->$list_name;
        foreach ($arrList as $idx => $arrRow) {
            if (isset($arrLayoutIds[$arrRow['page_id']])) $arrList[$idx]['edit_flg'] = 0;
        }

        // 編集可能ページ一覧の削除フラグを制御
        
        $objPage->$list_name = $arrList;
    }
    
    /**
     * 管理者側 カテゴリ編集入力画面のテンプレート値追加
     *
     * @param LC_Page_Ex $objPage Pageオブジェクト
     * @return void
     */
    public function adminProductsCategoryActionAfter(LC_Page_Ex $objPage) {
        // 入力画面用にテンプレートの一覧を取得
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $this->adminProductAddParam($objPage, $objQuery, 2);
    }

    /**
     * 管理者側 商品追加・編集入力画面のテンプレート値追加
     *
     * @param LC_Page_Ex $objPage Pageオブジェクト
     * @return void
     */
    public function adminProductsProductActionAfter(LC_Page_Ex $objPage) {
        // 入力画面用にテンプレートの一覧を取得
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $this->adminProductAddParam($objPage, $objQuery, 1);
        
        if ($objPage->getMode() == 'complete') {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $product_id = $objPage->arrForm['product_id'];
            $arrSqlVal = array(
                'plg_tplasyoulike_tayl_template_pc' => $_POST['plg_tplasyoulike_tayl_template_pc'], 
                'plg_tplasyoulike_tayl_template_mb' => $_POST['plg_tplasyoulike_tayl_template_mb'], 
                'plg_tplasyoulike_tayl_template_sp' => $_POST['plg_tplasyoulike_tayl_template_sp']
            );
            $objQuery->update('dtb_products', $arrSqlVal, 'product_id = ?', array($product_id));
        }
    }

    /**
     * 管理者側 商品編集画面の用に、独自テンプレートのリストを取得
     *
     * @param LC_Page_Ex   $objPage  Pageオブジェクト
     * @param SC_Query_Ex  $objQuery SC_Query_Ex インスタンス
     * @param integer      $type     1:商品詳細  2:商品一覧
     * @return void
     */
    protected function adminProductAddParam(LC_Page_Ex $objPage, SC_Query_Ex $objQuery, $type) {
        $objQuery->setOrder('page_id');
        $arrLayouts = $objQuery->select('device_type_id, page_id, page_name', 'dtb_pagelayout', 
            'plg_tplasyoulike_tayl_type = ? AND page_id != 0', array($type));
        $arrTaylTemplateListPc = array();
        $arrTaylTemplateListMb = array();
        $arrTaylTemplateListSp = array();
        foreach ($arrLayouts as $row) {
            if ($row['device_type_id'] == 10)    $arrTaylTemplateListPc[$row['page_id']] = $row['page_name'];
            elseif ($row['device_type_id'] == 1) $arrTaylTemplateListMb[$row['page_id']] = $row['page_name'];
            elseif ($row['device_type_id'] == 2) $arrTaylTemplateListSp[$row['page_id']] = $row['page_name'];
        }
        
        $objPage->plg_TplAsYouLike_arrTaylTemplateListPc = $arrTaylTemplateListPc;
        $objPage->plg_TplAsYouLike_arrTaylTemplateListSp = $arrTaylTemplateListSp;
        $objPage->plg_TplAsYouLike_arrTaylTemplateListMb = $arrTaylTemplateListMb;
    }
    
    /**
     * 処理の介入箇所とコールバック関数を設定
     * registerはプラグインインスタンス生成時に実行されます
     * 
     * @param SC_Helper_Plugin $objHelperPlugin 
     */
    function register(SC_Helper_Plugin $objHelperPlugin, $priority) {
        $objHelperPlugin->addAction('prefilterTransform', 
            array(&$this, 'prefilterTransform'), $this->arrSelfInfo['priority']);
        
        $objHelperPlugin->addAction('LC_Page_Products_List_action_after', 
            array($this, 'productsListActionAfter'), $this->arrSelfInfo['priority']);
        $objHelperPlugin->addAction('LC_Page_Products_Detail_action_after', 
            array($this, 'productsDetailActionAfter'), $this->arrSelfInfo['priority']);

        $objHelperPlugin->addAction('LC_Page_Admin_Design_MainEdit_action_confirm', 
            array($this, 'adminDesignMainEditActionConfirm'), $this->arrSelfInfo['priority']);
        $objHelperPlugin->addAction('LC_Page_Admin_Design_MainEdit_action_after', 
            array($this, 'adminDesignMainEditActionAfter'), $this->arrSelfInfo['priority']);

        $objHelperPlugin->addAction('LC_Page_Admin_Design_action_after', 
            array($this, 'adminDesignActionAfter'), $this->arrSelfInfo['priority']);
        $objHelperPlugin->addAction('LC_Page_Admin_Products_Category_action_after', 
            array($this, 'adminProductsCategoryActionAfter'), $this->arrSelfInfo['priority']);
        $objHelperPlugin->addAction('LC_Page_Admin_Products_Product_action_after', 
            array($this, 'adminProductsProductActionAfter'), $this->arrSelfInfo['priority']);
        
        $objHelperPlugin->addAction('SC_FormParam_construct', 
            array($this, 'formParamConstruct'), $this->arrSelfInfo['priority']);
        
    }
}
?>
