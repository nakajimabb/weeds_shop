<?php
/*
 * MainImage
 * Copyright(c) 2012 DELIGHT Inc. All Rights Reserved.
 *
 * http://www.delight-web.com/
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
require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/class/plg_MainImage_SC_UploadFile_Ex.php';
require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/class/plg_MainImage_SC_Utils_Ex.php';
require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/MainImage.php';

/**
 * メインイメージの管理ページクラス.
 *
 * @package Page
 * @author DELIGHT CO.,LTD.
 * @version $
 */
 
class plg_MainImage_LC_Page_Admin_Contents_MainImage extends LC_Page_Admin_Ex{
    
    //データベース上のフィールド名
    var $image_key = 'image';
    
    //データベース上のフィールド一覧
    var $arrFields = array(
        'title',
        'url_pc',
        'url_sp',
        'url_mb',
        'target_blank_pc',
        'target_blank_sp',
        'target_blank_mb',
        'hidden_pc',
        'hidden_sp',
        'hidden_mb',
        'image'
    );
    
    var $arrPlugin;
    
    /**
     * Page を初期化する
     * @return void
     */
     function init(){
         parent::init();
         $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . 'MainImage/templates/plg_MainImage_admin_main_image.tpl';
         $this->tpl_mainno = 'contents';
         $this->tpl_subno = 'main_image';
         $this->tpl_maintitle = 'コンテンツ管理';
         $this->tpl_subtitle = 'メインイメージ設定';
         $this->arrPlugin = MainImage::getNamedPluginInfo();
     }
     
     /**
      * Page のプロセス
      * 
      * @return void
      */
        function process(){
            $this->action();
            $this->sendResponse();
        }
      
    /**
    * Page のアクション
    *
    * @return void
    */
    function action(){
        $objFormParam = new SC_FormParam_Ex();
        $objUpFile = new plg_MainImage_SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        $this->lfInitFile($objUpFile);
        $objUpFile->setHiddenFileList($_POST);
        
        $mode = $this->getMode();
        switch($mode){
        case 'upload_image':
            
            $this->lfInitFormParam($objFormParam,$_POST);
            $arrForm = $objFormParam->getHashArray();
            $image_key = $this->image_key;
            //何番目の画像の処理か取得する
            $image_number = $arrForm['image_number'];
            
            $this->arrErr[$image_key] = $objUpFile->makeTempFile($image_key,IMAGE_RENAME,$image_number);
            //エラーがない場合
            if(empty($this->arrErr[$image_key])){
                //縮小画像作成
                $this->lfMakeScaleImage($objUpFile, $image_key,$image_number);
            }
            
            $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile,$arrForm);
            break;
            
        case 'edit':
            $this->lfInitFormParam($objFormParam,$_POST);
            $arrForm = $objFormParam->getHashArray();
            $this->arrErr = $this->lfCheckError_Edit($objFormParam,$objUpFile);
            //エラーがない場合
            if(count($this->arrErr) == 0){
                //データベースに登録
                $this->lfRegistImages($objUpFile,$arrForm);
                //tempファイルをsaveフォルダに保存
                $this->lfSaveUploadFiles($objUpFile);
                $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . 'MainImage/templates/plg_MainImage_admin_main_image_complete.tpl';
            }
            else{
                $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile,$arrForm);
            }
            break;
            
