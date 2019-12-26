<?php

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

 // ブランド登録 のページクラス.
class LC_Page_Admin_System_TestCode extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . 'TestCode/templates/test_code.tpl';
        $this->tpl_subno = 'test_code';
        $this->tpl_maintitle = 'システム設定';
        $this->tpl_subtitle = 'テストコード';
        $this->tpl_mainno = 'system';
    }

    function process() {
        $this->action();
        $this->sendResponse();
    }

    function action() {

        // モードによる処理切り替え
        switch ($this->getMode()) {

            // 編集処理
            case 'exec':
               // $this->BatchProcProduct();
                break;

            default:
                break;
        }
    }

    function destroy() {
        parent::destroy();
    }

    function ExecTestCode() {
        SC_Utils::sfPrintR('test code here');
    }

    function BatchProcProduct() {
        $cate_id = 196;
        $objDb = new SC_Helper_DB_Ex();
        list($tmp_where, $arrTmp) = $objDb->sfGetCatWhere($cate_id);

        $where = 'product_id IN (SELECT product_id FROM dtb_product_categories WHERE ' . $tmp_where . ')';

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $sqlval['status'] = '2';
        $arrProducts = $objQuery->update('dtb_products', $sqlval, $where, $arrTmp);

        SC_Utils::sfPrintR(count($arrProducts));

        // SC_Utils::sfPrintR($tmp_where);
        // SC_Utils::sfPrintR($arrTmp);
    }
}
