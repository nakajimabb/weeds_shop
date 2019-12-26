<?php

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

 // ブランド登録 のページクラス.
class LC_Page_Admin_Customer_CustomerCSV extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . 'CustomerCSV/templates/customer_csv.tpl';
        $this->tpl_subno = 'customer_csv';
        $this->tpl_maintitle = '会員管理';
        $this->tpl_subtitle = '会員登録CSV';
        $this->tpl_mainno = 'customer';
    }

    function process() {
        $this->action();
        $this->sendResponse();
    }

    public function action()
    {
        $this->objDb = new SC_Helper_DB_Ex();

        // CSV管理ヘルパー
        // $objCSV = new SC_Helper_CSV_Ex();

        // CSVファイルアップロード情報の初期化
        $objUpFile = new SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        $this->lfInitFile($objUpFile);

        $this->max_upload_csv_size = SC_Utils_Ex::getUnitDataSize(CSV_SIZE);

        switch ($this->getMode()) {
            case 'csv_upload':
                list($this->regist_count, $this->exist_count) = $this->doUploadCsv($objUpFile);
                break;
            default:
                break;
        }

    }

    function destroy() {
        parent::destroy();
    }

    public function doUploadCsv(&$objUpFile)
    {
        // ファイルアップロードのチェック
        $this->arrErr['csv_file'] = $objUpFile->makeTempFile('csv_file');
        if (strlen($this->arrErr['csv_file']) >= 1) {
            return;
        }
        $arrErr = $objUpFile->checkExists();
        if (count($arrErr) > 0) {
            $this->arrErr = $arrErr;

            return;
        }
        // 一時ファイル名の取得
        $filepath = $objUpFile->getTempFilePath('csv_file');

        // CSVファイルの文字コード変換
        $enc_filepath = SC_Utils_Ex::sfEncodeFile($filepath, CHAR_CODE, CSV_TEMP_REALDIR);

        // CSVファイルのオープン
        $fp = fopen($enc_filepath, 'r');
        // 失敗した場合はエラー表示
        if (!$fp) {
            SC_Utils_Ex::sfDispError('');
        }

        $arrRegistCustomer = array();
        $arrExistCustomer  = array();
        $regist_count = 0;
        $exist_count = 0;

        $arrHeader = fgetcsv($fp, CSV_LINE_MAX);
        $keycol = $arrHeader[0];
        $messages = array();

        while (!feof($fp)) {

            $arrCSV = fgetcsv($fp, CSV_LINE_MAX);

            if(empty($arrCSV) || empty($arrCSV[0])) continue;

            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $cols = '*';
            $from = 'dtb_customer';
            $where = $keycol . ' = ?';
            $whereVal = array();
            $whereVal[] = $arrCSV[0];

            $sqlval = array_combine($arrHeader, $arrCSV);
            foreach ($sqlval as $key => $value) {
                if(empty($value)) unset($sqlval[$key]);         // 空の場合はセットしない
            }

            $result = $objQuery->select($cols, $from, $where, $whereVal);

            if(empty($result)) {
                $this->lfRegistData($sqlval);
                $arrRegistCustomer[] = $sqlval;
                $regist_count++;
            }
            else {
                $arrExistCustomer[] = $sqlval;
                $exist_count++;
            }
        }

        // SC_Utils::sfPrintR('登録データ');
        // foreach($arrRegistCustomer as $key => $value) {
        //     SC_Utils::sfPrintR($value);
        // }

        // SC_Utils::sfPrintR('登録済データ');
        // foreach($arrExistCustomer as $key => $value) {
        //     SC_Utils::sfPrintR($value);
        // }

        return array($regist_count, $exist_count);
    }

    public function lfInitFile(&$objUpFile)
    {
        $objUpFile->addFile('CSVファイル', 'csv_file', array('csv'), CSV_SIZE, true, 0, 0, false);
    }

    public function lfRegistData(&$arrData)
    {
        $arrData['secret_key'] = SC_Utils_Ex::sfGetUniqRandomId('r');

        return SC_Helper_Customer_Ex::sfEditCustomerData($arrData);
    }
}
