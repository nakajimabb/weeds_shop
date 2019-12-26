<?php
/**
 * プラグイン の情報クラス.
 *
 * @package AdminAuth
 * @author Cyber-Will
 * @version $Id: $
 */
class plugin_info{
    /** プラグインコード(必須)：プラグインを識別する為キーで、他のプラグインと重複しない一意な値である必要がありま. */
    static $PLUGIN_CODE       = "AdminAuth";
    /** プラグイン名(必須)：EC-CUBE上で表示されるプラグイン名. */
    static $PLUGIN_NAME       = "管理者権限設定";
    /** クラス名(必須)：プラグインのクラス（拡張子は含まない） */
    static $CLASS_NAME        = "AdminAuth";
    /** プラグインバージョン(必須)：プラグインのバージョン. */
    static $PLUGIN_VERSION    = "0.1.1";
    /** 対応バージョン(必須)：対応するEC-CUBEバージョン. */
    static $COMPLIANT_VERSION = "2.12.0 〜 2.12.4";
    /** 作者(必須)：プラグイン作者. */
    static $AUTHOR            = "Cyber-Will";
    /** 説明(必須)：プラグインの説明. */
    static $DESCRIPTION       = "管理者権限設定プラグイン";
    /** プラグインURL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $PLUGIN_SITE_URL   = "http://www.cyber-will.co.jp";
    /** プラグインURL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $AUTHOR_SITE_URL   = "http://www.cyber-will.co.jp";
    static $LICENSE           = “LGPL”;
}
?>
