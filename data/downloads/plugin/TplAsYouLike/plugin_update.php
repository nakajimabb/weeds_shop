<?php
/*
 * TplAsYouLike
 * Copyright(c) 2012 SUNATMARK CO.,LTD. All Rights Reserved.
 * http://www.sunatmark.co.jp/
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

/**
 * プラグイン のアップデート用クラス.
 *
 * @package TplAsYouLike
 * @author SUNATMARK CO.,LTD.
 * @version $Id: $
 */
class plugin_update{
   /**
     * アップデート
     * updateはアップデート時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     * 
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function update($arrPlugin) {
        $plugin_dir_path = PLUGIN_UPLOAD_REALDIR . 'TplAsYouLike/';
        SC_Utils_Ex::copyDirectory(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR, $plugin_dir_path);
        
        $objQuery = SC_Query_Ex::getSingletonInstance();
        
        //プラグインIDの取得
        if (in_array($arrPlugin['plugin_version'], array('1.0', '1.0.1', '1.0.2'))) {
            $plugin_id = $objQuery->get('plugin_id', 'dtb_plugin', 'plugin_code = ?', array(plugin_info::$PLUGIN_CODE));
            $arrPluginHookPoint = array(
                'prefilterTransform' => 'prefilterTransform', 
                'LC_Page_Products_List_action_after' => 'productsListActionAfter', 
                'LC_Page_Products_Detail_action_after' => 'productsDetailActionAfter', 
                'LC_Page_Admin_Design_MainEdit_action_confirm' => 'adminDesignMainEditActionConfirm', 
                'LC_Page_Admin_Design_MainEdit_action_after' => 'adminDesignMainEditActionAfter', 
                'LC_Page_Admin_Design_action_after' => 'adminDesignActionAfter', 
                'LC_Page_Admin_Products_Category_action_after' => 'adminProductsCategoryActionAfter', 
                'LC_Page_Admin_Products_Product_action_after' => 'adminProductsProductActionAfter', 
                'SC_FormParam_construct' => 'formParamConstruct'
            );

            $table = 'dtb_plugin_hookpoint';
            $objQuery->begin();
            foreach ($arrPluginHookPoint as $hook_point => $callback) {
                $plugin_hookpoint_id = $objQuery->nextVal('dtb_plugin_hookpoint_plugin_hookpoint_id');
                $sqlval = array();
                $sqlval['plugin_hookpoint_id']  = $plugin_hookpoint_id;
                $sqlval['plugin_id']  = $plugin_id;
                $sqlval['hook_point']  = $hook_point;
                $sqlval['callback']  = $callback;
                $sqlval['create_date'] = 'now()';
                $sqlval['update_date'] = 'now()';
                $objQuery->insert($table, $sqlval);
            }
        }

        //バージョンの更新
        $arrUpdate = array(
            'plugin_name'        => plugin_info::$PLUGIN_NAME,
            'plugin_version'     => plugin_info::$PLUGIN_VERSION,
            'compliant_version'  => plugin_info::$COMPLIANT_VERSION,
            'plugin_description' => plugin_info::$DESCRIPTION
        );
        $objQuery->update('dtb_plugin', $arrUpdate, 'plugin_code = ?', array(plugin_info::$PLUGIN_CODE));
        //$objQuery->update('dtb_plugin', array('plugin_version' => '1.0.3', 'compliant_version' => '2.11～2.13.0', 'update_date' => 'CURRENT_TIMESTAMP'), 'plugin_code = ?', array('TplAsYouLike'));
        $objQuery->commit();
    }
}
?>