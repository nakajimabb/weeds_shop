<?php
/*
 * mo03 PluginDownloader
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


/* 
 * インストールされているプラグインのアーカイブを生成し、ダウンロードします
 */
class PluginDownloader extends SC_Plugin_Base {

    /**
     * コンストラクタ
     * プラグイン情報(dtb_plugin)をメンバ変数をセットします.
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    
    function install($arrPlugin) {
        if(copy(PLUGIN_UPLOAD_REALDIR . "mo03/logo.png", PLUGIN_HTML_REALDIR . "mo03/logo.png") === false);
    }

    
    function uninstall($arrPlugin) {

        // nop
    }
    
    
    function enable($arrPlugin) {
        // nop
    }

    
    function disable($arrPlugin) {
        // nop
    }

    /**
     * 該当のプラグインのアーカイブを生成し、ダウンロードします.
     * 
     * @param LC_Page_Products_List $objPage 
     * @return void
     */
    function package_plugin($objPage) {

        $mode = $objPage->getMode();
        switch ($mode) {
            case 'download':
                $plugin_code = $_POST['plugin_code'];
                PluginDownloader::downloadArchiveFiles(PLUGIN_UPLOAD_REALDIR . $plugin_code, $plugin_code);
        }
    }
    
      /**
     * アーカイブしダウンロードさせる
     * @param string $dir アーカイブを行なうディレクトリ
     * @param string $plugin_code プラグインコード
     * @return boolean 成功した場合 true; 失敗した場合 false
     */
    function downloadArchiveFiles($dir, $plugin_code) {
        // ダウンロードされるファイル名
        $dlFileName = $plugin_code . '_' . date('YmdHis') . '.tar.gz';

        $debug_message = $dir . ' から ' . $dlFileName . " を作成します...\n";
        // ファイル一覧取得
        $arrFileHash = SC_Helper_FileManager_Ex::sfGetFileList($dir);
        $arrFileList = array();
        foreach ($arrFileHash as $val) {
            $arrFileList[] = $val['file_name'];
            $debug_message.= '圧縮：'.$val['file_name']."\n";
        }
        GC_Utils_Ex::gfPrintLog($debug_message);

        // ディレクトリを移動
        chdir($dir);
        // 圧縮をおこなう
        $tar = new Archive_Tar($dlFileName, true);
        if ($tar->create($arrFileList)) {
            // ダウンロード用HTTPヘッダ出力
            $file = $dlFileName;
            $file_length = filesize($file);
            header("Content-Disposition: attachment; filename=$file");
            header("Content-Length:$file_length");
            header("Content-Type: application/octet-stream");
            header("Connection: close");
            ob_end_clean(); // 出力バッファをクリア
            readfile($dlFileName,FILE_BINARY);
            unlink($dir . '/' . $dlFileName);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * プレフィルタコールバック関数
     *
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . 'mo03/templates/';
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC:
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                // プラグイン管理
                if (strpos($filename, 'ownersstore/plugin.tpl') !== false) {
                        $objTransform->select('a.update_link')->insertBefore(file_get_contents($template_dir . "mo03_admin_ownersstore_plugin_download_add.tpl"));
                }
                break;
        }
        $source = $objTransform->getHTML();
    }
    
    
}

?>
