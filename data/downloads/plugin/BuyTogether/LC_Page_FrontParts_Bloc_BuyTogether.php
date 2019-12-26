<?php
// {{{ requires
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

/**
 * よく一緒に購入されている商品ブロックのブロッククラス
 */
class LC_Page_FrontParts_Bloc_BuyTogether extends LC_Page_FrontParts_Bloc {

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
    
        $arrRequest   = $_GET;
        $product_id   = '';
        $arrBuyTogether = array();

        // 商品ID取得
        if (isset($arrRequest['product_id']) && $arrRequest['product_id'] != '' && is_numeric($arrRequest['product_id'])) {
            $product_id = $arrRequest['product_id'];
        }
        
        if( $product_id ){
            $this->is_disp = true;
            $arrBuyTogether = $this->getItemList($product_id);
        }
        
        //商品情報取得
        $this->arrBuyTogether = $arrBuyTogether;
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
     * 商品の情報を取得
     *
     * @return array
     * @setcookie array
     */
    function getItemList($product_id) {
    
        $arrProductIds = $this->getProductIds($product_id);
        $arrItems = array();
        $cnt = 0;
        
        foreach ($arrProductIds as $name => $value) {
            $objQuery = new SC_Query();
            $objProduct = new SC_Product_Ex();
            // 商品情報取得
            $arrRet = $objProduct->getDetail($value);
            $arrItems[$cnt] = $arrRet;
            $cnt = $cnt+1;
        }

        return $arrItems;
    }

    function getProductIds($product_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $cnt = 0;
        $arrItemList = array();

        // プラグイン情報を取得.
        $plugin     = SC_Plugin_Util_Ex::getPluginByPluginCode("BuyTogether");
        //検索対象期間
        $search_date = is_numeric($plugin['free_field1']) ? $plugin['free_field1'] : 120;
        
        $disp_limit  = 0;
        if( $plugin['free_field2'] ){
            $arrDispLimit = unserialize($plugin['free_field2']);
        }
        switch( SC_Display_Ex::detectDevice() ){
            case DEVICE_TYPE_MOBILE :
                $disp_limit = $arrDispLimit['mb'] ? $arrDispLimit['mb'] : 2;
            break;
            case DEVICE_TYPE_SMARTPHONE :
                $disp_limit = $arrDispLimit['sp'] ? $arrDispLimit['sp'] : 3;
            break;
            default:
                $disp_limit = $arrDispLimit['pc'] ? $arrDispLimit['pc'] : 6;
            break;
        }
        
        $order_count = is_numeric($plugin['free_field3']) ? $plugin['free_field3'] : 0;
        
        // 検索対象日付算出
        $today = date('Ymd');
        $sdate = date('Y-m-d 00:00:00', strtotime( $today . '-'. $search_date . ' day' ) );        

        // 検索対象のorder_id 取得
        $arrWhereVal = array();
        $col   = 't1.order_id';
        $from  = '(dtb_order t1 LEFT JOIN dtb_order_detail t2 ON t1.order_id = t2.order_id)';
        $where = 't1.del_flg=0 AND t1.create_date >= ? AND t2.product_id = ? ';

        $arrWhereVal[] = $sdate;
        $arrWhereVal[] = $product_id;
        
        $objQuery->setGroupBy('t1.order_id');
        $innerSql = $objQuery->getSql($col, $from, $where, $arrWhereVal);
        
        $col   = 't3.product_id, count(t3.product_id) as cnt';
        $from  = '(dtb_products t3 LEFT JOIN dtb_order_detail t4 ON t3.product_id = t4.product_id AND t3.del_flg=0 AND t3.status=1)';
        $where = 't3.product_id <> \'' . $product_id . '\' AND order_id IN (' . $innerSql . ')';
        
        $objQuery->setGroupBy('t3.product_id');
        $objQuery->setOrder('cnt DESC');
        $objQuery->setLimit($disp_limit);
        
        $res = $objQuery->select($col, $from, $where, $arrWhereVal);
        
        $arrProducts = array();
        if( is_array( $res ) ){
            foreach( $res AS $row ){
                if( $row['product_id'] && $row['cnt'] >= $order_count){
                    $arrProducts[] = $row['product_id'];
                }
            }
        }
        
        return $arrProducts;

    }

}
?>
