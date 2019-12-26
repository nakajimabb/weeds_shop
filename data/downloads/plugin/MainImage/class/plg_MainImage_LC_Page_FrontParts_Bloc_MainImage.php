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
require_once CLASS_EX_REALDIR . 'page_extends/frontparts/bloc/LC_Page_FrontParts_Bloc_Ex.php';
require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/class/plg_MainImage_SC_UploadFile_Ex.php';
require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/MainImage.php';

/**
 * メインイメージのブロックページクラス.
 *
 * @package Page
 * @author DELIGHT CO.,LTD.
 * @version $
 */
class plg_MainImage_LC_Page_FrontParts_BLoc_MainImage extends LC_Page_FrontParts_Bloc_Ex {
    
    //データベース上のフィールド名
    var $image_key = 'image';
    
    //何番目のメインイメージまで表示したか、のCOOKIEのキー
    var $cookie_key = 'main_image';
    //COOKIEのインスタンス
    var $cookie;
    
    var $arrPlugin;
    
    function init(){
        parent::init();
        $this->cookie = new SC_Cookie_Ex();
        $this->arrPlugin = MainImage::getNamedPluginInfo();
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
        $objUpFile = new plg_MainImage_SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        $this->lfInitFile($objUpFile);
        
        $arrMainImages = $this->lfGetMainImages($objUpFile,$this->cookie->getCookie($this->cookie_key));
        $this->arrMainImages = $this->lfSetViewParam($objUpFile,$arrMainImages);
    }
    
    /**
     * フォームパラメーター取得
     * 
     * @param object $objUpFile plg_MainImage_SC_UploadFile_Exインスタンス
     * @return array フォームパラメーター配列
     **/
     function lfGetMainImages(&$objUpFile){
         //デバイスタイプを取得する
         $device_type = SC_Display_Ex::detectDevice();
         switch($device_type){
             //携帯の場合
             case DEVICE_TYPE_MOBILE:
                 //オフセット有りのデータを取得
                 $arrMainImages = $this->lfGetMainImagesOffsetData_FromDB($this->cookie->getCookie($this->cookie_key));
                 break;
             
             default:
                 $arrMainImages = $this->lfGetMainImagesData_FromDB();
                 break;
         }
         $arrMainImages = $this->lfConvertMainImagesData($arrMainImages);
         $objUpFile->setDBFileList($arrMainImages);
         return $arrMainImages;
     }
     
     /**
      * DBから取得したメインイメージデータ配列を表示用の配列に変換
      * @param type $arrData DBから取得したメインイメージデータ配列
      * @return array 変換されたメインイメージデータ配列
      */
     function lfConvertMainImagesData($arrMainImages){
        $arrRet = array();
        foreach($arrMainImages as $key => $arrMainImage){
            foreach($arrMainImage as $field => $value){
                $arrRet[$field][$key] = $value;
            }
        }
         return $arrRet;
     }
    
    /**
     * DBからメインイメージのデータを取得する。
     * @return array メインイメージデータ配列
     **/
    function lfGetMainImagesData_FromDB(){
        
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = '';
        $arrWhereValues = array(0);
        
        //デバイスタイプを取得する
        $device_type = SC_Display_Ex::detectDevice();
        switch($device_type){
            case DEVICE_TYPE_PC:
                $where = 'hidden_pc = ?';
                break;
            
            case DEVICE_TYPE_MOBILE:
                $where = 'hidden_mb = ?';
                break;
            
            case DEVICE_TYPE_SMARTPHONE:
                $where = 'hidden_sp = ?';
                break;
        }
        
        $objQuery->setOrder('id ASC');
        $arrMainImages = $objQuery->select('*','dtb_main_images',$where,$arrWhereValues);
        return $arrMainImages;
    }
    
    function lfGetMainImagesOffsetData_FromDB($rank = null){
        
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = '';
        $arrWhereValues = array(0);
        
        //$rankがセットされている場合、$rank+1
        //そうでなければランクを0にリセット
        $offset = is_numeric($rank) ? $rank+1 : 0;
        //オフセット数をCOOKIEに登録する
        $this->cookie->setCookie($this->cookie_key,$offset);
        
        //デバイスタイプを取得する
        $device_type = SC_Display_Ex::detectDevice();
        switch($device_type){
            case DEVICE_TYPE_PC:
                $where = 'hidden_pc = ?';
                break;
            
            case DEVICE_TYPE_MOBILE:
                $where = 'hidden_mb = ?';
                break;
            
            case DEVICE_TYPE_SMARTPHONE:
                $where = 'hidden_sp = ?';
                break;
        }
        
        $objQuery->setLimit(1);
        $objQuery->setOffset($offset);
        $objQuery->setOrder('id ASC');
        $arrMainImages = $objQuery->select('*','dtb_main_images',$where,$arrWhereValues);
        
        if(empty($arrMainImages) && $offset > 0){
            //オフセットした上でデータが取得できなかったら、
            //オフセット0のデータを返す。
            $arrMainImages = $this->lfGetMainImagesOffsetData_FromDB();
        }
        return $arrMainImages;
    }
    
    /**
     * 表示用フォームパラメーター取得
     * - 入力画面
     *
     * @param object $objUpFile SC_UploadFileインスタンス
     * @param array $arrForm フォーム入力パラメーター配列
     * @return array 表示用フォームパラメーター配列
     */    
    function lfSetViewParam(&$objUpFile, $arrForm){
        $arrForm['arrHidden'] = $objUpFile->getHiddenFileList();
        $arrForm['arrFile'] = $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH,IMAGE_SAVE_URLPATH);
        return $arrForm;
    }
    
    /**
     *　アップロードファイルパラメーター情報の初期化
     * - 画像ファイル用
     * 
     * @param object $objUpFile SC_UploadFileインスタンス
     * @return void
     **/
    function lfInitFile(&$objUpFile){
        $objUpFile->addFile('メインイメージ画像', $this->image_key, array('jpg','gif','png'),IMAGE_SIZE, false, TOP_FLASH_IMAGE_WIDTH, TOP_FLASH_IMAGE_HEIGHT, true, true);
    }
}