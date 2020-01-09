<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 売上集計 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Total_Detail.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Admin_Total_Detail extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        // GDライブラリのインストール判定
        $this->tpl_mainpage         = 'total/detail.tpl';
        $this->tpl_graphsubtitle    = 'total/subtitle.tpl';

        $masterData                 = new SC_DB_MasterData_Ex();
        $this->arrWDAY              = $masterData->getMasterData('mtb_wday');
        $this->arrSex               = $masterData->getMasterData('mtb_sex');
        $this->arrJob               = $masterData->getMasterData('mtb_job');

        // 登録・更新日検索用
        $objDate                    = new SC_Date_Ex();
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrYear              = $objDate->getYear();
        $this->arrMonth             = $objDate->getMonth();
        $this->arrDay               = $objDate->getDay();

        $this->CustomerID; 
        $this->Member;  
        $this->Year;    
        $this->Month;   

        $this->Order;
        $this->Customer;
        $this->TotalPayment;
        $this->TotalPoint;
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

        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_REQUEST);

        $this->CustomerID   = $_GET['id'];
        $this->Year         = $_GET['year']; 
        $this->Month        = $_GET['month'];
        
        $this->Customer = $this->lfGetCustomer($this->CustomerID);
        
        
        // 検索ワードの引き継ぎ
        $this->arrHidden = $objFormParam->getSearchArray();


        $this->arrErr = $this->lfCheckError($objFormParam);
        if (empty($this->arrErr)) {

            // 日付
            list($sdate, $edate) = $this->lfSetStartEndDate($objFormParam);

            // ページ
            $page = ($objFormParam->getValue('page')) ? $objFormParam->getValue('page') : 'term';

            // 集計種類
            $type = ($objFormParam->getValue('type')) ? $objFormParam->getValue('type'): 'all';

            list($sdate, $edate) = SC_Utils_Ex::sfTermMonth($this->Year, $this->Month, CLOSE_DAY);

            // FIXME 可読性が低いので call_user_func_array を使わない (またはメソッド名を1つの定数値とする) 実装に。
            $this->Order = $this->lfGetOrder($this->CustomerID, $sdate, $edate);
            $this->TotalPayment = $this->getcolsum($this->Order, 'payment_total');
            $this->TotalPoint   = $this->getcolsum($this->Order, 'use_point');

            
            if ($this->getMode() == 'csv') {
                // CSV出力タイトル行の取得
                list($arrTitleCol, $arrDataCol) = $this->lfGetCSVColum($page);
                $head = SC_Utils_Ex::sfGetCSVList($arrTitleCol);
                $data = $this->lfGetDataColCSV($this->arrResults, $arrDataCol);

                // CSVを送信する。
                list($fime_name, $data) = SC_Utils_Ex::sfGetCSVData($head.$data);

                $this->sendResponseCSV($fime_name, $data);
                SC_Response_Ex::actionExit();
            }
        }

        // 画面宣しても日付が保存される
        $_SESSION           = $this->lfSaveDateSession($_SESSION, $this->arrHidden);
        $objFormParam->setParam($_SESSION['total']);
        // 入力値の取得
        $this->arrForm      = $objFormParam->getFormParamList();
        $this->tpl_subtitle = $this->arrTitle[$objFormParam->getValue('page')];
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /* デフォルト値の取得 */
    function lfGetDateDefault() {
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $list = isset($_SESSION['total']) ? $_SESSION['total'] : '';

        // セッション情報に開始月度が保存されていない。
        if (empty($_SESSION['total']['startyear_m'])) {
            $list['startyear_m'] = $year;
            $list['startmonth_m'] = $month;
        }

        // セッション情報に開始日付、終了日付が保存されていない。
        if (empty($_SESSION['total']['startyear']) && empty($_SESSION['total']['endyear'])) {
            $list['startyear'] = $year;
            $list['startmonth'] = $month;
            $list['startday'] = $day;
            $list['endyear'] = $year;
            $list['endmonth'] = $month;
            $list['endday'] = $day;
        }

        return $list;
    }

    /* パラメーター情報の初期化 */
    function lfInitParam(&$objFormParam) {
        // デフォルト値の取得
        $arrList = $this->lfGetDateDefault();

        // 月度集計
        $objFormParam->addParam('月度(年)', 'search_startyear_m', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), $arrList['startyear_m']);
        $objFormParam->addParam('月度(月)', 'search_startmonth_m', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), $arrList['startmonth_m']);
        // 期間集計
        $objFormParam->addParam('期間(開始日)', 'search_startyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), $arrList['startyear']);
        $objFormParam->addParam('期間(開始日)', 'search_startmonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), $arrList['startmonth']);
        $objFormParam->addParam('期間(開始日)', 'search_startday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), $arrList['startday']);
        $objFormParam->addParam('期間(終了日)', 'search_endyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), $arrList['endyear']);
        $objFormParam->addParam('期間(終了日)', 'search_endmonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), $arrList['endmonth']);
        $objFormParam->addParam('期間(終了日)', 'search_endday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'), $arrList['endday']);

        // hiddenデータの取得用
        $objFormParam->addParam('', 'page');
        $objFormParam->addParam('', 'type');
        $objFormParam->addParam('', 'mode');
        $objFormParam->addParam('', 'search_form');
    }

    /* 入力内容のチェック */
    function lfCheckError(&$objFormParam) {

        $objFormParam->convParam();
        $objErr         = new SC_CheckError_Ex($objFormParam->getHashArray());
        $objErr->arrErr = $objFormParam->checkError();

        // 特殊項目チェック

        // 月度集計
        if ($objFormParam->getValue('search_form') == 1) {
            $objErr->doFunc(array('月度', 'search_startyear_m', 'search_startmonth_m'), array('FULL_EXIST_CHECK'));
        }

        // 期間集計
        if ($objFormParam->getValue('search_form') == 2) {
            $objErr->doFunc(array('期間(開始日)', 'search_startyear', 'search_startmonth', 'search_startday'), array('FULL_EXIST_CHECK'));
            $objErr->doFunc(array('期間(終了日)', 'search_endyear', 'search_endmonth', 'search_endday'), array('FULL_EXIST_CHECK'));
            $objErr->doFunc(array('期間(開始日)', 'search_startyear', 'search_startmonth', 'search_startday'), array('CHECK_DATE'));
            $objErr->doFunc(array('期間(終了日)', 'search_endyear', 'search_endmonth', 'search_endday'), array('CHECK_DATE'));
            $objErr->doFunc(array('期間(開始日)', '期間(終了日)', 'search_startyear', 'search_startmonth', 'search_startday', 'search_endyear', 'search_endmonth', 'search_endday'), array('CHECK_SET_TERM'));
        }

        return $objErr->arrErr;
    }

    /* サブナビを移動しても日付が残るようにセッションに入力期間を記録する */
    function lfSaveDateSession($session, $arrForm) {

        // session の初期化をする
        if (!isset($session['total'])) {
            $session['total'] = $this->lfGetDateInit();
        }

        if (!empty($arrForm)) {
            $session['total'] = array_merge($session['total'], $arrForm);
        }

        return $session;
    }

    /* 日付の初期値 */
    function lfGetDateInit() {
        $search_startyear_m     = $search_startyear  = $search_endyear  = date('Y');
        $search_startmonth_m    = $search_startmonth = $search_endmonth = date('m');
        $search_startday        = $search_endday     = date('d');

        return compact($this->arrSearchForm1, $this->arrSearchForm2);
    }

    /* フォームで入力された日付を適切な形にする */
    function lfSetStartEndDate(&$objFormParam) {
        $arrRet = $objFormParam->getHashArray();

        // 月度集計
        if ($arrRet['search_form'] == 1) {
            list($sdate, $edate) = SC_Utils_Ex::sfTermMonth($arrRet['search_startyear_m'],
                                                            $arrRet['search_startmonth_m'],
                                                            CLOSE_DAY);
            
        }
        // 期間集計
        elseif ($arrRet['search_form'] == 2) {
            $sdate = $arrRet['search_startyear'] . '/' . $arrRet['search_startmonth'] . '/' . $arrRet['search_startday'];
            $edate = $arrRet['search_endyear'] . '/' . $arrRet['search_endmonth'] . '/' . $arrRet['search_endday'];
        }

        return array($sdate, $edate);
    }

    // 会員、非会員集計のWHERE分の作成
    function lfGetWhereMember($col_date, $sdate, $edate, $col_member = 'customer_id') {
        $where = '';
        // 取得日付の指定
        if ($sdate != '') {
            if ($where != '') {
                $where.= ' AND ';
            }
            $where.= " $col_date >= '". $sdate ."'";
        }

        if ($edate != '') {
            if ($where != '') {
                $where.= ' AND ';
            }
            $edate = date('Y/m/d',strtotime('1 day' ,strtotime($edate)));
            $where.= " $col_date < date('" . $edate ."')";
        }

        // 会員、非会員の判定
        if ($where != '') {
            $where.= ' AND ';
        }
        $where.= " $col_member <> 0";

        return array($where, array());
    }

    /*
     * 期間中の日付を埋める
     */
    function lfAddBlankLine($arrResults, $type, $st, $ed) {

        $arrDateList = $this->lfDateTimeArray($type, $st, $ed);

        foreach ($arrResults as $arrResult) {
            $strdate                = $arrResult['str_date'];
            $arrDateResults[$strdate] = $arrResult;
        }

        foreach ($arrDateList as $date) {

            if (array_key_exists($date, $arrDateResults)) {

                $arrRet[] = $arrDateResults[$date];

            } else {
                $arrRet[]['str_date'] = $date;
            }
        }
        return $arrRet;
    }

    /*
     * 日付の配列を作成する
     *
     */
    function lfDateTimeArray($type, $st, $ed) {
        switch ($type) {
            case 'month':
                $format        = 'm';
                break;
            case 'year':
                $format        = 'Y';
                break;
            case 'wday':
                $format        = 'D';
                break;
            case 'hour':
                $format        = 'H';
                break;
            default:
                $format        = 'Y-m-d';
                break;
        }

        if ($type == 'hour') {
            $arrDateList = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');

        } else {
            $arrDateList = array();
            $tmp    = strtotime($st);
            $nAday  = 60*60*24;
            $edx    = strtotime($ed);
            while ($tmp <= $edx) {
                $sDate = date($format, $tmp);
                if (!in_array($sDate, $arrDateList)) {
                    $arrDateList[] = $sDate;
                }
                $tmp += $nAday;
            }
        }
        return $arrDateList;
    }

    /*
     * 合計を付与する
     */
    function lfAddTotalLine($arrResults) {
        // 検索結果が0でない場合
        if (count($arrResults) > 0) {

            // 合計の計算
            foreach ($arrResults as $arrResult) {
                foreach ($arrResult as $key => $value) {
                    $arrTotal[$key] += $arrResult[$key];
                }
            }
            // 平均値の計算
            $arrTotal['total_average'] = $arrTotal['total'] / $arrTotal['total_order'];
            $arrResults[] = $arrTotal;
        }

        return $arrResults;
    }

    // 必要なカラムのみ抽出する(CSVデータで取得する)
    function lfGetDataColCSV($arrData, $arrDataCol) {
        $max = count($arrData);
        $csv_data = '';
        for ($i = 0; $i < $max; $i++) {
            foreach ($arrDataCol as $val) {
                $arrRet[$i][$val] = $arrData[$i][$val];
            }
            // 期間別集計の合計行の「期間」項目に不要な値が表示されてしまわない様、'合計'と表示する
            if (($i === $max -1) && isset($arrRet[$i]['str_date'])) {
                $arrRet[$i]['str_date'] = '合計';
            }
            $csv_data.= SC_Utils_Ex::sfGetCSVList($arrRet[$i]);
        }
        return $csv_data;
    }

    function lfGetCSVColum($page) {
        switch ($page) {
            // 商品別集計
            case 'products':
                $arrTitleCol = array(
                    '商品コード',
                    '商品名',
                    '購入件数',
                    '数量',
                    '単価',
                    '金額',
                );
                $arrDataCol = array(
                    'product_code',
                    'product_name',
                    'order_count',
                    'products_count',
                    'price',
                    'total',
                );
                break;
            // 職業別集計
            case 'job':
                $arrTitleCol = array(
                    '職業',
                    '購入件数',
                    '購入合計',
                    '購入平均',
                );
                $arrDataCol = array(
                    'job_name',
                    'order_count',
                    'total',
                    'total_average',
                );
                break;
            // 会員別集計
            case 'member':
                $arrTitleCol = array(
                    '会員',
                    '購入件数',
                    '購入合計',
                    '購入平均',
                );
                $arrDataCol = array(
                    'member_name',
                    'order_count',
                    'total',
                    'total_average',
                );
                break;
            // 年代別集計
            case 'age':
                $arrTitleCol = array(
                    '年齢',
                    '購入件数',
                    '購入合計',
                    '購入平均',
                );
                $arrDataCol = array(
                    'age_name',
                    'order_count',
                    'total',
                    'total_average',
                );
                break;
            // 月別個別集計
            case 'every':
                $arrTitleCol = array(
                    '会員ID',
                    '氏名',
                    'かな',
                    '購入件数',
                    '購入合計（使用ポイント込）',
                    '使用ポイント',
                );
                $arrDataCol = array(
                    'customer_id',
                    'member_name',
                    'member_kana',
                    'order_count',
                    'total',
                    'point',
                );
                break;
            // 期間別集計
            default:
                $arrTitleCol = array(
                    '期間',
                    '購入件数',
                    '男性',
                    '女性',
                    '男性(会員)',
                    '男性(非会員)',
                    '女性(会員)',
                    '女性(非会員)',
                    '購入合計',
                    '購入平均',
                );
                $arrDataCol = array(
                    'str_date',
                    'total_order',
                    'men',
                    'women',
                    'men_member',
                    'men_nonmember',
                    'women_member',
                    'women_nonmember',
                    'total',
                    'total_average',
                );
                break;
        }

        return array($arrTitleCol, $arrDataCol);
    }

    
    /** 会員別集計 **/
    function lfGetOrder($id, $sdate, $edate) {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // 注文日ではなく発送日で集計
        //list($where, $arrWhereVal) = $this->lfGetWhereMember('create_date', $sdate, $edate);
        list($where, $arrWhereVal) = $this->lfGetWhereMember('commit_date', $sdate, $edate);
        // $where .= ' AND del_flg = 0 AND status <> ?';
        $where .= ' AND del_flg = 0 AND status = ?';
        $where .= ' AND customer_id = ?';

        // $arrWhereVal[] = ORDER_CANCEL;
        $arrWhereVal[] = ORDER_DELIV;
        $arrWhereVal[] = $id;

        // 会員集計の取得
        $col = '*';
        $from       = 'dtb_order';

        $arrTotalResults = $objQuery->select($col, $from, $where, $arrWhereVal);

        $where = 'order_id = ?';
        $from       = 'dtb_order_detail';
        $col = 'product_name, classcategory_name1, price, quantity';

        foreach($arrTotalResults as $key => $value) {
    
            $arrWhereVal[0] = $value['order_id'];
            $detail_order = $objQuery->select($col, $from, $where, $arrWhereVal);

            $arrTotalResults[$key]['detail'] = $detail_order;
        }
        //unset($total); // 最後の要素への参照を解除します
        
        return $arrTotalResults;
    }

    function lfGetCustomer($id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $where = 'customer_id = ?';
        $arrWhereVal[] = $id;

        // 会員集計の取得
        $col = '*';
        $from       = 'dtb_customer';

        $result = $objQuery->getRow($col, $from, $where, $arrWhereVal);
        return $result;
    }
    
    function getcolsum($array, $col) {
        
        $sum = 0;
        foreach ($array as $key => $value) {
            $sum += $value[$col];
        }        
        
        return $sum;
    }

}
