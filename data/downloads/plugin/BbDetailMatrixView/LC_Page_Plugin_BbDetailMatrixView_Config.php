<?php
// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 商品詳細マトリクス表示の設定クラス
 */
class LC_Page_Plugin_BbDetailMatrixView_Config extends LC_Page_Admin_Ex {
    
    var $arrForm = array();

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR ."BbDetailMatrixView/templates/config.tpl";
        $this->tpl_subtitle = "商品詳細マトリクス表示 コンフィグ画面";
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
        
        $css_file_path = PLUGIN_HTML_REALDIR . "BbDetailMatrixView/media/BbDetailMatrixView.css";
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
                    $this->tpl_onload = "alert('登録が完了しました。');";
                }
            }
            break;
        default:
            // プラグイン情報を取得.
            $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("BbDetailMatrixView");
            $arrForm['free_field1'] = $plugin['free_field1'];
            $arrForm['free_field2'] = $plugin['free_field2'];
            $arrForm['free_field3'] = $plugin['free_field3'];
            $arrForm['free_field4'] = $plugin['free_field4'];
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
        $objFormParam->addParam('商品コード欄', 'free_field1', INT_LEN, 'n', array('EXIST_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('通常価格', 'free_field2', INT_LEN, 'n', array('EXIST_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ポイント', 'free_field3', INT_LEN, 'n', array('EXIST_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('数量', 'free_field4', INT_LEN, 'n', array('EXIST_CHECK','MAX_LENGTH_CHECK'));
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
        // UPDATEする値を作成する。
        $sqlval = array();
        $sqlval['free_field1'] = $arrData['free_field1'];
        $sqlval['free_field2'] = $arrData['free_field2'];
        $sqlval['free_field3'] = $arrData['free_field3'];
        $sqlval['free_field4'] = $arrData['free_field4'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = 'BbDetailMatrixView'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);
        $objQuery->commit();
        return $arrErr;
    }
}
?>
