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

require_once CLASS_REALDIR . 'helper/SC_Helper_Customer.php';

/**
 * CSV関連のヘルパークラス(拡張).
 *
 * LC_Helper_Customer をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author EC-CUBE CO.,LTD.
 * @version $Id:SC_Helper_DB_Ex.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class SC_Helper_Customer_Ex extends SC_Helper_Customer
{
    function sfCustomerEntryErrorCheck2(&$objFormParam) {
        
        $arrErr = SC_Helper_Customer::sfCustomerEntryErrorCheck($objFormParam);
        $customer_id = null;

        $staff_no   = $objFormParam->getValue('staff_no');
        $name01     = $objFormParam->getValue('name01');
        //$name02     = $objFormParam->getValue('name02');

        if(!empty($staff_no)) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $col    = 'customer_id, staff_no, name01, name02, email';
            $table  = 'dtb_customer';
            $where  = 'staff_no = ?';
            $arrWhereVal[0] = $staff_no;            
            
            $result = $objQuery->getRow($col, $table, $where, $arrWhereVal);

            if(empty($result)) {
                $arrErr['staff_no'] = '指定された社員番号が存在しません。';
            }
            else {
                if(!empty($result['email'])) {
                    $arrErr['staff_no'] = '指定された社員番号は既に登録済です。';
                }
                //else if($name01 != $result['name01']) || $name02 != $result['name02']) {
                else if(!strstr($name01, $result['name01'])) {
                    $arrErr['staff_no'] = '社員番号と氏名が一致しません。';
                }
                else {
                    $customer_id = $result['customer_id'];
                    if(empty($arrErr)) { 
                        $objFormParam->setValue('name01', $result['name01']);
                        $objFormParam->setValue('name02', $result['name02']);
                    }
                }
            }
        }
        
        return array($arrErr, $customer_id);
    }

    function sfSetSearchParam(&$objFormParam) {
        SC_Helper_Customer::sfSetSearchParam($objFormParam);

        $objFormParam->addParam('社員番号', 'search_staff_no', MTEXT_LEN, 'a', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('受け取り店舗', 'search_default_shop_id', ID_MAX_LEN, 'n', array('NUM_CHECK','MAX_LENGTH_CHECK'));
    }
}
