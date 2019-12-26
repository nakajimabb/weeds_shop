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

require_once CLASS_REALDIR . 'SC_UploadFile.php';
require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/class/plg_MainImage_SC_Utils_Ex.php';
require_once PLUGIN_UPLOAD_REALDIR . 'MainImage/class/plg_MainImage_SC_CheckError_Ex.php';

/**
 * メインイメージのアップロードファイルクラス.
 *
 * @author DELIGHT CO.,LTD.
 * @version $
 */
class plg_MainImage_SC_UploadFile_Ex extends SC_UploadFile {
    
    //ファイルを配列で扱うか否か    
    var $is_array = array();
    
    function addFile($disp_name, $keyname, $arrExt, $size, $necessary=false, $width=0, $height=0, $image=true, $is_array=false){
        $this->is_array[] = $is_array;
        parent::addFile($disp_name, $keyname, $arrExt, $size, $necessary, $width, $height, $image);
    }
    

    /**
     * アップロードされたファイルを保存する。
     * @param string $keyname 
     * @param type $rename 
     * @param int $file_number ファイル番号
     * @return エラー情報  
     */
    function makeTempFile($keyname, $rename = IMAGE_RENAME, $file_number = NULL) {
        //$keynameのキーを取得
        $keyname_key = $this->getKeynameKey($keyname);
        if(!$this->is_array[$keyname_key]){
            return parent::makeTempFile($keyname,$rename);
        }

        $objErr = new plg_MainImage_SC_CheckError_Ex();
        $arrKeynameFiles = $_FILES[$keyname];
        
        //$keynameファイルの、$file_number番目のファイルサイズが0以上なら
        if($_FILES[$keyname]['size'][$file_number] > 0){
            $arrFile = array(
                'name' => $arrKeynameFiles['name'][$file_number],
                'type' => $arrKeynameFiles['type'][$file_number],
                'tmp_name' => $arrKeynameFiles['tmp_name'][$file_number],
                'error' => $arrKeynameFiles['error'][$file_number],
                'size' => $arrKeynameFiles['size'][$file_number]
            );
            
            //独自バリデーション
            //エラーの場合、$objErr-.arrErr[$keyname][$file_number]にエラー情報が格納される
            $objErr->doFunc(array($this->disp_name[$keyname_key],$keyname,$file_number, $arrFile['name'],$this->arrExt[$keyname_key]),array('MY_FILE_EXT_CHECK'));
            $objErr->doFunc(array($this->disp_name[$keyname_key],$keyname,$file_number, $arrFile['size'],$this->size[$keyname_key]),array('MY_FILE_SIZE_CHECK'));

            //エラーがなければ
            if(empty($objErr->arrErr[$keyname])){
                if($this->image[$keyname_key]){
                    //画像ファイルの場合
                    //保存用のファイル名を取得する
                    $dst_file = $this->lfGetTmpImageName($rename,$keyname, '', $file_number);
                    $this->temp_file[$keyname_key][$file_number] = $this->makeThumb($arrFile['tmp_name'],$this->width[$keyname_key],$this->height[$keyname_key],$dst_file);
                }
                else{
                    //画像ファイル以外の場合
                    //一意なファイル名を作成する
                    if($rename){
                        $uniqname = date('mdHi') . '_' . uniqid('').'.';
                        $this->temp_file[$keyname_key][$file_number] = preg_replace("/^.*\./", $uniqname, $arrFile['name']);
                    }
                    else{
                        $this->temp_file[$keyname_key][$file_number] = $arrFile['name'];
                    }
                    if (move_uploaded_file($arrFile['tmp_name'], $this->temp_dir . $this->temp_file[$keyname_key][$file_number])) {
                        GC_Utils_Ex::gfPrintLog($arrFile['name'].' -> '. $this->temp_dir . $this->temp_file[$keyname_key][$file_number]);
                    } else {
                        $objErr->arrErr[$keyname][$file_number] = '※ ファイルのアップロードに失敗しました。<br />';
                        GC_Utils_Ex::gfPrintLog('File Upload Error!: ' . $arrFile['name'].' -> '. $this->temp_dir . $this->temp_file[$keyname_key][$file_number]);
                    }
                }
            }
        }
        else{
            $objErr->arrErr[$keyname][$file_number] = '※ '. $this->disp_name[$keyname_key] . 'がアップロードされていません。<br />';
        }
        return $objErr->arrErr[$keyname];
    }
    
    
    /**
     * HIDDEN用のファイル名配列を返す
     * @return void 
     */
    function getHiddenFileList() {
        //まず継承元の結果を取得する
        $arrRet = parent::getHiddenFileList();
        $arrKeynameKeys = array_flip($this->keyname);
        foreach($arrKeynameKeys as $keyname => $keyname_key){
            if($this->is_array[$keyname_key]){
                //$this->is_array[$keyname_key]の場合、temp_とsave_を初期化し、再度処理する
                unset($arrRet['temp_'.$keyname]);
                unset($arrRet['save_'.$keyname]);
                //$this->temp_fileと$this->save_fileのキーを取得
                $arrFileKeys = $this->getAllFileKeys($keyname_key);
                foreach($arrFileKeys as $file_key){
                    if(!empty($this->temp_file[$keyname_key][$file_key])){
                    $arrRet['temp_'.$keyname][$file_key] = $this->temp_file[$keyname_key][$file_key];
                    }
                    if(!empty($this->save_file[$keyname_key][$file_key])){
                        $arrRet['save_'.$keyname][$file_key] = $this->save_file[$keyname_key][$file_key];
                    }
                }
            }
        }
        return $arrRet;
    }

