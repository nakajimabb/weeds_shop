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

/**
 * スマートフォン・携帯アクセス制御プラグイン の情報クラス.
 *
 * @package AccessControlSpMb
 * @author C-Rowl Co., Ltd.
 */
class plugin_info {
    /** プラグインコード(必須)：システム上でのキーとなります。プラグインコードは一意である必要があります。 */
    static $PLUGIN_CODE        = "AccessControlSpMb";
    /** プラグイン名(必須)：プラグイン管理・画面出力（エラーメッセージetc）にはこの値が出力されます。 */
    static $PLUGIN_NAME        = "スマートフォン・携帯アクセス制御";
    /** プラグインメインクラス名(必須)：本体がプラグインを実行する際に呼ばれるクラス。拡張子は不要です。 */
    static $CLASS_NAME         = "AccessControlSpMb";
    /** プラグインバージョン(必須) */
    static $PLUGIN_VERSION     = "1.0";
    /** 本体対応バージョン(必須) */
    static $COMPLIANT_VERSION  = "2.12.2";
    /** 作者(必須) */
    static $AUTHOR             = "株式会社 C-Rowl";
    /** 説明(必須) */
    static $DESCRIPTION        = "スマートフォン・携帯端末からのアクセスの挙動を設定できます。スマートフォンからアクセス時に固定でPC画面表示や、PC画面表示ボタンの設置、携帯アクセス時に静的HTMLの表示に対応が可能です。";
    /** 作者用のサイトURL：設定されている場合はプラグイン管理画面の作者名がリンクになります。 */
    static $AUTHOR_SITE_URL    = "http://www.c-rowl.com/";
    /** プラグインのサイトURL : 設定されている場合はプラグイン管理画面の作者名がリンクになります。 */
    static $PLUGIN_SITE_URL   = "";
    /** 使用するフックポイント：使用するフックポイントを設定すると、フックポイントが競合した際にアラートが出ます。 */
    static $HOOK_POINTS        = "loadClassFileChange";
    /** ライセンス */
    static $LICENSE        = "LGPL";
}
?>
