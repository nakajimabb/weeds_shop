<?php
/*
 * WPPost
 * Copyright (C) 2012 GIZMO CO.,LTD. All Rights Reserved.
 * http://www.gizmo.co.jp/
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

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
$plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("WpPost");
$wp_install_dir = $plugin['free_field1'];
require_once($_SERVER['DOCUMENT_ROOT'].$wp_install_dir.'/wp-load.php' );

/**
 * WordPressPost取得のブロッククラス
 *
 * @package WpPost
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class LC_Page_WpPost extends LC_Page_Ex {

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("WpPost");
        $wp_install_dir = $plugin['free_field1'];
        $this->wp_root = $wp_install_dir;

        $masterData                 = new SC_DB_MasterData_Ex();
        $this->arrSTATUS            = $masterData->getMasterData('mtb_status');
        $this->arrSTATUS_IMAGE      = $masterData->getMasterData('mtb_status_image');
        $this->arrDELIVERYDATE      = $masterData->getMasterData('mtb_delivery_date');
        $this->arrPRODUCTLISTMAX    = $masterData->getMasterData('mtb_product_list_max');
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

        if ($_GET["postid"]) {
            $postid = $_GET["postid"];
            query_posts("post_type=any&post_status=publish&p=$postid&showposts=1");
            $this->postid = $postid;
        }

        //カテゴリの取得
        $wp_cats = get_the_category($postid);
        $wp_cat_lists = array();
        foreach($wp_cats as $wp_cat){
            $wp_cat_lists[] = $this->cat_list($wp_cat->cat_ID); 
        }
        $this->cat_lists = $wp_cat_lists;

        //記事の取得
        $wp_post_base = get_post($postid, 'ARRAY_A');
            $wp_posts = array();
            //$wp_posts[$idx]["date"]=get_the_date();
            //$wp_posts[$idx]["title"]=the_title('','',false);
            //$wp_posts[$idx]["content"]=get_the_content();
            //$wp_posts[$idx]["meta"]=get_post_custom();

            $wp_posts["date"]=$wp_post_base["post_date"];//投稿日
            $wp_posts["title"]=$wp_post_base["post_title"];//タイトル
            $wp_posts["content"]=$wp_post_base["post_content"];//内容
            $wp_posts["comment_status"]=$wp_post_base["comment_status"];//コメント受け付け状況
            $wp_posts["comment_count"]=$wp_post_base["comment_count"];//コメント数

        $this->wp_post = $wp_posts;
        wp_reset_query();

        //コメントの取得
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $wppost_comment = $objQuery->select("*",plg_WpPost_comment);
        $wppost_comment = $wppost_comment[0];

        //ログイン判定
        $objCustomer = new SC_Customer();
        if($objCustomer->isLoginSuccess()) {
            $this->tpl_login = true;
        }
        //コメントにログイン必要か
        $this->wppost_comment_login = $wppost_comment["comment_login"];

        //EC-CUBE会員認証
        $this->wppost_comment_login_ec = $wppost_comment["comment_login_ec"];

        // Facebook認証
        if ($wppost_comment["comment_login_fb"] == 1){
            $this->wppost_comment_login_fb = $wppost_comment["comment_login_fb"];

            switch ($this->getMode()) {
                case 'fb_start':
                    $fb_url = "http://www.facebook.com/dialog/oauth?client_id=" . $wppost_comment["fb_appid"] . "&redirect_uri=". urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF'] ."?postid=" . $postid);
                    header('Location: ' . $fb_url);
                break;

                case 'fb_stop':
                    unset($_SESSION["fb_code"]);
                    $this->fb_auth = 0;
                break;

                default:
                    //Facebookのコールバックにトークンがある場合
                    $fb_code = $_REQUEST["code"];
                    if ($fb_code) {
                        $_SESSION["fb_code"] = $fb_code;
                        $this->fb_auth = 1;
                    } else {
                        if ($_SESSION["fb_code"]) {
                            $this->fb_auth = 1;
                        } else {
                            $this->fb_auth = 0;
                        }
                    }
                break;

            }

        }
        
        // Twitter認証
        if ($wppost_comment["comment_login_tw"] == 1){
            $this->wppost_comment_login_tw = $wppost_comment["comment_login_tw"];
            require_once 'twitteroauth/twitteroauth.php';

            switch ($this->getMode()) {
                case 'tw_start':
                    // TwitterのOAuth関係
                    $tw_consumer_key = $wppost_comment["tw_consumer_key"];
                    $tw_consumer_secret = $wppost_comment["tw_consumer_secret"];
                    $tw_oauth_callback = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF'] ."?postid=" . $postid . "&tw_status=start";
                    //OAuthトークンがなければ取得する
                    if (  empty($_SESSION['tw_oauth_token']) || empty($_SESSION['tw_oauth_token_secret']) || empty($_REQUEST['oauth_verifier']) ) {
                        /* 1.認証リクエストを行い、仮のトークンを取得する*/
                	    $con  = new TwitterOAuth($tw_consumer_key, $tw_consumer_secret);
                	    $request_token = $con->getRequestToken($tw_oauth_callback);
                	    // 仮のアクセストークンをセットする
                    	$_SESSION['tw_oauth_token'] = $token = $request_token['oauth_token'];
                	    $_SESSION['tw_oauth_token_secret'] = $request_token['oauth_token_secret'];

                	    switch ($con->http_code) {
                	        case 200:
                    	        /* 2. Twitter認証用のURLを取得し、ユーザの承認を得るページに遷移 */
                	            $url = $con->getAuthorizeURL($token);
                	            header('Location: ' . $url);
                	        break;
                	        default:
                    	         //HTTP ステータスが200でなければエラー
                	            $this->tpl_onload = "alert('接続に失敗しました。');";
                	        exit;
                        }
                    }
                break;

                case 'tw_stop':
                    $this->tw_auth = 0;
                    unset($_SESSION['tw_oauth_token']);
                    unset($_SESSION['tw_oauth_token_secret']);
                    unset($_SESSION['tw_access_token']);
                    unset($_SESSION['tw_auth_status']);
                break;

                default:

                	if ($_SESSION['tw_oauth_token'] && $_SESSION['tw_oauth_token_secret'] && empty($_SESSION['tw_access_token'])){
                	    /* 3. アクセストークン、トークンシークレット、ユーザ認証済みのパラメータがそろったので、コネクションを作成*/
                        $con = new TwitterOAuth($tw_consumer_key, $tw_consumer_secret, $_SESSION['tw_oauth_token'], $_SESSION['tw_oauth_token_secret']);
                        //ユーザが承認した印のverifier を取得して、正式のアクセストークンを取得する 
                        $tw_access_token = $con->getAccessToken($_REQUEST['oauth_verifier']);
                        //正式のアクセストークンをセッションにセットする
                        $_SESSION['tw_access_token'] = $tw_access_token;

                        /* 4. アクセストークンが取得できたらセッションにセットし、処理用ページにリダイレクト*/
                        if (200 == $con->http_code) {
                        /*後処理*/
                            $_SESSION['tw_auth_status'] = 'authed';
                            $this->tw_auth = 1;

                        /* HTTPのステータスコードが200でなければ */
                        } else {
                            /* エラーー*/
                            $this->tw_auth = 0;
                            $this->tpl_onload = "alert('認証に失敗しました。');";
                        }
                	}

                	//セッションが認証済みとなっていたら認証とする
                    if ($_SESSION['tw_auth_status'] == "authed"){
                        $this->tw_auth = 1;
                    } else {
                        $this->tw_auth = 0;
                    }

                break;
            }

        }

        //コメント表示
        //全体設定
        if ($wppost_comment["show_comment"] == 1){
            $this->wppost_comment_show = 1;
        } else {
            $this->wppost_comment_show = 0;
        }

        //ページ毎の設定
        if ($wp_posts["comment_status"] == "open") {
            $this->wppost_comment_show = 1;
        } else {
            $this->wppost_comment_show = 0;
        }

        if ($this->wppost_comment_show == 1){
            // コメント取得
            $comment_start_num = 0;
            //$comment_num = $wppost_comment["comment_num"];
            $wp_temp = array();
            $args = array(
                'post_id' => $postid,
                'status' => 'approve',
                'number' => $comment_num
            );

            $args_count = array(
                'post_id' => $postid,
                'status' => 'approve',
                'count' => true
            );

            // コメント取得
            $comments = get_comments($args);
            //表示コメント数
            $this->comment_num = $wppost_comment["comment_num"];

            $idc=0;
            foreach($comments as $comment) {
                $wp_temp[$idc]["comment_ID"] = $comment->comment_ID;
                $wp_temp[$idc]["comment_parent"] = $comment->comment_parent;
                $wp_temp[$idc]["comment_author"] = $comment->comment_author;
                $wp_temp[$idc]["comment_date"] = $comment->comment_date;
                $wp_temp[$idc]["comment_author_url"] = $comment->comment_author_url;
                $wp_temp[$idc]["comment_content"] = $comment->comment_content;
                $idc++;
            }
            // 配列並び変え準備
            foreach($wp_temp as $key => $row){
                $id[$key] = $row["comment_ID"];
                $parent[$key] = $row["comment_parent"];
                $date[$key] = $row["comment_date"];
            }

            //入れ子
            if ($wppost_comment["comment_format"] == 1){
				$this->wppost_comment_format = 1;
                //入れ子用並べ替え
                //並び順
                if ($wppost_comment["comment_turn"] == 1){
                    array_multisort($parent, SORT_ASC, $date, SORT_ASC, $wp_temp); //古いものから
                } else {
                    array_multisort($parent, SORT_ASC, $date, SORT_DESC, $wp_temp); //新着順
                }
                // 結果を格納する配列を用意
                $wp_commentlist = array();
                // 関数呼び出し（parent=0から開始）
                $this->search_and_push($wp_temp, $wp_commentlist, 0);
                $this->wp_commentlist = $wp_commentlist;
            //フラット
            } else {
                //並び順
                if ($wppost_comment["comment_turn"] == 1){
                    array_multisort($date, SORT_ASC, $wp_temp); //古いものから
                } else {
                    array_multisort($date, SORT_DESC, $wp_temp); //新着順
                }
                $this->wp_commentlist = $wp_temp;
            }
        }

        // contentからproduct_idを取得
        $src = mb_convert_kana($wp_posts["content"], "as"); //全角英数と全角スペースを半角に変換
        $src = str_replace("\ ", "", $src); //半角スペースを削除
        $pattern = '/products_id_list(.*?)products_id_list/';
        $result =  preg_match_all($pattern,$src,$dest,PREG_SET_ORDER);

        if($result!==0){

            $arrProductId_string = explode(",", $dest[0][1]);
            // 文字列を数値に変換
            foreach ($arrProductId_string as &$value) {
                $value = (int)$value;
                $arrProductId[] = $value;
            }
            unset($value); // 最後の要素への参照を解除

            $objProduct = new SC_Product_Ex();

            $this->arrForm = $_REQUEST;//時間が無いのでコレで勘弁してください。 tao_s
            //modeの取得
            $this->mode = $this->getMode();

            $urlParam           = "category_id={$this->arrSearchData['category_id']}&pageno=#page#";
            // モバイルの場合に検索条件をURLの引数に追加
            if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE) {
                $searchNameUrl = urlencode(mb_convert_encoding($this->arrSearchData['name'], 'SJIS-win', 'UTF-8'));
                $urlParam .= "&mode={$this->mode}&name={$searchNameUrl}&orderby={$this->orderby}";
            }
            $this->objNavi      = new SC_PageNavi_Ex($this->tpl_pageno, $this->tpl_linemax, $this->disp_number, 'fnNaviPage', NAVI_PMAX, $urlParam, SC_Display_Ex::detectDevice() !== DEVICE_TYPE_MOBILE);
            $this->arrProducts  = $this->lfGetProductsList($arrProductId, $objProduct);

            switch ($this->getMode()) {

                case 'json':
                    $this->arrProducts = $this->setStatusDataTo($this->arrProducts, $this->arrSTATUS, $this->arrSTATUS_IMAGE);
                    $this->arrProducts = $objProduct->setPriceTaxTo($this->arrProducts);

                    // 一覧メイン画像の指定が無い商品のための処理
                    foreach ($this->arrProducts as $key=>$val) {
                        $this->arrProducts[$key]['main_list_image'] = SC_Utils_Ex::sfNoImageMainList($val['main_list_image']);
                    }

                    echo SC_Utils_Ex::jsonEncode($this->arrProducts);
                    SC_Response_Ex::actionExit();
                    break;

                default:

                    //商品一覧の表示処理
                    $strnavi            = $this->objNavi->strnavi;
                    // 表示文字列
                    $this->tpl_strnavi  = empty($strnavi) ? '&nbsp;' : $strnavi;

                    // 規格1クラス名
                    $this->tpl_class_name1  = $objProduct->className1;

                    // 規格2クラス名
                    $this->tpl_class_name2  = $objProduct->className2;

                    // 規格1
                    $this->arrClassCat1     = $objProduct->classCats1;

                    // 規格1が設定されている
                    $this->tpl_classcat_find1 = $objProduct->classCat1_find;
                    // 規格2が設定されている
                    $this->tpl_classcat_find2 = $objProduct->classCat2_find;

                    $this->tpl_stock_find       = $objProduct->stock_find;
                    $this->tpl_product_class_id = $objProduct->product_class_id;
                    $this->tpl_product_type     = $objProduct->product_type;

                    // 商品ステータスを取得
                    $this->productStatus = $this->arrProducts['productStatus'];
                    unset($this->arrProducts['productStatus']);
                    $this->tpl_javascript .= 'var productsClassCategories = ' . SC_Utils_Ex::jsonEncode($objProduct->classCategories) . ';';
                    //onloadスクリプトを設定. 在庫ありの商品のみ出力する
                    foreach ($this->arrProducts as $arrProduct) {
                        if ($arrProduct['stock_unlimited_max'] || $arrProduct['stock_max'] > 0) {
                            $js_fnOnLoad .= "fnSetClassCategories(document.product_form{$arrProduct['product_id']});";
                        }
                    }

                    //カート処理
                    $target_product_id = intval($this->arrForm['product_id']);
                    if ($target_product_id > 0) {
                        // 商品IDの正当性チェック
                        if (!SC_Utils_Ex::sfIsInt($this->arrForm['product_id'])
                            || !SC_Helper_DB_Ex::sfIsRecord('dtb_products', 'product_id', $this->arrForm['product_id'], 'del_flg = 0 AND status = 1')) {
                            SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
                        }

                        // 入力内容のチェック
                        $arrErr = $this->lfCheckError($target_product_id, $this->arrForm, $this->tpl_classcat_find1, $this->tpl_classcat_find2);
                        if (empty($arrErr)) {
                            $this->lfAddCart($this->arrForm, $_SERVER['HTTP_REFERER']);

                            SC_Response_Ex::sendRedirect(CART_URLPATH);
                            SC_Response_Ex::actionExit();
                        }
                        $js_fnOnLoad .= $this->lfSetSelectedData($this->arrProducts, $this->arrForm, $arrErr, $target_product_id);
                    } else {
                        // カート「戻るボタン」用に保持
                        $netURL = new Net_URL();
                        //該当メソッドが無いため、$_SESSIONに直接セット
                        $_SESSION['cart_referer_url'] = $netURL->getURL();
                    }

                    $this->tpl_javascript   .= 'function fnOnLoad(){' . $js_fnOnLoad . '}';
                    $this->tpl_onload       .= 'fnOnLoad(); ';
                    break;
            }

            $this->tpl_rnd = SC_Utils_Ex::sfGetRandomString(3);

        }

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }


    //カテゴリー抽出
    function cat_list( $id, $nicename = false, $visited = array() ) {
        $chain = array();
        $chain_end = array();

        $parent = &get_category( $id );
        if ( is_wp_error( $parent ) )
            return $parent;

        if ( $nicename )
            $name = $parent->slug;
        else
            $name = $parent->name;

        if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
            $visited[] = $parent->parent;

             $chain = $this->cat_list( $parent->parent, $link, $separator, $nicename, $visited );

        }

        $chain[] =  array("catid"=>$parent->term_id, "cat_title"=>esc_attr( sprintf( __( "View all posts in %s" ), $parent->name ) ), "cat_name"=>$name);

        return $chain;

    }
    


    // コメント抽出関数
    function search_and_push(&$arg1, &$arg2, $arg3) {
        for ($i = 0; $i < count($arg1); $i++) {
            $val = $arg1[$i];
            if ($val["comment_parent"] <> $arg3) { continue; }
            array_push($arg2, $val);
            $this->search_and_push($arg1, $arg2, $val["comment_ID"]);
        }
    }

    /* 商品一覧の表示 */
    function lfGetProductsList($arrProductId, &$objProduct) {

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrProducts = $objProduct->getListByProductIds($objQuery, $arrProductId);

        // 規格を設定
        $objProduct->setProductsClassByProductIds($arrProductId);
        $arrProducts['productStatus'] = $objProduct->getProductStatus($arrProductId);
        return $arrProducts;
    }

    /* 入力内容のチェック */
    function lfCheckError($product_id, &$arrForm, $tpl_classcat_find1, $tpl_classcat_find2) {

        // 入力データを渡す。
        $objErr = new SC_CheckError_Ex($arrForm);

        // 複数項目チェック
        if ($tpl_classcat_find1[$product_id]) {
            $objErr->doFunc(array('規格1', 'classcategory_id1', INT_LEN), array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        }
        if ($tpl_classcat_find2[$product_id]) {
            $objErr->doFunc(array('規格2', 'classcategory_id2', INT_LEN), array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        }

        $objErr->doFunc(array('商品規格ID', 'product_class_id', INT_LEN), array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objErr->doFunc(array('数量', 'quantity', INT_LEN), array('EXIST_CHECK', 'ZERO_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));

        return $objErr->arrErr;
    }

    /**
     * カートに入れる商品情報にエラーがあったら戻す
     *
     * @return str
     */
    function lfSetSelectedData(&$arrProducts, $arrForm, $arrErr, $product_id) {
        $js_fnOnLoad = '';
        foreach ($arrProducts as $key => $value) {
            if ($arrProducts[$key]['product_id'] == $product_id) {

                $arrProducts[$key]['product_class_id']  = $arrForm['product_class_id'];
                $arrProducts[$key]['classcategory_id1'] = $arrForm['classcategory_id1'];
                $arrProducts[$key]['classcategory_id2'] = $arrForm['classcategory_id2'];
                $arrProducts[$key]['quantity']          = $arrForm['quantity'];
                $arrProducts[$key]['arrErr']            = $arrErr;
                $js_fnOnLoad .= "fnSetClassCategories(document.product_form{$arrProducts[$key]['product_id']}, '{$arrForm['classcategory_id2']}');";
            }
        }
        return $js_fnOnLoad;
    }

    /**
     * カートに商品を追加
     *
     * @return void
     */
    function lfAddCart($arrForm, $referer) {
        $product_class_id = $arrForm['product_class_id'];
        $objCartSess = new SC_CartSession_Ex();
        $objCartSess->addProduct($product_class_id, $arrForm['quantity']);
    }

    /**
     * 商品情報配列に商品ステータス情報を追加する
     *
     * @param Array $arrProducts 商品一覧情報
     * @param Array $arrStatus 商品ステータス配列
     * @param Array $arrStatusImage スタータス画像配列
     * @return Array $arrProducts 商品一覧情報
     */
    function setStatusDataTo($arrProducts, $arrStatus, $arrStatusImage) {

        foreach ($arrProducts['productStatus'] as $product_id => $arrValues) {
            for ($i = 0; $i < count($arrValues); $i++) {
                $product_status_id = $arrValues[$i];
                if (!empty($product_status_id)) {
                    $arrProductStatus = array(
                        'status_cd' => $product_status_id,
                        'status_name' => $arrStatus[$product_status_id],
                        'status_image' =>$arrStatusImage[$product_status_id],
                    );
                    $arrProducts['productStatus'][$product_id][$i] = $arrProductStatus;
                }
            }
        }
        return $arrProducts;
    }

}
?>
