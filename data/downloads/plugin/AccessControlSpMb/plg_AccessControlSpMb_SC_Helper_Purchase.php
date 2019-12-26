<?php
/*
 * AccessControlSpMb
 * Copyright(c) C-Rowl Co., Ltd. All Rights Reserved.
 * http://www.c-rowl.com/
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

class plg_AccessControlSpMb_SC_Helper_Purchase extends SC_Helper_Purchase {

    /**
     * 受注一時情報を保存する.
     *
     * 既存のデータが存在しない場合は新規保存. 存在する場合は更新する.
     *
     * @param integer $uniqId 受注一時情報ID
     * @param array $params 登録する受注情報の配列
     * @param SC_Customer $objCustomer SC_Customer インスタンス
     * @return array void
     */
    function saveOrderTemp($uniqId, $params, &$objCustomer = NULL) {
        if (SC_Utils_Ex::isBlank($uniqId)) {
            return;
        }

        // スマートフォンからのアクセス時、PCとして記録されるので対応
        //$params['device_type_id'] = SC_Display_Ex::detectDevice();
        $tmp_device = $_SESSION['plg_accesscontrolspmb_device_session'];
        if (strlen($tmp_device) > 0) {
            $params['device_type_id'] = $tmp_device;
        } else {
            $params['device_type_id'] = SC_Display_Ex::detectDevice();
        }

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        // 存在するカラムのみを対象とする
        $cols = $objQuery->listTableFields('dtb_order_temp');
        $sqlval = array();
        foreach ($params as $key => $val) {
            if (in_array($key, $cols)) {
                $sqlval[$key] = $val;
            }
        }

        $sqlval['session'] = serialize($_SESSION);
        if (!empty($objCustomer)) {
            // 注文者の情報を常に最新に保つ
            $this->copyFromCustomer($sqlval, $objCustomer);
        }
        $exists = $this->getOrderTemp($uniqId);
        if (SC_Utils_Ex::isBlank($exists)) {
            $sqlval['order_temp_id'] = $uniqId;
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            $objQuery->insert('dtb_order_temp', $sqlval);
        } else {
            $objQuery->update('dtb_order_temp', $sqlval, 'order_temp_id = ?', array($uniqId));
        }
    }

}
?>
