<?php

class plugin_info{
    const PLUGIN_CODE       = "BbDetailMatrixView";
    const PLUGIN_NAME       = "商品詳細マトリクス表示プラグイン";
    const PLUGIN_VERSION    = "0.6";
    const COMPLIANT_VERSION = "2.12.0";
    const AUTHOR            = "ボクブロック株式会社";
    const DESCRIPTION       = "商品詳細ページで商品情報をマトリクス状に表示にする。(PC版のみ)";
    const AUTHOR_SITE_URL   = "http://www.bokublock.jp/";
    const CLASS_NAME        = "BbDetailMatrixView";
    const HOOK_POINTS       = "LC_Page_Products_Detail_action_after,prefilterTransform";
    const LICENSE           = "LGPL";
}

?>
