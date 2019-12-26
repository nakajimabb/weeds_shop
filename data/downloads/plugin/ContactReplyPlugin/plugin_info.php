<?php
/**
 * プラグインの登録情報
 */
class plugin_info{
    /** プラグインコード(必須)：プラグインを識別する為キーで、他のプラグインと重複しない一意な値である必要がありま. */
    static $PLUGIN_CODE       = "ContactReplyPlugin";
    /** プラグイン名(必須)：EC-CUBE上で表示されるプラグイン名. */
    static $PLUGIN_NAME       = "お問い合わせ管理プラグイン";
    /** プラグインバージョン(必須)：プラグインのバージョン. */
    static $PLUGIN_VERSION    = "1.0";
    /** 対応バージョン(必須)：対応するEC-CUBEバージョン. */
    static $COMPLIANT_VERSION = "2.12.3";
    /** 作者(必須)：プラグイン作者. */
    static $AUTHOR            = "エスキュービズム";
    /** 説明(必須)：プラグインの説明. */
    static $DESCRIPTION       = "EC-CUBEの管理画面でお問い合わせを管理することができます。また、管理画面から直接返信することも可能です。";
    /** プラグインURL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $PLUGIN_SITE_URL   = "";
    /** プラグイン作者URL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $AUTHOR_SITE_URL   = "http://ec-cube.ec-orange.jp/";
    /** クラス名(必須)：プラグインのクラス（拡張子は含まない） */
    static $CLASS_NAME        = "ContactReplyPlugin";
    /** フックポイント：フックポイントとコールバック関数を定義します */
    static $HOOK_POINTS       = array(
       array("LC_Page_Contact_action_before", 'contact_before'),
       array("LC_Page_Shopping_Confirm_action_before", 'shopping_before'),
       array("prefilterTransform", 'prefilterTransform'));
    /** ライセンス */
    static $LICENSE        = "LGPL";
}
?>
