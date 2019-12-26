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
 * プラグインのメインクラス
 *
 * @package AutoBackUp
 * @author SUNATMARK CO.,LTD.
 * @version $Id: $
 */
require_once CLASS_EX_REALDIR . 'page_extends/admin/system/LC_Page_Admin_System_Bkup_Ex.php';

class AutoBackUp extends SC_Plugin_Base {

    /**
     * コンストラクタ
     * プラグイン情報(dtb_plugin)をメンバ変数をセットします.
     * @param array $arrSelfInfo プラグイン情報
     * @return void
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
        
        $this->bkup_dir = DATA_REALDIR . 'downloads/backup/';
        $this->bkup_ext = '.tar.gz';
    }
    
    /**
     * インストール
     * installはプラグインのインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin plugin_infoを元にDBに登録されたプラグイン情報(dtb_plugin)
     * @return void
     */
    function install($arrPlugin) {
        if (copy(PLUGIN_UPLOAD_REALDIR . "AutoBackUp/logo.png", PLUGIN_HTML_REALDIR . "AutoBackUp/logo.png") === false) {
            $objQuery->delete('dtb_plugin', 'plugin_code = ?', array(get_class()));
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, '', false, PLUGIN_HTML_REALDIR.' に書き込めません。パーミッションをご確認ください。');
        }
        
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
		$arrSql = array(
			"ALTER TABLE dtb_plugin ADD plg_autobackup_execute_datetime TIMESTAMP;",
			"ALTER TABLE dtb_bkup ADD plg_autobackup_section INTEGER;"
		);
		foreach($arrSql as $sql) {
			$objQuery->exec($sql);
		}
        $objQuery->commit();
    }

    /**
     * アンインストール
     * uninstallはアンインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     * 
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function uninstall($arrPlugin) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
		$arrSql = array(
			"ALTER TABLE dtb_plugin DROP plg_autobackup_execute_datetime;",
			"ALTER TABLE dtb_bkup DROP plg_autobackup_section;"
		);
		
		foreach($arrSql as $sql) {
			$objQuery->exec($sql);
		}
        $objQuery->commit();
    }
    
    /**
     * 稼働
     * enableはプラグインを有効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function enable($arrPlugin) {
        // NOP
    }

    /**
     * 停止
     * disableはプラグインを無効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function disable($arrPlugin) {
        // NOP
    }
    
    /**
     * SC_系クラス読込コールバック関数
     *
     * @param string &$classname クラス名
     * @param string &$classpath クラスファイルパス
     * @return void
     */
    function loadClassFileChange(&$classname, &$classpath) {

    }

    /**
     * プレフィルタコールバック関数
     *
     * @param string     &$source  テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage  ページオブジェクト
     * @param string     $filename テンプレートのファイル名
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . get_class() . '/templates/';
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_PC:
                $template_dir .= 'default/';
                break;
            case DEVICE_TYPE_MOBILE:
                $template_dir .= 'mobile/';
                break;
            case DEVICE_TYPE_SMARTPHONE:
                $template_dir .= 'sphone/';
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                break;
        }
        $source = $objTransform->getHTML();
    }
    
    /**
     * LC_Page
     * @param LC_Page_Ex $objPage      Pageオブジェクト
     * @return void
     */
    public function preProcess(LC_Page_Ex $objPage) {
		// プラグイン情報を取得.
		$plugin = SC_Plugin_Util_Ex::getPluginByPluginCode(get_class());
		
		//自動バックアップ「2：なし」または未設定の場合は何もしない
		if ($plugin['free_field1'] != 1) return;
		
		//バックアップに失敗し続けた場合、同一ユーザー毎回アクセスするたびに
		//自動バックアップが動作してしまうので
		//当日に1度でもバックアップ処理を行った場合は行わない
		if (array_key_exists('eccube_autobackup', $_COOKIE)) {
			if ($_COOKIE['eccube_autobackup'] == date('Ymd')) return;
		}
		//cookieの設定
        setcookie("eccube_autobackup", date('Ymd'), time()+86400);
		
		//最終実行日時
		$last_exec_datetime = $plugin['plg_autobackup_execute_datetime'];
		//最終実行日
		$last_exec_date = date('Ymd', strtotime($last_exec_datetime));

		$obj_bk = new LC_Page_Admin_System_Bkup_Ex();
		$obj_bk->bkup_dir = $this->bkup_dir;
		$obj_bk->bkup_ext = $this->bkup_ext;

		//最終実行日が当日の場合は何もしない
		if ($last_exec_date == date('Ymd')) return;
		
		//実行時刻
		$exec_time = $plugin['free_field2'] . ':00';
		$exec_timestamp = strtotime(date('Y/m/d') . ' ' . $exec_time);
		//実行時刻未満の場合は何もしない
		if ($exec_timestamp > strtotime('now')) return;
		
		//バックアップの実行
		//基本、システム設定＞バックアップの自動化なので
		//LC_Page_Admin_System_Bkup.phpから必要な箇所を流用
        $objQuery =& SC_Query_Ex::getSingletonInstance();
		$bkup_name = 'autobukup_' . date('Ymd');
		
		$bk_name_cnt = $objQuery->count('dtb_bkup', 'bkup_name = ?', array($bkup_name));
		if ($bk_name_cnt == 0) {		
			$work_dir = $this->bkup_dir . $bkup_name . '/';
			// バックアップデータの事前削除
			SC_Helper_FileManager_Ex::deleteFile($work_dir);
			// バックアップファイル作成
			$res = $obj_bk->lfCreateBkupData($bkup_name, $work_dir);
			// バックアップデータの事後削除
			SC_Helper_FileManager_Ex::deleteFile($work_dir);
		}
		else {
			$res = false;
		}
		
		if ($res === true) {
			$arrVal = array();
			$arrVal['bkup_name'] = $bkup_name;
			$arrVal['bkup_memo'] = '自動バックアップ';
			$arrVal['plg_autobackup_section'] = 1;
	
			$objQuery->insert('dtb_bkup', $arrVal);
		}
		
		//メールアドレスが設定されていた場合は実行結果を送信
		if ($plugin['free_field3'] != '') {
			$today = date('Y/m/d');
			if ($res !== true) {
				$subject = "自動バックアップデータの作成に失敗しました({$today})";
				$body = "自動バックアップデータの作成に失敗しました({$today})";
				
			}
			else {
				$subject = "自動バックアップデータの作成に成功しました({$today})";
				$body = "自動バックアップデータの作成に成功しました({$today})";
				
			}
				
            $objHelperMail = new SC_Helper_Mail_Ex();
			$objHelperMail->sfSendMail($plugin['free_field3'], $subject, $body);
		}
		
		if ($res !== true) return;
		
		//最終実行時刻の保存
        $objQuery->update('dtb_plugin', array('plg_autobackup_execute_datetime' => 'CURRENT_TIMESTAMP'), "plugin_code = 'AutoBackUp'");
		
		$auto_cnt = $objQuery->count('dtb_bkup', 'plg_autobackup_section = ?', array(1));
		
		//自動バックアップ上限を超えていた場合は削除
		if ($plugin['free_field4'] == '' || $plugin['free_field4'] >= $auto_cnt) return;
		$limit = $auto_cnt - $plugin['free_field4'];
		
		//削除対象の取得
		$where = 'plg_autobackup_section = ?';
		$objQuery->setOrder('create_date', ASC);
		$objQuery->setLimitOffset($limit);
		$arrDel = $objQuery->select('bkup_name', 'dtb_bkup', $where, array(1));
		foreach ($arrDel as $del) {
			$del_file = $this->bkup_dir.$del['bkup_name'] . $this->bkup_ext;
			// ファイルの削除
			if (is_file($del_file)) {
				$ret = unlink($del_file);
			}

			$objQuery->delete('dtb_bkup', 'bkup_name = ?', array($del['bkup_name']));
		}
		
    }

    /**
     * 処理の介入箇所とコールバック関数を設定
     * registerはプラグインインスタンス生成時に実行されます
     * 
     * @param SC_Helper_Plugin $objHelperPlugin 
     */
    function register(SC_Helper_Plugin $objHelperPlugin, $priority) {
        parent::register($objHelperPlugin, $priority);

        //form項目追加
        $objHelperPlugin->addAction('SC_FormParam_construct', 
            array($this, 'formParamConstruct'), $this->arrSelfInfo['priority']);

        //代替クラス
        $objHelperPlugin->addAction("loadClassFileChange", 
            array($this, "loadClassFileChange"), $this->arrSelfInfo['priority']);

        // transform
        $objHelperPlugin->addAction('prefilterTransform', 
            array($this, 'prefilterTransform'), $this->arrSelfInfo['priority']);
        
        
        $objHelperPlugin->addAction('LC_Page_preProcess', 
            array($this, 'preProcess'), $this->arrSelfInfo['priority']);
   }
}
?>
