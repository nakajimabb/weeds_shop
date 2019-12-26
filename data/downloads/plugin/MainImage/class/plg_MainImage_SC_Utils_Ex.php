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
require_once CLASS_REALDIR . 'util/SC_Utils.php';

/**
 * メインイメージのユーティリティクラス
 *
 * @package Util
 * @author DELIGHT CO.,LTD.
 * @version $
 */
class plg_MainImage_SC_Utils_Ex extends SC_Utils {
    
    /**
     * 2つ以上の配列を再帰的に、添え字を振りなおし、連想配列の値を上書きして返す。
     * @param array $arrData マージされる配列
     * @param array $merge マージする配列
     * @return array マージされたデータ
     * $a = array('hoge'=>array('a'=>'A','b'=>'B'),'fuga'=>array(3,2,1));
     * $b = array('hoge'=>array('a'=>'Updated','c'=>'C'),'fuga'=>array(10=>10,100=>100));
     * print_r(sfMerge($a,$b));
     *  array(
     *      [hoge] => array(
     *          [a] => 'Updated',
     *          [b] => 'B',
     *          [c] => 'C'
     *      ),
     *      [fuga] => array(
     *          [0] => 3,
     *          [1] => 2,
     *          [2] => 1,
     *          [3] => 10,
     *          [4] => 100
     *      )
     *  )
     */
    public static function sfMerge($arrData, $merge = null) {
        $arrArgs = func_get_args();
        if (empty($arrArgs[1]) && count($arrArgs) <= 2) {
            return (array)$arrArgs[0];
        }
        if (!is_array($arrArgs[0])) {
            $arrArgs[0] = (array)$arrArgs[0];
        }
        return call_user_func_array(array(__CLASS__, '_sfMerge'), $arrArgs);
    }

    /**
     * sfMergeの内部処理
     * @param array $arrData マージされる配列
     * @param array $merge マージする配列
     * @return array マージされたデータ
     */
    public static function _sfMerge(array $arrData, $merge) {
        $arrArgs = func_get_args();
        $arrRet = current($arrArgs);
        
        while (($arrArg = next($arrArgs)) !== false) {
            foreach ((array)$arrArg as $key => $val) {
                if (!empty($arrRet[$key]) && is_array($arrRet[$key]) && is_array($val)) {
                    $arrRet[$key] = self::_sfMerge($arrRet[$key], $val);
                } elseif (is_int($key)) {
                    $arrRet[] = $val;
                } else {
                    $arrRet[$key] = $val;
                }
            }
        }
        return $arrRet;
    }
}
