<?php
/*
 * AutoBackUp
 * Copyright(c) 2013 SUNATMARK CO.,LTD. All Rights Reserved.
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
 * @package AutoBackUp
 * @author SUNATMARK CO.,LTD.
 * @version $Id: $
 */
class plugin_info{
    /** プラグインコード(必須)：システム上でのキーとなります。プラグインコードは一意である必要があります。 */
    static $PLUGIN_CODE         = "AutoBackUp";
    /** プラグイン名(必須)：プラグイン管理・画面出力（エラーメッセージetc）にはこの値が出力されます。 */
    static $PLUGIN_NAME         = "バックアップ機能自動化プラグイン";
    /** プラグインバージョン(必須) */
    static $PLUGIN_VERSION      = "1.0";
    /** 本体対応バージョン(必須) */
    static $COMPLIANT_VERSION   = "2.12.5";
    /** 作者(必須) */
    static $AUTHOR              = "株式会社サンアットマーク";
    /** 説明(必須) */
    static $DESCRIPTION         = "既存のバックアップ機能を1日に1回自動で行えるようになります";
    /** プラグインのサイトURL : 設定されている場合はプラグイン管理画面の作者名がリンクになります。 */
    static $PLUGIN_SITE_URL     = "http://www.sunatmark.co.jp/";
    /** 作者用のサイトURL：設定されている場合はプラグイン管理画面の作者名がリンクになります。 */
    static $AUTHOR_SITE_URL     = "http://www.sunatmark.co.jp/";
    /** プラグインメインクラス名(必須)：本体がプラグインを実行する際に呼ばれるクラス。拡張子は不要です。 */
    static $CLASS_NAME          = "AutoBackUp";
    /** 使用するフックポイント：使用するフックポイントを設定すると、フックポイントが競合した際にアラートが出ます。 */
    static $HOOK_POINTS         = "formParamConstruct,prefilterTransform,LC_Page_preProcess";
    /** ライセンス */
    static $LICENSE             = "LGPL";
}
?>