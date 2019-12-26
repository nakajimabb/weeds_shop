<?php
class plugin_info{
    /** プラグインコード(必須)：プラグインを識別する為キーで、他のプラグインと重複しない一意な値である必要がありま. */
    static $PLUGIN_CODE       = "TestCode";
    /** プラグイン名(必須)：EC-CUBE上で表示されるプラグイン名. */
    static $PLUGIN_NAME       = "テストコード";
    /** プラグインバージョン(必須)：プラグインのバージョン. */
    static $PLUGIN_VERSION    = "0.5";
    /** 対応バージョン(必須)：対応するEC-CUBEバージョン. */
    static $COMPLIANT_VERSION = "2.12, 2.13";
    /** 作者(必須)：プラグイン作者. */
    static $AUTHOR            = "S.Nakajima";
    /** 説明(必須)：プラグインの説明. */
    static $DESCRIPTION       = "テストコードを実行します。";
    /** プラグインURL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $PLUGIN_SITE_URL   = "http://www.ec-cube.net/";
    /** プラグイン作者URL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $AUTHOR_SITE_URL   = "http://www.ec-cube.net/";
    /** クラス名(必須)：プラグインのクラス（拡張子は含まない） */
    static $CLASS_NAME       = "TestCode";
    /** フックポイント：フックポイントとコールバック関数を定義します */
    static $HOOK_POINTS       = array(
        array("prefilterTransform", 'prefilterTransformTestCode'));
    /** ライセンス */
    static $LICENSE        = "LGPL";
}
?>