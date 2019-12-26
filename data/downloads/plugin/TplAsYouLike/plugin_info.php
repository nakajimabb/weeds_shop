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
 * プラグイン の情報クラス.
 *
 * @package TplAsYouLike
 * @author SUNATMARK CO.,LTD.
 * @version $Id: $
 */
class plugin_info{
    static $PLUGIN_CODE         = "TplAsYouLike";              // プラグインコード
    static $PLUGIN_NAME         = "テンプレートおきにめすまま"; // プラグイン名
    static $PLUGIN_VERSION      = "1.0.4";                       // プラグインバージョン
    static $COMPLIANT_VERSION   = "2.12.0～2.13.1";                     // 対応バージョン
    static $AUTHOR              = "株式会社サンアットマーク";   // プラグイン作者
    static $DESCRIPTION         = "カテゴリー別・商品別に独自テンプレートを設定できます。"; // プラグインの説明
    static $PLUGIN_SITE_URL     = "http://www.sunatmark.co.jp/";// プラグインURL
    static $AUTHOR_SITE_URL     = "http://www.sunatmark.co.jp/";// プラグイン作者URL
    static $CLASS_NAME          = "TplAsYouLike";              // プラグインクラス名
    /** 使用するフックポイント：使用するフックポイントを設定すると、フックポイントが競合した際にアラートが出ます。 */
    static $HOOK_POINTS         = "prefilterTransform,LC_Page_Products_List_action_after,LC_Page_Products_Detail_action_after,LC_Page_Admin_Design_MainEdit_action_confirm,LC_Page_Admin_Design_MainEdit_action_after,LC_Page_Admin_Design_action_after,LC_Page_Admin_Products_Category_action_after,LC_Page_Admin_Products_Product_action_after,SC_FormParam_construct";
    static $LICENSE             = "LGPL"; /** ライセンス */
}
?>