    /**
     * フォームに渡す用のファイル情報配列を返す
     * @param string $temp_url 
     * @param string $save_url
     * @param boolean $real_size
     * @return array ファイル情報の配列 
     */
    function getFormFileList($temp_url, $save_url, $real_size = false) {
        //まず継承元の結果を取得する
        $arrRet = parent::getFormFileList($temp_url,$save_url,$real_size);
        
        $arrKeynameKeys = array_flip($this->keyname);
        foreach($arrKeynameKeys as $keyname => $keyname_key){
            if($this->is_array[$keyname_key]){
                //$this->is_array[$keyname_key]の場合、$arrRet[$keyname_key]を初期化し、再度処理する
                unset($arrRet[$keyname]);
                //$this->temp_fileと$this->save_fileのキーを取得
                $arrFileKeys = $this->getAllFileKeys($keyname_key);
                foreach($arrFileKeys as $file_key){
                    //$this->temp_file[$keyname_key][$file_key]が存在するなら
                    if(!empty($this->temp_file[$keyname_key][$file_key])){
                        //パスのスラッシュが連続しないように処理
                        $arrRet[$keyname][$file_key] = array(
                            'filepath' => rtrim($temp_url,'/') . '/' . $this->temp_file[$keyname_key][$file_key],
                            'real_filepath' => $this->temp_dir . $this->temp_file[$keyname_key][$file_key]
                        );
                    }
                    //$this->save_file[$keyname_key][$file_key]が存在するなら
                    else if(!empty($this->save_file[$keyname_key][$file_key])){
                        //パスのスラッシュが連続しないように処理
                        $arrRet[$keyname][$file_key] = array(
                            'filepath' => rtrim($save_url,'/') . '/' . $this->save_file[$keyname_key][$file_key],
                            'real_filepath' => $this->temp_dir . $this->save_file[$keyname_key][$file_key]
                        );
                    }
                    
                    if(!empty($arrRet[$keyname][$file_key]['filepath'])){
                        if($real_size && is_file($arrRet[$keyname][$file_key]['real_filepath'])){
                            $size = getimagesize($arrRet[$keyname][$file_key]['real_filepath']);
                            $arrRet[$keyname][$file_key]['width'] = $size[0];
                            $arrRet[$keyname][$file_key]['height'] = $size[1];
                        }
                        else{
                            $arrRet[$keyname][$file_key]['width'] = $this->width[$keyname_key];
                            $arrRet[$keyname][$file_key]['height'] = $this->height[$keyname_key];
                        }
                        $arrRet[$keyname][$file_key]['disp_name'] = $this->disp_name[$keyname_key];
                    }
                }
            }
        }
        return $arrRet;
    }
    
