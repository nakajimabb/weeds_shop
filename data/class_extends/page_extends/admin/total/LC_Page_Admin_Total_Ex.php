<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
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

require_once CLASS_REALDIR . 'pages/admin/total/LC_Page_Admin_Total.php';

/**
 * 売上集計 のページクラス(拡張).
 *
 * LC_Page_Admin_Total をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Total_Ex extends LC_Page_Admin_Total
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();

        $this->arrTitle['every']    = '月別個別集計';     // naka
        $this->Year;                                    // naka
        $this->Month;                                   // naka        
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        if (isset($_GET['draw_image']) && $_GET['draw_image'] != '') {
            define('DRAW_IMAGE' , true);
        } else {
            define('DRAW_IMAGE' , false);
        }

        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_REQUEST);

        // 検索ワードの引き継ぎ
        $this->arrHidden = $objFormParam->getSearchArray();

        switch ($this->getMode()) {
            case 'csv':
            case 'search':

                $this->arrErr = $this->lfCheckError($objFormParam);
                if (empty($this->arrErr)) {
                    // 日付
                    list($sdate, $edate) = $this->lfSetStartEndDate($objFormParam);

                    // ページ
                    $page = ($objFormParam->getValue('page')) ? $objFormParam->getValue('page') : 'term';

                    // 集計種類
                    $type = ($objFormParam->getValue('type')) ? $objFormParam->getValue('type'): 'all';

                    $this->tpl_page_type = 'total/page_'. $page .'.tpl';
                    // FIXME 可読性が低いので call_user_func_array を使わない (またはメソッド名を1つの定数値とする) 実装に。
                    list($this->arrResults, $this->tpl_image) = call_user_func_array(array($this, 'lfGetOrder'.$page),
                                                                                     array($type, $sdate, $edate));
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
                break;
            // <-- add naka
            case 'csv_all':
                $this->arrErr = $this->lfCheckError($objFormParam);
                if (empty($this->arrErr)) {

                    // 日付
                    list($sdate, $edate) = $this->lfSetStartEndDate($objFormParam);

                    // ページ
                    $page = ($objFormParam->getValue('page')) ? $objFormParam->getValue('page') : 'term';

                    // 集計種類
                    $type = ($objFormParam->getValue('type')) ? $objFormParam->getValue('type'): 'all';

                    list($arrTitle, $arrData, $arrCol) = $this->lfGetOrderEveryDetailAll($type, $sdate, $edate);

                    $head = SC_Utils_Ex::sfGetCSVList($arrTitle);
                    $data = $this->lfGetDataColCSV($arrData, $arrCol);
                    $data = $this->replaceText($data);

                    // CSVを送信する。
                    list($fime_name, $data) = SC_Utils_Ex::sfGetCSVData($head.$data);

                    $this->sendResponseCSV($fime_name, $data);
                    SC_Response_Ex::actionExit();
                }
                break;
            // <--
            default:
                break;
        }

        // 画面宣しても日付が保存される
        $_SESSION           = $this->lfSaveDateSession($_SESSION, $this->arrHidden);
        $objFormParam->setParam($_SESSION['total']);
        // 入力値の取得
        $this->arrForm      = $objFormParam->getFormParamList();
        $this->tpl_subtitle = $this->arrTitle[$objFormParam->getValue('page')];

        if($page == 'every')    $this->install_GD = false;  // naka
    }

    /* フォームで入力された日付を適切な形にする */
    public function lfSetStartEndDate(&$objFormParam)
    {
        $arrRet = $objFormParam->getHashArray();

        // 月度集計
        if ($arrRet['search_form'] == 1) {
            list($sdate, $edate) = SC_Utils_Ex::sfTermMonth($arrRet['search_startyear_m'],
                                                            $arrRet['search_startmonth_m'],
                                                            CLOSE_DAY);
            $this->Year  = $arrRet['search_startyear_m'];   // naka
            $this->Month = $arrRet['search_startmonth_m'];  // naka
        }
        // 期間集計
        elseif ($arrRet['search_form'] == 2) {
            $sdate = $arrRet['search_startyear'] . '/' . $arrRet['search_startmonth'] . '/' . $arrRet['search_startday'];
            $edate = $arrRet['search_endyear'] . '/' . $arrRet['search_endmonth'] . '/' . $arrRet['search_endday'];
        }

        return array($sdate, $edate);
    }

    /** 月別個別集計 **/
    function lfGetOrderEvery($type, $sdate, $edate) {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // 注文日ではなく発送日で集計
        list($where, $arrWhereVal) = $this->lfGetWhereMember('T1.commit_date', $sdate, $edate, $type);
        $where .= ' AND T1.del_flg = 0 AND T1.status = ?';
        $arrWhereVal[] = ORDER_DELIV;
        // list($where, $arrWhereVal) = $this->lfGetWhereMember('T1.create_date', $sdate, $edate, $type);
        // $where .= ' AND T1.del_flg = 0 AND T1.status <> ?';
        // $arrWhereVal[] = ORDER_CANCEL;

        // 会員集計の取得
//        $col = <<< __EOS__
//            customer_id,
//            order_name01,
//            order_name02,
//            order_kana01,
//            order_kana02,
//            COUNT(order_id) AS order_count,
//            sum(payment_total) as total,
//            sum(use_point) as point
//__EOS__;
//
//        $from       = 'dtb_order';
        
        $col = <<< __EOS__
            T1.customer_id,
            T1.order_name01,
            T1.order_name02,
            T1.order_kana01,
            T1.order_kana02,
            T2.staff_no,
            COUNT(T1.order_id) AS order_count,
            sum(T1.payment_total) as total,
            sum(T1.use_point) as point
__EOS__;

        $from       = 'dtb_order as T1 join dtb_customer as T2 using(customer_id)';

        $objQuery->setGroupBy('customer_id');

        $arrTotalResults = $objQuery->select($col, $from, $where, $arrWhereVal);

        foreach ($arrTotalResults as $key => $value) {
            $arrResult =& $arrTotalResults[$key];
            $arrResult['customer_id'] = $arrResult['customer_id'];
            $arrResult['member_name'] = $arrResult['order_name01'].' '.$arrResult['order_name02']; 
            $arrResult['member_kana'] = $arrResult['order_kana01'].' '.$arrResult['order_kana02']; 
            $arrResult['order_count'] = $arrResult['order_count'];             
            $arrResult['total']       = $arrResult['total']; 
            $arrResult['point']       = $arrResult['point']; 
        }

        return array($arrTotalResults, $tpl_image);
    }

    /** 月別個別集計 **/
    function lfGetOrderEveryDetailAll($type, $sdate, $edate) {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // 注文日ではなく発送日で集計
        list($where, $arrWhereVal) = $this->lfGetWhereMember('T1.commit_date', $sdate, $edate, $type);
        $where .= ' AND T1.del_flg = 0 AND T1.status = ?';
        $arrWhereVal[] = ORDER_DELIV;
        // list($where, $arrWhereVal) = $this->lfGetWhereMember('T1.create_date', $sdate, $edate, $type);
        // $where .= ' AND T1.del_flg = 0 AND T1.status <> ?';
        // $arrWhereVal[] = ORDER_CANCEL;

       
        $col = <<< __EOS__
            T1.order_id,
            T1.customer_id,
            T1.order_name01,
            T1.order_name02,
            T1.create_date,
            T1.commit_date,
            T2.staff_no
__EOS__;

        $from       = 'dtb_order as T1 join dtb_customer as T2 using(customer_id)';

        $arrOrder = $objQuery->select($col, $from, $where, $arrWhereVal);

        $arrData = array();
        $col = 'order_id, product_name, classcategory_name1, price, quantity';
        $from  = 'dtb_order_detail';

        $arrTitle = array('社員番号', '氏名', '注文日', '発送日', '商品名', '単価（税抜）', '数量', '小計');
        $arrCol   = array('staff_no', 'order_name', 'create_date', 'commit_date', 'product_name', 'price', 'quantity', 'total');

        foreach ($arrOrder as $key => $value) {

            $objQuery = SC_Query_Ex::getSingletonInstance();
            $where = 'order_id = ' . $arrOrder[$key]['order_id'];
            $arrDetail = $objQuery->select($col, $from, $where);

            foreach ($arrDetail as $key2 => $value2) {
                $result['staff_no']                 = $value['staff_no'];
                $result['order_name']               = $value['order_name01'].' '.$value['order_name02'];
                $result['create_date']              = date('Y/m/d', strtotime($value['create_date']));
                $result['commit_date']              = date('Y/m/d', strtotime($value['commit_date']));

                $result['product_name']             = $value2['product_name'];
                if(!empty($value2['classcategory_name1']))
                    $result['product_name'] .= '  [ ' . $value2['classcategory_name1'] . ' ]'; 

                $result['price']                    = $value2['price'];
                $result['quantity']                 = $value2['quantity'];
                $result['total']                    = strval(intval($value2['price']) * intval($value2['quantity']));

                $arrData[] = $result;
            }
        }

        return array($arrTitle, $arrData, $arrCol);
    }

    public function lfGetCSVColum($page)
    {
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
            // --> naka
            // 月別個別集計
            case 'every':
                $arrTitleCol = array(
                    '社員番号',
                    '会員ID',
                    '氏名',
                    '購入件数',
                    '支払合計',
                );
                $arrDataCol = array(
                    'staff_no',
                    'customer_id',
                    'member_name',
                    'order_count',
                    'total',
                );
                break;
            // <--
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

    function replaceText($str){
        $arr = array(
        'Ⅰ' => 'I',
        'Ⅱ' => 'II',
        'Ⅲ' => 'III',
        'Ⅳ' => 'IV',
        'Ⅴ' => 'V',
        'Ⅵ' => 'VI',
        'Ⅶ' => 'VII',
        'Ⅷ' => 'VIII',
        'Ⅸ' => 'IX',
        'Ⅹ' => 'X',
        'Ⅺ' => 'XI',
        'Ⅻ' => 'XII',
        );
        return str_replace( array_keys( $arr), array_values( $arr), $str);
    }
}
