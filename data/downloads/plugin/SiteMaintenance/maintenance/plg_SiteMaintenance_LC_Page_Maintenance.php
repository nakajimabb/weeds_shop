<?php
/*
 * SiteMaintenance
 *
 * Copyright(c) 2009-2012 CUORE CO.,LTD. All Rights Reserved.
 *
 * http://ec.cuore.jp/
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

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * メンテナンス表示 のページクラス.
 *
 * @package Page
 * @author CUORE CO.,LTD.
 */
class plg_SiteMaintenance_LC_Page_Maintenance extends LC_Page_Ex {


    // {{{ properties

    /** メッセージ */
    var $mainte_msg = "　現在メンテナンス中の為、商品を御購入頂けません。<BR/>　メンテナンス終了まで、しばらくお待ちください。";

    /** 登録メッセージ */
    var $reg_mainte_msg = "";

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        $this->tpl_mainpage = 'site_maintenance/plg_SiteMaintenance_maintenance.tpl';
        $this->tpl_column_num = 1;
        $this->tpl_title = 'メンテナンスのお知らせ';
        // ディスプレイクラス生成
        $this->objDisplay = new SC_Display_Ex();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {

        //メンテナンス中に表示する固定メッセージ
        $this->tpl_msg = $this->mainte_msg;
        //メンテナンス管理で設定されているメンテナンスメッセージを取得する
        $this->tpl_maintenance_msg= "　" . $this->reg_mainte_msg;
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
     * メンテナンスメッセージセット処理
     *
     * @param string $maintenance_msg 表示メッセージ
     */
    function gfSetMaintenanceMsg($maintenance_msg){
        $this->reg_mainte_msg = $maintenance_msg;
    }
}
?>
