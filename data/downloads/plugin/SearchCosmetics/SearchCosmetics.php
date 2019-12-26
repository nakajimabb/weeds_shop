<?php

class SearchCosmetics extends SC_Plugin_Base {

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

        // プラグイン
        if(copy(PLUGIN_UPLOAD_REALDIR . "SearchCosmetics/logo.png", PLUGIN_HTML_REALDIR . "SearchCosmetics/logo.png") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "SearchCosmetics/cosmetic.php", HTML_REALDIR . "products/cosmetic.php") === false);
        // if(copy(PLUGIN_UPLOAD_REALDIR . "SearchCosmetics/SearchCosmetics.php", PLUGIN_HTML_REALDIR . "SearchCosmetics/SearchCosmetics.php") === false);

        // ブロック
        if(copy(PLUGIN_UPLOAD_REALDIR . "SearchCosmetics/templates/default/plg_search_cosmetics.tpl", TEMPLATE_REALDIR . "frontparts/bloc/plg_search_cosmetics.tpl") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "SearchCosmetics/templates/sphone/plg_search_cosmetics.tpl", SMARTY_TEMPLATES_REALDIR . SMARTPHONE_DEFAULT_TEMPLATE_NAME . "/frontparts/bloc/plg_search_cosmetics.tpl") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "SearchCosmetics/bloc/plg_search_cosmetics.php", HTML_REALDIR . "frontparts/bloc/plg_search_cosmetics.php") === false);

        // ブロック登録
        SearchCosmetics::registDB($arrPlugin);
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

        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR . "products/cosmetic.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR  . "frontparts/bloc/plg_search_cosmetics.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "frontparts/bloc/plg_search_cosmetics.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(SMARTY_TEMPLATES_REALDIR . SMARTPHONE_DEFAULT_TEMPLATE_NAME . "/frontparts/bloc/plg_search_cosmetics.tpl") === false);

        // ブロック削除
        SearchCosmetics::deleteDB($arrPlugin);
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

    function registDB($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        // PCにdtb_blocにブロックを追加する.
        $sqlval_bloc = array();
        $sqlval_bloc['device_type_id'] = DEVICE_TYPE_PC;
        $sqlval_bloc['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = " . DEVICE_TYPE_PC) + 1;
        $sqlval_bloc['bloc_name'] = $arrPlugin['plugin_name'];
        $sqlval_bloc['tpl_path'] = "plg_search_cosmetics.tpl";
        $sqlval_bloc['filename'] = "plg_search_cosmetics";
        $sqlval_bloc['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc['update_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc['php_path'] = "frontparts/bloc/plg_search_cosmetics.php";
        $sqlval_bloc['deletable_flg'] = 0;
        $sqlval_bloc['plugin_id'] = $arrPlugin['plugin_id'];
        $objQuery->insert("dtb_bloc", $sqlval_bloc);

        // スマートフォンdtb_blocにブロックを追加する.
        $sqlval_bloc = array();
        $sqlval_bloc['device_type_id'] = DEVICE_TYPE_SMARTPHONE;
        $sqlval_bloc['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = " . DEVICE_TYPE_SMARTPHONE) + 1;
        $sqlval_bloc['bloc_name'] = $arrPlugin['plugin_name'];
        $sqlval_bloc['tpl_path'] = "plg_search_cosmetics.tpl";
        $sqlval_bloc['filename'] = "plg_search_cosmetics";
        $sqlval_bloc['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc['update_date'] = "CURRENT_TIMESTAMP";
        $sqlval_bloc['php_path'] = "frontparts/bloc/plg_search_cosmetics.php";
        $sqlval_bloc['deletable_flg'] = 0;
        $sqlval_bloc['plugin_id'] = $arrPlugin['plugin_id'];
        $objQuery->insert("dtb_bloc", $sqlval_bloc);

        // dtb_pagelayoutにページを追加する.
        $sqlval_page = array();
        $sqlval_page['device_type_id'] = DEVICE_TYPE_PC;
        $sqlval_page['page_id'] = $objQuery->max('page_id', "dtb_pagelayout", "device_type_id = " . DEVICE_TYPE_PC) + 1;
        $sqlval_page['page_name'] = "化粧品ページ";
        $sqlval_page['url'] = "products/cosmetic.php";
        $sqlval_page['filename'] = "products/cosmetic";
        $sqlval_page['header_chk'] = "1";
        $sqlval_page['footer_chk'] = "1";
        $sqlval_page['edit_flg'] = "2";
        $sqlval_page['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_page['update_date'] = "CURRENT_TIMESTAMP";
        $objQuery->insert("dtb_pagelayout", $sqlval_page);

        // dtb_pagelayoutにページを追加する.
        $sqlval_page = array();
        $sqlval_page['device_type_id'] = DEVICE_TYPE_SMARTPHONE;
        $sqlval_page['page_id'] = $objQuery->max('page_id', "dtb_pagelayout", "device_type_id = " . DEVICE_TYPE_SMARTPHONE) + 1;
        $sqlval_page['page_name'] = "化粧品ページ";
        $sqlval_page['url'] = "products/cosmetic.php";
        $sqlval_page['filename'] = "products/cosmetic";
        $sqlval_page['header_chk'] = "1";
        $sqlval_page['footer_chk'] = "1";
        $sqlval_page['edit_flg'] = "2";
        $sqlval_page['create_date'] = "CURRENT_TIMESTAMP";
        $sqlval_page['update_date'] = "CURRENT_TIMESTAMP";
        $objQuery->insert("dtb_pagelayout", $sqlval_page);
    }

    function deleteDB($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $arrBlocId = $objQuery->getCol('bloc_id', "dtb_bloc", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_PC , "plg_search_cosmetics"));
        $bloc_id = (int) $arrBlocId[0];

        // ブロックを削除する.（PC）
        $where = "bloc_id = ? AND device_type_id = ?";
        $objQuery->delete("dtb_bloc", $where, array($bloc_id,DEVICE_TYPE_PC));
        $objQuery->delete("dtb_blocposition", $where, array($bloc_id,DEVICE_TYPE_PC));

        $arrBlocId = $objQuery->getCol('bloc_id', "dtb_bloc", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_SMARTPHONE , "plg_search_cosmetics"));
        $bloc_id = (int) $arrBlocId[0];

        // ブロックを削除する.（スマートフォン）
        $where = "bloc_id = ? AND device_type_id = ?";
        $objQuery->delete("dtb_bloc", $where, array($bloc_id,DEVICE_TYPE_SMARTPHONE));
        $objQuery->delete("dtb_blocposition", $where, array($bloc_id,DEVICE_TYPE_SMARTPHONE));

        //dtb_pagelayoutページの削除
        $arrPageIdCat = $objQuery->getCol('page_id', "dtb_pagelayout", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_PC , "products/cosmetic"));
        $page_id = (int) $arrPageIdCat[0];
        $where = "page_id = ?";
        $objQuery->delete("dtb_pagelayout", $where, array($page_id));
    }
}
?>