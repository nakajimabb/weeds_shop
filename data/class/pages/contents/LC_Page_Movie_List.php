<?php

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * ユーザーカスタマイズ用のページクラス
 *
 * 管理画面から自動生成される
 *
 * @package Page
 */
class LC_Page_Movie_List extends LC_Page_Ex {

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_title = '動画紹介';
        $this->tpl_mainpage = 'contents/plg_intro_movie.tpl';
        $this->tpl_mainno = '';
        
        $this->arrMovie = array();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $objCustomer = new SC_Customer();
        if ($objCustomer->isLoginSuccess(true)) {
            $this->arrMovie = $this->lfGetMovie();
        } else {
            $url = "/mypage/login.php";
            SC_Response_Ex::sendRedirect($this->getLocation($url));
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
    
    function lfGetMovie() {

        $objQuery = SC_Query_Ex::getSingletonInstance();

        $cols   = '*';
        $from  = 'dtb_movie';
        $where = 'status = 1';
        $arrWhereVal = array();
        $objQuery->setOrder('rank ASC');
        // $objQuery->setLimit(3);

        $result = $objQuery->select($cols, $from, $where);
        
        return $result;
    }
}

