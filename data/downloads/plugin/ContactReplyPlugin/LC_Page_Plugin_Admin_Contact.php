<?php
// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';
// }}}

define(TPL_LIST_PATH, PLUGIN_UPLOAD_REALDIR . 'ContactReplyPlugin/templates/admin_contact.tpl');
define(TPL_CONFIRM_PATH, PLUGIN_UPLOAD_REALDIR . 'ContactReplyPlugin/templates/admin_contact_confirm.tpl');
define(TPL_DETAIL_PATH, PLUGIN_UPLOAD_REALDIR . 'ContactReplyPlugin/templates/admin_contact_detail.tpl');

/**
 * パンくずブロックの設定クラス
 *
 * @package SocialButton
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class LC_Page_Plugin_Admin_Contact extends LC_Page_Admin_Ex {

    var $arrForm = array();

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        if ($_GET['contact_id']) {
            if ($_POST['mode'] == 'send_confirm') {
                $this->tpl_mainpage = TPL_CONFIRM_PATH;
                $this->tpl_subtitle = "お問い合わせ返信確認";
            } else {
                $this->tpl_mainpage = TPL_DETAIL_PATH;
                $this->tpl_subtitle = "お問い合わせ詳細";
            }
        } else {
            $this->tpl_mainpage = TPL_LIST_PATH;
            $this->tpl_subtitle = "お問い合わせ一覧";
        }
        $this->tpl_mainno = 'customer';
        $this->tpl_subno = 'index';
        $this->tpl_maintitle = 'お問い合わせ管理';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrCONTACTSTATUS = $masterData->getMasterData("mtb_plg_ContactReply_status");
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

        $objView = new SC_AdminView();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $masterData = new SC_DB_MasterData_Ex();

        // 認証可否の判定
        $objSess = new SC_Session();
        SC_Utils_Ex::sfIsSuccess($objSess);

        // マスタデータからテンプレート一覧の取得
        $this->arrMailTEMPLATE = $masterData->getMasterData("mtb_mail_template");
        $this->objFormParam = new SC_FormParam();
        // 入力パラメータの初期化
        $this->lfInitParam();
        // 問い合わせ返信メールのテンプレート番号は19のため、デフォルトで19を入れる
        if (!isset($_REQUEST['template'])) $_REQUEST['template'] = "19";

        $this->objFormParam->setParam($_REQUEST);
        $this->objFormParam->convParam();
        $arrForm = $this->objFormParam->getHashArray();
        $this->contact_status = $arrForm['contact_status'];
        
        if (!isset($_POST['mode'])) $_POST['mode'] = "";
        $this->arrForm = array();

        switch($_POST['mode']) {
        case 'delete':
            // 削除を押した場合
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            // ブラックリスト情報を削除する
            $sqlval['del_flg'] = 1;
            //$objQuery->update('dtb_blacklist', $sqlval, 'blacklist_id = ?', array($_POST['blacklist_id']));
            $objQuery->update("plg_ContactReply_contact", $sqlval, 'contact_id = ?', array($arrForm['contact_id']));
            break;
        case 'return':
            $objResponse = new SC_Response_Ex();
            $objResponse->sendRedirect("./admin_contact.php");
            break;
        case 'change_status':
            // お問い合わせの状態を変更する
            $contact_id = $arrForm['contact_id'];
            $sqlval['status'] = $arrForm['status'];
            $where = 'contact_id = ?';
            $objQuery->update('plg_ContactReply_contact', $sqlval, $where, array($contact_id));
            break;
        case 'send_confirm':
            $this->arrErr = $this->objFormParam->checkError();

            if (count($this->arrErr) == 0) {
                //$this->tpl_mainpage = 'customer/contact_confirm.tpl';
                $this->tpl_mainpage = TPL_CONFIRM_PATH;
                $this->tpl_subtitle = "お問い合わせ返信(確認)";
            }else{
                $this->tpl_mainpage = TPL_DETAIL_PATH;
            }
            break;
        case 'send_return':
            $this->tpl_mainpage = TPL_DETAIL_PATH;
            break;
        case 'send_complete':
            // 返信メールを送信して、問い合わせ詳細ページへ戻る
            // TODO メールの送信
            //会員情報変更メール
            $objHelperMail = new SC_Helper_Mail();

            // メール送信メソッドに渡す値を取得する
            $arrDetail = $this->lfGetContactDetail();
            $to = $arrDetail['email'];

            $objHelperMail->sfSendMail($to, $arrForm['title'], $arrForm['contents']);
            // メールの送信が成功したら、データベースに返信内容を登録
            $this->lfRegistContactReply();

            // 表示フォームを空にする
            $arrForm['title'] = '';
            $arrForm['contents'] = '';
            break;
        }

        // お問い合わせ詳細の取得
        $this->arrContactDetail = $this->lfGetContactDetail();
        // お問い合わせの返信一覧を取得
        $this->arrContactReplies = $this->lfGetContactReplies();
        $this->arrForm = $arrForm;


        $this->arrContacts = $this->lfGetContacts($arrForm['contact_status']);

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
    function lfInitParam() {
        // 入力検索条件
        $this->objFormParam->addParam("お問い合わせID", "contact_id", MTEXT_LEN, "n", array());
        $this->objFormParam->addParam("状態", "status", MTEXT_LEN, "n", array());
        $this->objFormParam->addParam("検索元", "from", MTEXT_LEN, "n", array());
        $this->objFormParam->addParam("テンプレート", "template", MTEXT_LEN, "n", array());
        $this->objFormParam->addParam("対応状況", "contact_status", MTEXT_LEN, "n", array());

        if ($_POST['mode'] == 'send_confirm' || $_POST['mode'] == 'send_complete' || $_POST['mode'] == 'send_return') {
            // 返信機能を利用するときは返信内容にエラーチェックをする
            $this->objFormParam->addParam("メールタイトル", "title", MTEXT_LEN, "n", array('EXIST_CHECK'));
            $this->objFormParam->addParam("本文", "contents", MTEXT_LEN, "n", array('EXIST_CHECK'));
        }
    }

    function lfGetContacts($status) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $where = 'del_flg = 0';
        $whereVal = array();
        if($status != "") {
            $where .= ' and status = ?';
            $whereVal[] = $status;
        }

        $this->tpl_linemax = $objQuery->count("plg_ContactReply_contact", $where, $whereVal);

        // ページ送りの取得
        $page_max = SEARCH_PMAX;
        $objNavi = new SC_PageNavi($_POST['search_pageno'], $this->tpl_linemax, $page_max, "fnNaviSearchPage", NAVI_PMAX);
        $this->strnavi = $objNavi->strnavi;
        $startno = $objNavi->start_row;

        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setlimitoffset($page_max, $startno);
        // 表示順序
        $order = "contact_id DESC";
        $objQuery->setorder($order);

        // お問い合わせ履歴情報の取得
        $arrRet = $objQuery->select('*', 'plg_ContactReply_contact', $where, $whereVal);

        return $arrRet;
    }

    /**
     * ページデータを取得する.
     *
     * @param integer $device_type_id 端末種別ID
     * @param integer $page_id ページID
     * @param SC_Helper_PageLayout $objLayout SC_Helper_PageLayout インスタンス
     * @return array ページデータの配列
     */
    function getTplMainpage($file_path) {

        if (file_exists($file_path)) {
            $arrfileData = file_get_contents($file_path);
        }
        return $arrfileData;
    }


    /**
     * お問い合わせ詳細を取得します。
     * @return お問い合わせ詳細を格納した配列を返します
     */
    function lfGetContactDetail() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arr = $this->objFormParam->getHashArray();
        $contact_id = $arr['contact_id'];

        $col = '*';
        $table = 'plg_ContactReply_contact';
        $where = 'contact_id = ?';
        $arrRet = $objQuery->select($col, $table, $where, array($contact_id));

        return $arrRet[0];
    }

    /**
     * お問い合わせへの返信一覧を取得します。
     * @return お問い合わせへの返信一覧を格納した配列を返します
     */
    function lfGetContactReplies() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arr = $this->objFormParam->getHashArray();
        $contact_id = $arr['contact_id'];

        $col = 'R.contents, R.title, R.direction, R.create_date, C.email';
        $table = 'plg_ContactReply_contact AS C INNER JOIN plg_ContactReply_contact_reply AS R ON C.contact_id = R.contact_id';
        $where = 'C.contact_id = ?';
        $objQuery->setOrder('R.create_date ASC');
        $arrRet = $objQuery->select($col, $table, $where, array($contact_id));

        return $arrRet;
    }

    /**
     * お問合せ内容に対する返信をデータベースに格納します。
     */
    function lfRegistContactReply() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arr = $this->objFormParam->getHashArray();
        $contact_id = $arr['contact_id'];

        $sqlval = array();

        $sqlval['contact_id'] = $contact_id;
        $sqlval['contact_reply_id'] = $objQuery->nextVal('plg_ContactReply_contact_reply_contact_reply_id');
        $sqlval['title'] = $arr['title'];
        $sqlval['contents'] = $arr['contents'];
        $sqlval['direction'] = 1;
        $sqlval['create_date'] = 'Now()';

        $col = '*';
        $table = 'plg_ContactReply_contact_reply';
        $arrRet = $objQuery->insert('plg_ContactReply_contact_reply', $sqlval);
    }

}
?>
