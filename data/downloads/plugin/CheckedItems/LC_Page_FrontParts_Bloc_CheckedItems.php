<?php
// {{{ requires
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

/**
 * 最近チェックした商品ブロックのブロッククラス
 */
class LC_Page_FrontParts_Bloc_CheckedItems extends LC_Page_FrontParts_Bloc {

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
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
        //商品情報取得
        $this->arrCheckItems = $this->getItemList();
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
     * 最近チェック商品の情報を取得
     *
     * @return array
     * @setcookie array
     */
    function getItemList() {
        $cnt = 0;
        $arrItemList = array();

        // プラグイン情報を取得.
        $plugin     = SC_Plugin_Util_Ex::getPluginByPluginCode("CheckedItems");
        //保存件数
        $save_count = is_numeric($plugin['free_field2']) ? $plugin['free_field2'] : 0;

        // ページを再読み込み後に表示
        if (isset($_COOKIE['product'])) {
            $arrItem = $_COOKIE['product'];

            //順番を変更
            $arrItem = array_reverse($arrItem);
            foreach ($arrItem as $name => $value) {
                $objQuery = new SC_Query();
                $objProduct = new SC_Product_Ex();

                // 商品情報取得
                $arrRet = $objProduct->getDetail($value);
                $arrItemList[$cnt] = $arrRet;
                $cnt = $cnt+1;
                if( $save_count <= $cnt ) break;
            }
            return $arrItemList;
        }
    }

}
?>
