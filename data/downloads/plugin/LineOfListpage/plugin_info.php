<?php
/*
 * LineOfListpage
 * Copyright (C) 2013 BLUE STYLE All Rights Reserved.
 * http://bluestyle.jp/
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
 * @package LineOfListpage
 * @author BLUE STYLE
 */
class plugin_info{
    /** プラグインコード(必須)：プラグインを識別する為キーで、他のプラグインと重複しない一意な値である必要がありま. */
    static $PLUGIN_CODE       = "LineOfListpage";
    /** プラグイン名(必須)：EC-CUBE上で表示されるプラグイン名. */
    static $PLUGIN_NAME       = "横並び商品一覧プラグイン";
    /** プラグインバージョン(必須)：プラグインのバージョン. */
    static $PLUGIN_VERSION    = "1.3";
    /** 対応バージョン(必須)：対応するEC-CUBEバージョン. */
    static $COMPLIANT_VERSION = "2.12・2.13";
    /** 作者(必須)：プラグイン作者. */
    static $AUTHOR            = "BLUE STYLE";
    /** 説明(必須)：プラグインの説明. */
    static $DESCRIPTION       = "商品一覧ページを横並び表示にするプラグインです。1カラム、2カラムは横4列に、3カラムは横3列に表示し、行で高さを揃えたり、角丸（半径3px）の枠線（1px）を表示することができます。プラグイン設定で表示非表示を設定出来ます。デフォルトでは表示されない「商品コード」と「在庫数」も表示できます。";
    /** プラグインURL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $PLUGIN_SITE_URL   = "http://bluestyle.jp/";
    /** プラグイン作者URL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $AUTHOR_SITE_URL   = "http://bluestyle.jp/";
    /** クラス名(必須)：プラグインのクラス（拡張子は含まない） */
    static $CLASS_NAME       = "LineOfListpage";
    /** フックポイント：フックポイントとコールバック関数を定義します */
    static $HOOK_POINTS       = array(
        array("prefilterTransform", 'prefilterTransform'));
    /** ライセンス */
    static $LICENSE        = "LGPL";
}
?>