
<?php
// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/frontparts/bloc/LC_Page_FrontParts_Bloc_Ex.php';

class LC_Page_FrontParts_Bloc_SearchCosmetics extends LC_Page_FrontParts_Bloc_Ex {

    // {{{ properties

    var $treeCategories = array();  // naka
    var $CosmeticID;                // naka
    var $searchMode = 1;            // naka

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
    }

    /**
     * Page のプロセス.
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

        // <-- naka
        // ブランド検索用リスト
        $this->lfGetBrandList($maker_id, $this->arrBrandList, $this->arrBrandInfo);
        
        $this->lfGetTreeCategories($this->treeCategories, true);
        $this->CosmeticID = $this->lfGetCategoryIDByName($this->treeCategories, '化粧品');

        // 商品ID取得
        $product_id = $this->lfGetProductId();
        // カテゴリID取得
        $category_id = $this->lfGetCategoryId();
        // メーカーID取得
        $maker_id = $this->lfGetMakerId();

        // 選択中のカテゴリIDを判定する
        $this->category_id = $this->lfGetSelectedCategoryId($product_id, $category_id);
        // カテゴリ検索用選択リスト
        $this->arrCatList = $this->lfGetCategoryList();
        // 選択中のメーカーIDを判定する
        $this->maker_id = $this->lfGetSelectedMakerId($product_id, $maker_id);
        // メーカー検索用選択リスト
        list($this->arrMakerInfo, $this->arrMakerList) = $this->lfGetMakerInfo();

        $mode = $this->getMode();

        if(isset($_GET['srch']))
            $this->searchMode =  $_GET['srch'];
        // <-- naka
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
     * 商品IDを取得する.
     *
     * @return string $product_id 商品ID
     */
    function lfGetProductId() {
        $product_id = '';
        if (isset($_GET['product_id']) && $_GET['product_id'] != '' && is_numeric($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
        }
        return $product_id;
    }

    /**
     * カテゴリIDを取得する.
     *
     * @return string $category_id カテゴリID
     */
    function lfGetCategoryId() {
        $category_id = '';
        if (isset($_GET['category_id']) && $_GET['category_id'] != '' && is_numeric($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
        }
        return $category_id;
    }

    /**
     * メーカーIDを取得する.
     *
     * @return string $maker_id メーカーID
     */
    function lfGetMakerId() {
        $maker_id = '';
        if (isset($_GET['maker_id']) && $_GET['maker_id'] != '' && is_numeric($_GET['maker_id'])) {
            $maker_id = $_GET['maker_id'];
        }
        return $maker_id;
    }

    /**
     * 選択中のカテゴリIDを取得する
     *
     * @return array $arrCategoryId 選択中のカテゴリID
     */
    function lfGetSelectedCategoryId($product_id, $category_id) {
        // 選択中のカテゴリIDを判定する
        $objDb = new SC_Helper_DB_Ex();
        $arrCategoryId = $objDb->sfGetCategoryId($product_id, $category_id);
        return $arrCategoryId;
    }

    /**
     * 選択中のメーカーIDを取得する
     *
     * @return array $arrMakerId 選択中のメーカーID
     */
    function lfGetSelectedMakerId($product_id, $maker_id) {
        // 選択中のメーカーIDを判定する
        $objDb = new SC_Helper_DB_Ex();
        $arrMakerId = $objDb->sfGetMakerId($product_id, $maker_id);
        return $arrMakerId;
    }

    /**
     * カテゴリ検索用選択リストを取得する
     *
     * @return array $arrCategoryList カテゴリ検索用選択リスト
     */
    // --> naka
    /*
    function lfGetCategoryList() {
        $objDb = new SC_Helper_DB_Ex();
        // カテゴリ検索用選択リスト
        $arrCategoryList = $objDb->sfGetCategoryList('', true, '　');
        if (is_array($arrCategoryList)) {
            // 文字サイズを制限する
            foreach ($arrCategoryList as $key => $val) {
                $truncate_str = SC_Utils_Ex::sfCutString($val, SEARCH_CATEGORY_LEN, false);
                $arrCategoryList[$key] = preg_replace('/　/u', '&nbsp;&nbsp;', $truncate_str);
            }
        }
        return $arrCategoryList;

         }
     */
    function lfGetCategoryList() {

        foreach($this->treeCategories[$this->CosmeticID]['child_id'] as $key=>$val) {
            $arrCategoryList[$val] = $this->treeCategories[$val]['category_name'];
        }
        return $arrCategoryList;
    }
    // <-- naka
    

    /**
     * メーカー検索用選択リストを取得する
     *
     * @return array $arrMakerInfo メーカー検索用選択リスト
     */
    function lfGetMakerInfo() {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col        = '*';
        $from       = 'dtb_maker';
        $result = $objQuery->select($col, $from);
        
        $arrMakerInfo = array();
        $arrMakerList = array();
        foreach($result as $key=>$val) {

            $maker_id = $val['maker_id'];
            $arrMakerInfo[$maker_id] = $val;
            $arrMakerList[$maker_id] = $val['name'];
        }
        
        return array($arrMakerInfo, $arrMakerList);
    }

    // --> naka
    /*
    function lfGetBrandList() {
        $objDb = new SC_Helper_DB_Ex();
        // ブランド検索リスト
        $arrBrandList = $objDb->sfGetBrandList('', true);
        if (is_array($arrBrandList)) {
            // 文字サイズを制限する
            foreach ($arrBrandList as $key => $val) {
                $arrBrandList[$key] = SC_Utils_Ex::sfCutString($val, SEARCH_CATEGORY_LEN, false);
            }
        }
        return $arrBrandList;
    }
    */

    function lfGetBrandList($maker_id, &$arrBrandList, &$arrBrandInfo) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = '';

        //$objQuery->setOption('ORDER BY rank DESC');
        
        $col = '*';
        $from = 'dtb_brand';

        $objQuery->setOrder('rank ASC');
        $arrRet = $objQuery->select($col, $from, $where);

        $max = count($arrRet);
        
        $arrBrandList = array();
        $arrBrandInfo = array();

        for ($cnt = 0; $cnt < $max; $cnt++) {
            $maker_id2 = $arrRet[$cnt]['maker_id'];
            $brand_id = $arrRet[$cnt]['brand_id'];
            
            $arrBrandInfo[$maker_id2][$brand_id]['name']          = $arrRet[$cnt]['name'];
            $arrBrandInfo[$maker_id2][$brand_id]['brand_image']   = $arrRet[$cnt]['brand_image'];
            
            if($maker_id == $maker_id2) {
                $arrBrandList[$brand_id] = $arrRet[$cnt]['name'];
            }
        }
    }
    
    function lfGetTreeCategories(&$tree_categories, $products_check = true) {
        // 商品が属するカテゴリ(level=2)のセット
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $where = 'del_flg = 0';
        $objQuery->setOption('ORDER BY category_id ASC');

        if ($products_check) {
            $col = 'T1.category_id, category_name, level, parent_category_id';
            $from = 'dtb_category AS T1 LEFT JOIN dtb_category_total_count AS T2 ON T1.category_id = T2.category_id';
            $where .= ' AND product_count > 0';
        }
        else {
            $col = 'category_id, category_name, level, parent_category_id';
            $from = 'dtb_category';
        }
        
        $arrTemp = $objQuery->select($col, $from, $where);

        foreach($arrTemp as $value) {
            $cate_id    = $value['category_id'];
            $parent_id  = $value['parent_category_id'];

            $tree_categories[$cate_id]['category_name']      = $value['category_name'];
            $tree_categories[$cate_id]['level']              = $value['level'];
            $tree_categories[$cate_id]['parent_category_id'] = $value['parent_category_id'];

            $tree_categories[$parent_id]['child_id'][]       = $cate_id;
            
        }
    }

    // naka
    function lfGetCategoryIDByName(&$tree_categories, $category_name, $level = 1) {

        foreach($tree_categories as $key=>$value) {

            if($value['category_name'] === $category_name && (int)$value['level'] === $level) {
                return (int)$key;
            }
        }
    }
    
    // <-- naka
}
?>
