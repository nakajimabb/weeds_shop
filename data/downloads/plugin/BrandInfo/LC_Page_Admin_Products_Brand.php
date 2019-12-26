<?php

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

 // ブランド登録 のページクラス.
class LC_Page_Admin_Products_Brand extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . 'BrandInfo/templates/brand.tpl';
        $this->tpl_subno = 'brand';
        $this->tpl_maintitle = '商品管理';
        $this->tpl_subtitle = 'ブランド登録';
        $this->tpl_mainno = 'products';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrMaker = SC_Helper_DB_Ex::sfGetIDValueList('dtb_maker', 'maker_id', 'name');
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

        $objFormParam = new SC_FormParam_Ex();

        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);

        // POST値をセット
        $objFormParam->setParam($_POST);

        // POST値の入力文字変換
        $objFormParam->convParam();

        //brand_idを変数にセット
        $brand_id = $objFormParam->getValue('brand_id');

        // 変換後のPOST値を取得
        $this->arrForm  = $objFormParam->getHashArray();

        // アップロードファイル情報の初期化 naka --->
        $objUpFile = new SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        $this->lfInitFile($objUpFile);
        if($this->getMode()!='pre_edit'){
                $objUpFile->setHiddenFileList($_POST);
        }
        // <-- naka

 
        // モードによる処理切り替え
        switch ($this->getMode()) {

            // 編集処理
            case 'edit':
            // 入力文字の変換

                // エラーチェック
                $this->arrErr = $this->lfCheckError($this->arrForm, $objFormParam);
                if (count($this->arrErr) <= 0) {

                    if ($this->arrForm['brand_id'] == '') {
                        // ブランド情報新規登録
                        $brand_id = $this->lfInsert($this->arrForm, $objUpFile);
                        $this->lfSaveUploadFiles($objUpFile, $brand_id);    // naka
                    } else {
                        // ブランド情報編集
                        $arrRet = $objUpFile->getDBFileList();              // naka
                        $this->lfUpdate($this->arrForm, $objUpFile);
                        $this->lfSaveUploadFiles($objUpFile);    // naka
                    }

                    // 再表示
                    $this->objDisplay->reload();
                } else {
                    // POSTデータを引き継ぐ
                    $this->tpl_brand_id = $this->arrForm['brand_id'];
                }
                break;

            // 編集前処理
            case 'pre_edit':
                $this->arrForm = $this->lfPreEdit($this->arrForm, $this->arrForm['brand_id'], $objUpFile);  // naka
                $this->arrForm = array_merge($this->lfSetViewParam_InputPage($objUpFile, $this->arrForm),$this->arrForm);   // naka
                $this->tpl_brand_id = $this->arrForm['brand_id'];
                break;

            // ブランド順変更
            case 'up':
            case 'down':
                $this->lfRankChange($this->arrForm['brand_id'], $this->getMode());

                // リロード
                SC_Response_Ex::reload();
                break;

            // 削除
            case 'delete':
                $this->lfDelete($this->arrForm['brand_id']);

                // リロード
                SC_Response_Ex::reload();
                break;

            // 画像のアップロード --> naka
            case 'upload_image':
            case 'delete_image':
                // パラメーター初期化
                $objFormParam->addParam("image_key", "image_key", "", "", array());
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                $this->arrForm = $objFormParam->getHashArray();

                $objQuery =& SC_Query_Ex::getSingletonInstance();   // naka
                $brand_id2 = ($brand_id != 0) ? $brand_id : $objQuery->currVal('dtb_brand_brand_id') + 1; // naka
                
                switch($this->getMode()) {
                case 'upload_image':
                    // ファイルを一時ディレクトリにアップロード
                    //$this->arrErr[$this->arrForm['image_key']] = $objUpFile->makeTempFile($this->arrForm['image_key'], "brand_".$this->arrForm['brand_id']);
                    $this->arrErr[$this->arrForm['image_key']] = $objUpFile->makeTempFile($this->arrForm['image_key'], "brand_".$brand_id2);    // naka
                    if($this->arrErr[$arrForm['image_key']] == "") {
                        // 縮小画像作成
                        //$this->lfSetScaleImage($objUpFile, $arrForm['image_key'],$arrForm['brand_id']);

                        $arrImageKey = array_flip($objUpFile->keyname);
                        $from_path = "";

                        if($objUpFile->temp_file[$arrImageKey[$from_key]]) {
                            $from_path = $objUpFile->temp_dir . $objUpFile->temp_file[$arrImageKey[$from_key]];
                        } elseif($objUpFile->save_file[$arrImageKey[$from_key]]){
                            $from_path = $objUpFile->save_dir . $objUpFile->save_file[$arrImageKey[$from_key]];
                        }

                        if(file_exists($from_path)) {
                            // 生成先の画像サイズを取得
                            $to_w = $objUpFile->width[$arrImageKey[$to_key]];
                            $to_h = $objUpFile->height[$arrImageKey[$to_key]];

                            //$dst_file = $objUpFile->temp_dir."brand_".$brand_id. $suffix;
                            $dst_file = $objUpFile->temp_dir."brand_".$brand_id2. $suffix;      // naka
                            $path = $objUpFile->makeThumb($from_path, $to_w, $to_h, $dst_file);
                            $objUpFile->temp_file[$arrImageKey[$to_key]] = basename($path);
                        }
                    }
                break;
            case 'delete_image':
                // ファイル取り消し
                $this->lfDeleteTempFile($objUpFile);
                break;
            }

            // 入力画面表示設定
            $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile, $this->arrForm);
            break;
            // <--- naka

            default:
                break;
        }
        $this->arrForm['arrFile'] = $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);    // naka
        $this->arrForm['mode'] = $_POST['mode'];    // naka

        // ブランド情報読み込み
        $this->arrBrand = $this->lfDisp();
        // POSTデータを引き継ぐ
        $this->tpl_brand_id = $brand_id;

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
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam('ブランドID', 'brand_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ブランド名', 'name', SMTEXT_LEN, 'KVa', array('EXIST_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ブランド情報', 'brand_info', STEXT_LEN, 'KVa', array());    // naka
        $objFormParam->addParam('ブランド画像', 'brand_image', STEXT_LEN, 'KVa', array());   // naka
        $objFormParam->addParam('メーカー', 'maker_id', INT_LEN, 'n', array('NUM_CHECK'));  // naka
    }

    /**
     * ブランド情報表示.
     *
     * @return array $arrBrand ブランド情報
     */
    function lfDisp() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // 削除されていないブランド情報を表示する
        $where = 'del_flg = 0';
        $objQuery->setOrder('rank DESC');
        $arrBrand = array();
        $arrBrand = $objQuery->select('brand_id, name', 'dtb_brand', $where);
        return $arrBrand;
    }

    /**
     * ブランド情報新規登録.
     *
     * @param array $arrForm ブランド情報
     * @return void
     */
    function lfInsert(&$arrForm, $objUpFile) {

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // INSERTする値を作成する
        $sqlval['name'] = $arrForm['name'];
        $sqlval['rank'] = $objQuery->max('rank', 'dtb_brand') + 1;
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
        $sqlval['brand_id'] = $objQuery->nextVal('dtb_brand_brand_id');
        $sqlval['brand_info'] = $arrForm['brand_info'];     // naka
        $sqlval['maker_id'] = $arrForm['maker_id'];         // naka
        $arrRet = $objUpFile->getDBFileList();              // naka
        $sqlval = array_merge($sqlval, $arrRet);            // naka

        // INSERTの実行
        $objQuery->insert('dtb_brand', $sqlval);
        
        return $sqlval['brand_id']; // naka
    }

    /**
     * ブランド情報更新.
     *
     * @param array $arrForm ブランド情報
     * @return void
     */
    function lfUpdate(&$arrForm, $objUpFile) {

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // UPDATEする値を作成する
        $sqlval['name'] = $arrForm['name'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = 'brand_id = ?';
        $sqlval['brand_info'] = $arrForm['brand_info'];     // naka
        $sqlval['maker_id'] = $arrForm['maker_id'];         // naka
        $arrRet = $objUpFile->getDBFileList();              // naka
        $sqlval = array_merge($sqlval, $arrRet);            // naka

        // UPDATEの実行
        $objQuery->update('dtb_brand', $sqlval, $where, array($arrForm['brand_id']));
    }

    /**
     * ブランド情報削除.
     *
     * @param integer $brand_id ブランドID
     * @return void
     */
    function lfDelete($brand_id) {
        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfDeleteRankRecord('dtb_brand', 'brand_id', $brand_id, '', true);
    }

    /**
     * ブランド情報順番変更.
     *
     * @param  integer $brand_id ブランドID
     * @param  string  $mode up か down のモードを示す文字列
     * @return void
     */
    function lfRankChange($brand_id, $mode) {
        $objDb = new SC_Helper_DB_Ex();

        switch ($mode) {
            case 'up':
                $objDb->sfRankUp('dtb_brand', 'brand_id', $brand_id);
                break;

            case 'down':
                $objDb->sfRankDown('dtb_brand', 'brand_id', $brand_id);
                break;

            default:
                break;
        }
    }

    /**
     * ブランド情報編集前処理.
     *
     * @param array   $arrForm ブランド情報
     * @param integer $brand_id ブランドID
     * @return array  $arrForm ブランド名を追加
     */
    function lfPreEdit(&$arrForm, $brand_id, $objUpFile) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // 編集項目を取得する
        $where = 'brand_id = ?';
        $arrBrand = array();
        //$arrBrand = $objQuery->select('name', 'dtb_brand', $where, array($brand_id));
        $arrBrand = $objQuery->select('*', 'dtb_brand', $where, array($brand_id));  // naka

        $arrForm['name'] = $arrBrand[0]['name'];
        $arrForm['brand_info'] = $arrBrand[0]['brand_info'];    // naka
        $arrForm['brand_image'] = $arrBrand[0]['brand_image'];  // naka
        $arrForm['maker_id'] = $arrBrand[0]['maker_id'];        // naka

        $objUpFile->setDBFileList($this->arrForm);              // naka
   
        return $arrForm;
    }

    /**
     * 入力エラーチェック.
     *
     * @param  array $arrForm ブランド情報
     * @return array $objErr->arrErr エラー内容
     */
    function lfCheckError(&$arrForm, &$objFormParam) {

        $arrErr = $objFormParam->checkError();
        if (!empty($arrErr)) {
            return $arrErr;
        }

        // brand_id の正当性チェック
        if (!empty($arrForm['brand_id'])) {
            $objDb = new SC_Helper_DB_Ex();
            if (!SC_Utils_Ex::sfIsInt($arrForm['brand_id'])
                || SC_Utils_Ex::sfIsZeroFilling($arrForm['brand_id'])
                || !$objDb->sfIsRecord('dtb_brand', 'brand_id', array($arrForm['brand_id']))
            ) {
                // brand_idが指定されていて、且つその値が不正と思われる場合はエラー
                $arrErr['brand_id'] = '※ ブランドIDが不正です<br />';
            }
        }
        if (!isset($arrErr['name'])) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $arrBrand = array();
            $arrBrand = $objQuery->select('brand_id, name', 'dtb_brand', 'del_flg = 0 AND name = ?', array($arrForm['name']));

            // 編集中のレコード以外に同じ名称が存在する場合
            if ($arrBrand[0]['brand_id'] != $arrForm['brand_id'] && $arrBrand[0]['name'] == $arrForm['name']) {
                $arrErr['name'] = '※ 既に同じ内容の登録が存在します。<br />';
            }
        }

        return $arrErr;
    }

    // --> naka
    /**
     * アップロードファイルパラメーター情報の初期化
     * - 画像ファイル用
     *
     * @param object $objUpFile SC_UploadFileインスタンス
     * @return void
     */
    function lfInitFile(&$objUpFile) {
        $objUpFile->addFile('ブランド画像', 'brand_image', array('jpg', 'gif', 'png'),IMAGE_SIZE, false, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
    }

    /**
     * 表示用フォームパラメーター取得
     * - 入力画面
     *
     * @param object $objUpFile SC_UploadFileインスタンス
     * @param array $arrForm フォーム入力パラメーター配列
     * @return array 表示用フォームパラメーター配列
     */
    function lfSetViewParam_InputPage(&$objUpFile, &$arrForm) {

        // アップロードファイル情報取得(Hidden用)
        $arrForm['arrHidden'] = $objUpFile->getHiddenFileList();

        // 画像ファイル表示用データ取得
        $arrForm['arrFile'] = $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);

        return $arrForm;
    }

    /**
     * アップロードファイルを保存する
     * 
     * @param object $objUpFile SC_UploadFileインスタンス
     * @param integer $brand_id カテゴリID
     * @return void
     */
    function lfSaveUploadFiles(&$objUpFile) {
        // TODO: SC_UploadFile::moveTempFileの画像削除条件見直し要
        $objImage = new SC_Image_Ex($objUpFile->temp_dir);
        $arrTempFile = $objUpFile->temp_file;

        foreach($arrTempFile as $key => $temp_file) {
            if($temp_file) {
                $objImage->moveTempImage($temp_file, $objUpFile->save_dir);
            }
        }
    }

    /**
     * アップロードファイルパラメーター情報から削除
     * 一時ディレクトリに保存されている実ファイルも削除する
     *
     * @param object $objUpFile SC_UploadFileインスタンス
     * @return void
     */ 
    function lfDeleteTempFile(&$objUpFile) {
        // TODO: SC_UploadFile::deleteFileの画像削除条件見直し要
        $arrTempFile = $objUpFile->temp_file;
        $arrKeyName = $objUpFile->keyname;

        foreach($arrKeyName as $key => $keyname) {

            if(!empty($arrTempFile[$key])) {
                $temp_file = $arrTempFile[$key];
                $arrTempFile[$key] = '';

                if(!in_array($temp_file, $arrTempFile)) {
                    $objUpFile->deleteFile($keyname);
                } else {
                    $objUpFile->temp_file[$key] = '';
                    $objUpFile->save_file[$key] = '';
                }
            } else {
                $objUpFile->temp_file[$key] = '';
                $objUpFile->save_file[$key] = '';
            }
        }
    }    

    // <-- naka
}
