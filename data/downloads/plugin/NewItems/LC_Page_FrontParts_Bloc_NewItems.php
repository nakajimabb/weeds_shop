<?php
// {{{ requires
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

/**
 * 新着商品ブロックのブロッククラス
 */
class LC_Page_FrontParts_Bloc_NewItems extends LC_Page_FrontParts_Bloc {

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
        $this->arrNewItems = $this->lfGetNewitems();
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
     * 新着商品の情報を取得
     *
     * @return array
     * @setcookie array
     */
    function lfGetNewitems(){
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objProduct = new SC_Product_Ex();


        // プラグイン情報を取得.
        $plugin     = SC_Plugin_Util_Ex::getPluginByPluginCode("NewItems");
        //表示条件
        $disp_rule  = is_numeric($plugin['free_field1']) ? $plugin['free_field1'] : 0;
        //表示件数
        $disp_count = unserialize($plugin['free_field2']);
        //表示ステータス
        $product_status = $plugin['free_field3'];

        //デバイスフラグ
       $disp_device == SC_Display_Ex::detectDevice();

        // 新着商品情報取得
        $col = 'product_id';
        $table = 'dtb_products';
        $where = 'status = 1 and del_flg = 0';

        if($product_status){
            $arrStatus = unserialize($product_status);
            if(is_array( $arrStatus )){
                $strstatus = '';
                foreach( $arrStatus as $status ){
                    if( $strstatus ) $strstatus .= ',';
                    $strstatus .= $status;
                }
                $where .= ' '
                        . 'AND product_id IN ('
                        . '    SELECT product_id FROM dtb_product_status WHERE product_status_id IN (' . $strstatus . ')'
                        . ')';
            }
        }
        switch($disp_rule) {
            case 1://登録日順
                $objQuery->setOrder('create_date desc');
            break;
            case 2://更新日順
                $objQuery->setOrder('update_date desc');
            break;
        }
        
        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_PC ) {
           $objQuery->setLimit($disp_count[10]);
        }elseif(SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE){
           $objQuery->setLimit($disp_count[1]);
        }elseif(SC_Display_Ex::detectDevice() == DEVICE_TYPE_SMARTPHONE ){
           $objQuery->setLimit($disp_count[2]);
        }
        $arrProducts = $objQuery->select($col, $table, $where);


        $objQuery =& SC_Query_Ex::getSingletonInstance();
        if (count($arrProducts) > 0) {

            $arrProductId = array();
            foreach ($arrProducts as $key => $val) {
                $arrProductId[] = $val['product_id'];
            }

            // 商品詳細情報取得
            $arrTmp = $objProduct->getListByProductIds($objQuery, $arrProductId);
            foreach ($arrTmp as $key => $arrRow) {
                $_row = $arrRow;
                $arrProductList[] = $_row;
            }
        }
        return $arrProductList;
    }

}
?>
