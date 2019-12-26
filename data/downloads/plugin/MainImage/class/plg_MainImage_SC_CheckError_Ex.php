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

require_once CLASS_REALDIR . 'SC_CheckError.php';

/**
 * メインイメージ用のエラーチェッククラス
 *
 * @author DELIGHT CO.,LTD.
 * @version $
 */
class plg_MainImage_SC_CheckError_Ex extends SC_CheckError {
    
    /**
     * 拡張子の判定
     * 受け取りがない場合エラーを返す
     * value[0] = 項目名
     * value[1] = keyname
     * value[2] = ファイルキー
     * value[3] = ファイル名
     * value[4] = array(拡張子)
     **/
    function MY_FILE_EXT_CHECK($value){
        if(isset($this->arrErr[$value[1]]) || $value[3] == ''){
            return;
        }
        
        $match = false;
        if(strlen($value[1]) > 0){
            $filename = $value[3];
            foreach($value[4] as $checkExt){
                $match = preg_match(sprintf('/%s$/i',preg_quote('.'.$checkExt)),$filename) >= 1;
                if($match === true) break ;
            }
        }
        if($match === false){
            $str_ext = implode(', ', $value[4]);
            $this->arrErr[$value[1]][$value[2]] = '※ ' . $value[0] . 'で許可されている形式は、' . $str_ext . 'です。<br />';
        }
    }

    /**
     * ファイルサイズの判定
     * value[0] = 項目名
     * value[1] = keyname
     * value[2] = ファイルキー
     * value[3] = ファイルサイズ
     * value[4] = 上限サイズ(KB)
     **/
    function MY_FILE_SIZE_CHECK($value) {
        if (isset($this->arrErr[$value[1]])) {
            return;
        }
        
        if(method_exists($this, 'createParam')){
            //必要？
            $this->createParam($value);
        }
        
        if ($value[3] > $value[4] *  1024) {
            $byte = 'KB';
            if ($value[4] >= 1000) {
                $value[4] = $value[4] / 1000;
                $byte = 'MB';
            }
            $this->arrErr[$value[1]][$value[2]] = '※ ' . $value[0] . 'のファイルサイズは' . $value[4] . $byte . '以下のものを使用してください。<br />';
        }
    }
}