    /**
     * 画像を削除する
     * @param type $keyname
     * @param type $file_number ファイル番号
     * @return void
     */
    function deleteFile($keyname, $file_number = null) {
        //$file_numberが指定されていない場合、継承元の関数を実行して終了
        if(!isset($file_number)) return parent::deleteFile($keyname);
        
        $objImage = new SC_Image_Ex($this->temp_dir);
        $keyname_key = $this->getKeynameKey($keyname);
        foreach ($this->keyname as $val) {
            if ($val == $keyname) {
                // 一時ファイルの場合削除する。
                if (!empty($this->temp_file[$keyname_key][$file_number])) {
                    $objImage->deleteImage($this->temp_file[$keyname_key][$file_number],$this->temp_dir);
                }
                $this->temp_file[$keyname_key][$file_number] = '';
                $this->save_file[$keyname_key][$file_number] = '';
            }
        }
    }
    
    /**
     * 画像が必須の場合に行う判定
     * @param type $keyname
     * @return array エラー情報 
     */
    function checkExists($keyname = '') {
        $arrRet = parent::checkExists($keyname);
        $cnt = 0;
        foreach($this->keyname as $val){
            if($val == $keyname || $keyname == ''){
                if($this->necessary[$cnt]){
                    if($this->is_array[$this->getKeynameKey($val)]){
                        unset($arrRet[$val]);
                        $arrFileKeys = $this->getAllFileKeys($cnt);
                        foreach($arrFileKeys as $file_key){
                            if(empty($this->save_file[$cnt][$file_key])
                                && empty($this->temp_file[$cnt][$file_key])
                            ){
                                $arrRet[$val][$file_key] = sprintf('※ %sがアップロードされていません。<br />',$this->disp_name[$cnt]);
                            }
                        }
                    }
                }
            }
            $cnt++;
        }
        return $arrRet;
    }
    
    /**
     * save_file[$keyname_key]とtemp_file[$keyname_key]に含まれる全ての添字を重複なくソートして取得
     *
     * @param int $keyname_key 添字(数字)
     * @return array save_file[$keyname_key]とtemp_file[$keyname_key]に含まれる全ての添字
     **/
     function getAllFileKeys($keyname_key){
         
         $arrKeys = array();
         if(!empty($this->temp_file[$keyname_key])){
             $arrKeys = array_merge($arrKeys, array_keys($this->temp_file[$keyname_key]));
         }
         if(!empty($this->save_file[$keyname_key])){
             $arrKeys = array_merge($arrKeys, array_keys($this->save_file[$keyname_key]));
         }
         $arrKeys = array_unique($arrKeys);
         asort($arrKeys);
         return $arrKeys;
     }
     
     /**
      * DBで保存されたファイル名配列をセットする
      *
      * @param array $arrVal DBから取得した配列 
      **/
    function setDBFileList($arrVal){
        parent::setDBFileList($arrVal);
        $arrKeynameKeys = array_flip($this->keyname);
        foreach($arrKeynameKeys as $keyname => $keyname_key){
            if(!empty($this->is_array[$keyname_key])){
                unset($this->save_file[$keyname_key]);
                if(@is_array($arrVal[$keyname])){
                    foreach($arrVal[$keyname] as $file_key => $arrFile){
                        $this->save_file[$keyname_key][$file_key] = $arrFile;
                    }
                }
            }
        }
    }
      
      /**
      * 指定した$keynameの番号を取得する
      *
      * @param string $keyname keyname
      * @return integer keynameの番号
      **/
     function getKeynameKey($keyname){
         $arrKeynameKeys = array_flip($this->keyname);
         return $arrKeynameKeys[$keyname];
     }
     
     /**
      * 一時ファイルのファイル名を生成する
      * 
      * @param boolean $rename リネームするかどうか
      * @param string $keyname
      * @param string $uploadfile
      * @param integer $file_number
      * @return string 
      */
     function lfGetTmpImageName($rename, $keyname = '', $uploadfile = '', $file_number = null) {
         
        $keyname_key = $this->getKeynameKey($keyname);
        if(!$this->is_array[$keyname_key]){
            return parent::lfGetTmpImageName($rename, $keyname, $uploadfile);
        }
         
        if ($rename === true || empty($_FILES[$keyname]['name'][$file_number])) {
            // 一意なIDを取得し、画像名をリネームし保存
            $uniqname = date('mdHi') . '_' . uniqid('');
        } else {
            // アップロードした画像名で保存
            $uploadfile = strlen($uploadfile) > 0 ? $uploadfile : $_FILES[$keyname]['name'][$file_number];
            $uniqname =  preg_replace('/(.+)\.(.+?)$/','$1', $uploadfile);
        }
        $dst_file = $this->temp_dir . $uniqname;
        return $dst_file;
     }
}