        default:
            $arrForm = $this->lfGetMainImages($objUpFile);
            $this->arrForm = $this->lfSetViewParam_InputPage($objUpFile,$arrForm);
            break;
        }
        
        //画像の行番号を取得
        if(!empty($this->arrForm['arrFile']['image'])){
            
            $this->arrImageIndexes = array_keys($this->arrForm['arrFile']['image']);
            
            if(count($this->arrImageIndexes) < $this->arrPlugin['max_registration'] || $this->arrPlugin['max_registration'] == 0){
                //新規画像用にインデックス追加
                $this->arrImageIndexes[] = max($this->arrImageIndexes)+1;
            }
        }
        else{
            
            $this->arrImageIndexes = array(1);
        }
    }
    
    
    /**
     * アップロードしたtempファイルをsaveフォルダに保存する
     *
     * @param object $objUpFile plg_MainImage_SC_UploadFileインスタンス
     * @return void
     **/
    function lfSaveUploadFiles(&$objUpFile){
        $objImage = new SC_Image_Ex($objUpFile->temp_dir);
        
        foreach($objUpFile->temp_file as $arrTempFiles){
            if(is_array($arrTempFiles)){
                foreach($arrTempFiles as $temp_file){
                    if(!empty($temp_file)){
                        $objImage->moveTempImage($temp_file,$objUpFile->save_dir);
                    }
                }
            }
        }
    }
    
    /**
     * DBからメインイメージのデータを取得する
     * 
     * @return array メインイメージデータ配列
     **/
    function lfGetMainImagesData_FromDB(){
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrMainImages = $objQuery->select('*','dtb_main_images');
        $arrFields = $this->arrFields;
        $arrRet = array();
        
        //データ用の配列を作成
        foreach($arrFields as $field){
            $arrRet[$field] = array();
        }
        
        foreach($arrMainImages as $arrMainImage){
            foreach(array_keys($arrRet) as $key){
                $arrRet[$key][] = $arrMainImage[$key];
            }
        }
        return $arrRet;
    }
    
    /**
     * フォームパラメーター取得
     * 
     * @param object $objUpFile plg_MainImage_SC_UploadFileインスタンス
     * @return array フォームパラメーター配列
     **/
     function lfGetMainImages(&$objUpFile){
         $arrForm = $this->lfGetMainImagesData_FromDB();
         $objUpFile->setDBFileList($arrForm);
         return $arrForm;
     }
    
    /**
     * テーブルを空にし、メインイメージのデータを登録する。
     * 
     * @param object $objUpFile plg_MainImage_SC_UploadFileインスタンス
     * @param array $arrList フォーム入力パラメーター配列
     * @return void
     **/
     function lfRegistImages(&$objUpFile,$arrList){
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $image_key = $this->image_key;
        
        $keyname_key = $objUpFile->getKeynameKey($image_key);
        $arrTempFiles = $objUpFile->temp_file[$keyname_key];
        $arrSaveFiles = $objUpFile->save_file[$keyname_key];
        //$objUpFile->temp_fileと$objUpFile->save_fileに含まれる全てのキーを取得
        $arrImageNumbers = $objUpFile->getAllFileKeys($keyname_key);
        $table = 'dtb_main_images';
        //テーブルを空にする
        $objQuery->delete($table);
        
        foreach($arrImageNumbers as $image_number){
            //deleteにチェックされていたら
            if(!empty($arrList['delete'][$image_number])){
                //一時ファイルを削除し、この画像は登録しない
                $objUpFile->deleteFile($image_key,$image_number);
                 continue;
            }
            $sqlVal['id'] = $objQuery->max('id', $table) + 1;
            //$sqlVal[$image_key]は空にしておく
            $sqlVal[$image_key] = '';
            $arrFields = $this->arrFields;
            foreach($arrFields as $field){
                //$sqlVal[$image_key]にこの時点でデータが入らないようにする
                if(isset($arrList[$field][$image_number]) && $field != $this->image_key){
                    $sqlVal[$field] = $arrList[$field][$image_number];
                }
            }
            //一時ファイルが存在するなら
            if(!empty($arrTempFiles[$image_number])){
                //$sqlVal[$image_key]に一時ファイルの情報を代入
                $sqlVal[$image_key] = $arrList['temp_'.$image_key][$image_number];
            }
            //保存済みファイルが存在するなら
            else if(!empty($arrSaveFiles[$image_number])){
                //$sqlVal[$image_key]に保存済みファイルの情報を代入
                $sqlVal[$image_key] = $arrList['save_'.$image_key][$image_number];
            }
            if(!empty($sqlVal[$image_key])){
                $objQuery->insert($table,$sqlVal);
            }
        }
     }
    

    /**
     * フォーム入力パラメーターのエラーチェック
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Exインスタンス
     * @param plg_MainImage_SC_UploadFile_Ex $objUpFile plg_MainImage_SC_UploadFile_Exインスタンス
     * @param array $arrForm フォーム入力パラメーター配列
     * @return array エラー情報を格納した連想配列
     */
    function lfCheckError_Edit(&$objFormParam, &$objUpFile) {

        $arrFormErr = (array)$objFormParam->checkError();
        $arrFileErr = (array)$objUpFile->checkExists();
        $arrErr = plg_MainImage_SC_utils_Ex::sfMerge($arrFormErr,$arrFileErr);
        
        return $arrErr;
    }
    

    /**
     * 表示用フォームパラメーター取得
     * - 入力画面
     *
     * @param object $objUpFile plg_MainImage_SC_UploadFileインスタンス
     * @param array $arrForm フォーム入力パラメーター配列
     * @return array 表示用フォームパラメーター配列
     */    
    function lfSetViewParam_InputPage(&$objUpFile, $arrForm){
        $arrForm['arrHidden'] = $objUpFile->getHiddenFileList();
        $arrForm['arrFile'] = $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH,IMAGE_SAVE_URLPATH);
        return $arrForm;
    }
    
    /**
     * パラメーター情報の初期化
     * 
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Exインスタンス
     * @param array $arrPost $_POSTデータ
     * @return void
     **/
    function lfInitFormParam(&$objFormParam,$arrPost){
        $image_key = $this->image_key;
        $objFormParam->addParam('タイトル','title',STEXT_LEN,'KVa',array('MAX_LENGTH_CHECK','SPTAB_CHECK'),'',true,true);
        $objFormParam->addParam('PC用URL','url_pc',URL_LEN, 'a', array('SPTAB_CHECK','URL_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('スマートフォン用URL','url_sp',URL_LEN, 'a', array('SPTAB_CHECK','URL_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('モバイル用URL','url_mb',URL_LEN, 'a', array('SPTAB_CHECK','URL_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('メインイメージ画像','image','','',array(),'',true,true);
        $objFormParam->addParam('新しいウィンドウで開く','target_blank_pc',INT_LEN,'n',array('NUM_CHECK','MAX_LENGTH_CHECK','SPTAB_CHECK'));
        $objFormParam->addParam('新しいウィンドウで開く','target_blank_sp',INT_LEN,'n',array('NUM_CHECK','MAX_LENGTH_CHECK','SPTAB_CHECK'));
        $objFormParam->addParam('新しいウィンドウで開く','target_blank_mb',INT_LEN,'n',array('NUM_CHECK','MAX_LENGTH_CHECK','SPTAB_CHECK'));
        $objFormParam->addParam('PC表示','hidden_pc',INT_LEN,'n',array('NUM_CHECK','MAX_LENGTH_CHECK','SPTAB_CHECK'));
        $objFormParam->addParam('モバイル表示','hidden_mb',INT_LEN,'n',array('NUM_CHECK','MAX_LENGTH_CHECK','SPTAB_CHECK'));
        $objFormParam->addParam('スマートフォン表示','hidden_sp',INT_LEN,'n',array('NUM_CHECK','MAX_LENGTH_CHECK','SPTAB_CHECK'));
        $objFormParam->addParam('削除','delete',INT_LEN,'n',array('NUM_CHECK','MAX_LENGTH_CHECK','SPTAB_CHECK'));
        $objFormParam->addParam('temp_'.$image_key,'temp_'.$image_key,'','',array(),'',true,true);
        $objFormParam->addParam('save_'.$image_key,'save_'.$image_key,'','',array(),'',true,true);
        $objFormParam->addParam('image_number','image_number','','',array());
        $objFormParam->setParam($arrPost);
        $objFormParam->convParam();
    }
        
      
    /**
     *　アップロードファイルパラメーター情報の初期化
     * - 画像ファイル用
     * 
     * @param object $objUpFile plg_MainImage_SC_UploadFileインスタンス
     * @return void
     **/
    function lfInitFile(&$objUpFile){
        $objUpFile->addFile('メインイメージ画像', $this->image_key, array('jpg','gif','png'),IMAGE_SIZE, false, $this->arrPlugin['image_width'], $this->arrPlugin['image_height'], true, true);
    }

    /**
     * 縮小画像生成
     *
     * @param object $objUpFile plg_MainImage_SC_UploadFileインスタンス
     * @param string $keyname 画像キー
     * @param boolean $forced
     * @return void
     */
    function lfMakeScaleImage(&$objUpFile, $image_number, $forced = false){
        $image_key = $this->image_key;
        $keyname_key = $objUpFile->getKeynameKey($image_key);
        $arrTempFile = $objUpFile->temp_file;
        $arrSaveFile = $objUpFile->save_file;
        //一時ファイルが存在するなら
        if(!empty($objUpFile->temp_file[$keyname_key][$image_number])){
            $path = $arrTempFile[$keyname_key][$image_number];
            if(file_exists($path)){
                //画像サイズを取得する
                $to_w = $objUpFile->width[$keyname_key];
                $to_h = $objUpFile->height[$keyname_key];
                
                if($forced){
                    $objUpFile->save_file[$keyname_key][$image_number] = '';
                }
                if(empty($arrTempFile[$keyname_key])
                    && empty($arrSaveFile[$keyname_key])
                ){
                    $dst_file = $objUpFile->lfGetTmpImageName(IMAGE_RENAME,'',$arrTempFile[$keyname_key]);
                    $path = $objUpFile->makeThumb($path,$to_w,$to_h,$dst_file);
                    $objUpFile->temp_file[$keyname_key][$image_number] = basename($path);
                }
            }
        }
    }
}