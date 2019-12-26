<?php
class BbDetailMatrixView extends SC_Plugin_Base {

    /**
     * 
    **/
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    /**
     * installはプラグインのインストール時に実行されます。[必須]
    **/
    function install($arrPlugin) {
        if(copy(PLUGIN_UPLOAD_REALDIR . "BbDetailMatrixView/logo.png", PLUGIN_HTML_REALDIR . "BbDetailMatrixView/logo.png") === false);

        //ディレクトリコピー関数
        //ToDo::$thisでの呼び出しが出来なかったので内部で関数化
        function copyDirectory($imageDir, $destDir){
            $handle=opendir($imageDir);
            while($filename=readdir($handle)){
                if(strcmp($filename,".")!=0 && strcmp($filename,"..")!=0){
                    if(is_dir("$imageDir/$filename")){
                        if(!empty($filename) && !file_exists("$destDir/$filename")){
                            mkdir("$destDir/$filename");
                        }
                        copyDirectory("$imageDir/$filename","$destDir/$filename");
                    }else{
                        if(file_exists("$destDir/$filename")){
                            unlink("$destDir/$filename");
                        }
                        copy("$imageDir/$filename","$destDir/$filename");
                    }
                }
            }
        }

        //Media用ディレクトリのコピー
        mkdir(PLUGIN_HTML_REALDIR . "BbDetailMatrixView/media");
        if(copyDirectory(PLUGIN_UPLOAD_REALDIR . "BbDetailMatrixView/media", PLUGIN_HTML_REALDIR . "BbDetailMatrixView/media") === false);

    }

    /**
     * uninstallはアンインストール時に実行されます。[必須]
    **/
    function uninstall($arrPlugin) {
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "BbDetailMatrixView") === false);
    }

    /**
     * updateはアップデート時に実行されます。[必須]
    **/
    function update($arrPlugin) {
        // nop
    }

    /**
     * enableはプラグインを有効にした際に実行されます。
    **/
    function enable($arrPlugin) {
        // nop
    }

    /**
     * disableはプラグインを無効にした際に実行されます。
    **/
    function disable($arrPlugin) {
        // nop
    }

    /**
     * registはプラグインインスタンス生成時に実行されます。フックポイントの登録はここで行います。
    **/
    function register(SC_Helper_Plugin $objHelperPlugin) {
        $template_dir = PLUGIN_UPLOAD_REALDIR . 'BbDetailMatrixView/templates/';
        $objHelperPlugin->setHeadNavi($template_dir . 'header.tpl');
        $objHelperPlugin->addAction('LC_Page_Products_Detail_action_after', array(&$this, 'MatrixMasterFunction'));
        $objHelperPlugin->addAction('prefilterTransform', array(&$this, 'prefilterTransform'),1);
    }

/*****************/

    /**
     *商品詳細ページ処理の最後に実行される
     *HookPoint:LC_Page_Products_Detail_action_after
    **/
    function MatrixMasterFunction($objPage) {

        //商品IDに紐尽く商品詳細を全て取得する
        $objPage->arrMatrixProducts = $this->getMatrixProducts($objPage->arrProduct['product_id']);
        //規格の無い商品の品切れ表示を無効化 @@書き換わってしまうので
        $objPage->tpl_stock_find = 1;
        
        $objPage->tpl_plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("BbDetailMatrixView");

    }

    /**
     *商品IDに紐尽く商品詳細を全て取得する
    **/
    function getMatrixProducts($product_id) {

        $objProduct = new SC_Product_Ex();
        $arrMatrixProducts = $objProduct->getProductsClassFullByProductId($product_id);

        foreach($arrMatrixProducts as $key => $row){
            $rank2[$key] = $row["rank2"];
            $rank1[$key] = $row["rank1"];
        }
        array_multisort($rank1,SORT_DESC,$rank2,SORT_DESC,$arrMatrixProducts);

        return $arrMatrixProducts;

    }

    /**
     * テンプレートコンパイル時に呼び出される関数
    **/
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . 'BbDetailMatrixView/templates/';
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
                break;
            case DEVICE_TYPE_PC:
                //商品詳細ページを変更
                if (strpos($filename, 'products/detail.tpl') !== false) {
                    //デフォルトの商品コード欄を非表示
                    //$objTransform->select('dl.product_code')->removeElement();
                    //デフォルトの通常価格欄を非表示
                    //$objTransform->select('dl.normal_price')->removeElement();
                    //デフォルトの販売価格欄を非表示
                    //$objTransform->select('dl.sale_price')->removeElement();
                    //デフォルトのポイント欄を非表示
                    $objTransform->select('div.point')->removeElement();
                    //デフォルトの規格欄を非表示
                    $objTransform->select('div.classlist')->removeElement();
                    //デフォルトの数量欄を非表示
                    $objTransform->select('dl.quantity')->removeElement();
                    //デフォルトのカート欄を非表示
                    $objTransform->select('div.cart_area')->removeElement();
                    //デフォルトのカート欄を置き換える
                    $objTransform->select('form#form1')->appendChild(file_get_contents($template_dir . 'detail_matrix_view.tpl'));
                }
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                break;
        }
        $source = $objTransform->getHTML();
    }
}
?>
