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

class plugin_update{
    function update($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // バージョンの更新
        $plugin_id = $arrPlugin['plugin_id'];
        $plugin_version = '1.0';  // 新しいバージョン
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $sqlval = array();
        $table = "dtb_plugin";
        $sqlval['plugin_version'] = $plugin_version;
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_id = ?";
        $objQuery->update($table, $sqlval, $where, array($plugin_id));
        $objQuery->commit();
        // 変更ファイルの上書き
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "/AccessControlSpMb.php", PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/AccessControlSpMb.php");
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "/LC_Page_Plugin_AccessControlSpMb_Config.php", PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/LC_Page_Plugin_AccessControlSpMb_Config.php");
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "/templates/config.tpl", PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/templates/config.tpl");
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "/templates/config.tpl", PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/templates/config.tpl");
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "/templates/default/plg_AccessControlSpMb_index.tpl", PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/templates/default/plg_AccessControlSpMb_index.tpl");
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "/templates/sphone/plg_AccessControlSpMb_index.tpl", PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/templates/sphone/plg_AccessControlSpMb_index.tpl");
    }
}

?>
