<?php


class TestCode extends SC_Plugin_Base {

    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    function install($arrPlugin) {

    copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/' . 'test_code.php',
         PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . '/' . 'test_code.php');
    copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/logo.png',
         PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . '/' . 'logo.png');
    }

    function uninstall($arrPlugin) {
    }
    
    function enable($arrPlugin) {
        // nop
    }

    function disable($arrPlugin) {
        // nop
    }

    function prefilterTransformTestCode(&$source, LC_Page_Ex $objPage, $filename) {
        // SC_Helper_Transformのインスタンスを生成.
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . $this->arrSelfInfo['plugin_code'] . '/templates/';
        // 呼び出し元テンプレートを判定します.
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE: // モバイル
            case DEVICE_TYPE_SMARTPHONE: // スマホ
                break;
            case DEVICE_TYPE_PC: // PC
                break;
            case DEVICE_TYPE_ADMIN: // 管理画面
                break;
            default:
            
                if (strpos($filename, 'system/subnavi.tpl') !== false) {
                    $objTransform->select('ul', NULL, false)->appendChild(
                        file_get_contents($template_dir . 'test_code_navi.tpl'));
                }
                break;
        }

        $source = $objTransform->getHTML();
    }
}

?>
