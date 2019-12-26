<?php
/*
 * CategoryContents
 * Copyright (C) 2012 LOCKON CO.,LTD. All Rights Reserved.
 * http://www.lockon.co.jp/
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


class ContactReplyPlugin extends SC_Plugin_Base {

    /**
     * コンストラクタ
     * @param array $arrSelfInfo dtb_pluginの情報配列
     * @return void
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    /**
     * インストール時に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function install($arrPlugin) {
    	// dtb_categoryに必要なカラムを追加します.
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();


        if (DB_TYPE == 'mysql') {
        $createContact = <<<DOC
   CREATE TABLE `plg_ContactReply_contact` (
  `contact_id` int(11) NOT NULL COMMENT '問い合わせID',
  `name01` text COMMENT '名前01',
  `name02` text COMMENT '名前02',
  `kana01` text COMMENT 'カナ01',
  `kana02` text COMMENT 'カナ02',
  `zip01` text COMMENT '郵便番号01',
  `zip02` text COMMENT '郵便番号02',
  `pref` smallint(6) DEFAULT NULL COMMENT '都道府県',
  `addr01` text COMMENT '住所01',
  `addr02` text COMMENT '住所02',
  `tel01` text COMMENT '電話番号01',
  `tel02` text COMMENT '電話番号02',
  `tel03` text COMMENT '電話番号03',
  `email` text COMMENT 'メールアドレス',
  `contents` text COMMENT '問い合わせ内容',
  `create_date` datetime DEFAULT NULL COMMENT '作成日時',
  `del_flg` smallint(6) DEFAULT '0' COMMENT '削除フラグ',
  `customer_id` int(11) DEFAULT NULL COMMENT '顧客ID',
  `order_id` int(11) DEFAULT NULL COMMENT '注文ID',
  `status` smallint(6) DEFAULT '0' COMMENT 'ステータス',
  PRIMARY KEY (`contact_id`)
);
DOC;

        $createContactReply = <<<DOC
CREATE TABLE `plg_ContactReply_contact_reply` (
  `contact_reply_id` int(11) NOT NULL COMMENT 'お問い合わせ返信ID',
  `contact_id` int(11) NOT NULL COMMENT 'お問い合わせID',
  `direction` smallint(6) DEFAULT NULL COMMENT '宛先',
  `title` text COMMENT 'タイトル',
  `contents` text COMMENT '問い合わせ内容',
  `create_date` datetime DEFAULT NULL COMMENT '作成日時',
  `del_flg` smallint(6) DEFAULT '0' COMMENT '削除フラグ',
  `status` smallint(6) DEFAULT '0' COMMENT 'ステータス',
  PRIMARY KEY (`contact_reply_id`)
);
DOC;

        $createStatus = <<<DOC
CREATE TABLE `mtb_plg_ContactReply_status` (
  `id` smallint(6) NOT NULL DEFAULT '0' COMMENT 'ID',
  `name` text COMMENT '名称',
  `rank` smallint(6) NOT NULL DEFAULT '0' COMMENT 'ランク',
  PRIMARY KEY (`id`)
);
DOC;

    } else if (DB_TYPE == 'pgsql') {

        $createContact = <<<DOC
   CREATE TABLE plg_ContactReply_contact (
  contact_id int NOT NULL,
  name01 text,
  name02 text,
  kana01 text,
  kana02 text,
  zip01 text,
  zip02 text,
  pref smallint DEFAULT NULL,
  addr01 text,
  addr02 text,
  tel01 text,
  tel02 text,
  tel03 text,
  email text,
  contents text,
  create_date timestamp DEFAULT NULL,
  del_flg smallint DEFAULT '0',
  customer_id int DEFAULT NULL,
  order_id int DEFAULT NULL,
  status smallint DEFAULT '0',
  PRIMARY KEY (contact_id)
);
DOC;
        $createContactReply = <<<DOC
CREATE TABLE plg_ContactReply_contact_reply (
  contact_reply_id int NOT NULL,
  contact_id int NOT NULL,
  direction smallint DEFAULT NULL,
  title text,
  contents text,
  create_date timestamp DEFAULT NULL,
  del_flg smallint DEFAULT '0',
  status smallint DEFAULT '0',
  PRIMARY KEY (contact_reply_id)
);
DOC;

        $createStatus = <<<DOC
CREATE TABLE mtb_plg_ContactReply_status (
  id smallint NOT NULL DEFAULT '0',
  name text,
  rank smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
);
DOC;

    }


        $objQuery->query($createContact);
        $objQuery->query($createContactReply);
        $objQuery->query($createStatus);
        $objQuery->insert('mtb_plg_ContactReply_status', array('id' => 0, 'name' => '未読', 'rank' => 0));
        $objQuery->insert('mtb_plg_ContactReply_status', array('id' => 1, 'name' => '既読', 'rank' => 1));
        $objQuery->insert('mtb_plg_ContactReply_status', array('id' => 2, 'name' => '対応中', 'rank' => 2));
        $objQuery->insert('mtb_plg_ContactReply_status', array('id' => 3, 'name' => '対応済', 'rank' => 3));

        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/html/' . 'admin_contact.php',
             PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . '/' . 'admin_contact.php');
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/logo.png',
             PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . '/' . 'logo.png');
        $objQuery->commit();
    }

    /**
     * 削除時に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function uninstall($arrPlugin) {
    	$objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->query("DROP TABLE plg_ContactReply_contact ;");
        $objQuery->query("DROP TABLE plg_ContactReply_contact_reply ;");
        $objQuery->query("DROP TABLE mtb_plg_ContactReply_status ;");
        SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR .  $arrPlugin['plugin_code']);
    }

    /**
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function enable($arrPlugin) {
        // nop
    }

    /**
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function disable($arrPlugin) {
        // nop
    }

    function contact_before($objPage) {

        if ($objPage->getMode() == 'complete') {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objFormParam = new SC_FormParam_Ex();
            $customer_id = $_SESSION['customer']['customer_id'];

            $objPage->lfInitParam($objFormParam);
            $objFormParam->setParam($_POST);
            $arrErr = $objFormParam->checkError();
            $arrForm = $objFormParam->getFormParamList();
            if (SC_Utils_Ex::isBlank($arrErr)) {
                $this->lfRegisterContactData($_POST, $_POST['contents']);
            }
        }
    }

    function shopping_before($objPage) {

        if ($objPage->getMode() == 'confirm') {
            $objCartSess = new SC_CartSession_Ex();
            $objSiteSess = new SC_SiteSession_Ex();
            $objPurchase = new SC_Helper_Purchase_Ex();

            $uniqid = $objSiteSess->getUniqId();
            $arrOrderTemp = $objPurchase->getOrderTemp($uniqid);

            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $order_id = $objQuery->currVal('dtb_order_order_id') + 1;

            $cartKey = $objCartSess->getKey();
            $msg = $objCartSess->checkProducts($cartKey);
            //SC_Utils::sfPrintR($cartKey);
            
            if (!SC_Utils_Ex::isBlank($msg))
                return;
            
            $info['name01'] = $arrOrderTemp['order_name01'];
            $info['name02'] = $arrOrderTemp['order_name02'];
            $info['kana01'] = $arrOrderTemp['order_kana01'];
            $info['kana02'] = $arrOrderTemp['order_kana02'];
            $info['zip01']  = $arrOrderTemp['order_zip01'];
            $info['zip02']  = $arrOrderTemp['order_zip02'];
            $info['pref']   = $arrOrderTemp['order_pref'];
            $info['addr01'] = $arrOrderTemp['order_addr01'];
            $info['addr02'] = $arrOrderTemp['order_addr02'];
            $info['tel01']  = $arrOrderTemp['order_tel01'];
            $info['tel02']  = $arrOrderTemp['order_tel02'];
            $info['tel03']  = $arrOrderTemp['order_tel03'];
            $info['email']  = $arrOrderTemp['order_email'];
            $message = $arrOrderTemp['message'];
            
            if (SC_Utils_Ex::isBlank($arrErr) && !empty($arrOrderTemp['message'])) {
                $this->lfRegisterContactData($info, $message, $order_id);
            }
        }
    }

    /**
     * prefilterコールバック関数
     * テンプレートの変更処理を行います.
     *
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
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
                if (strpos($filename, 'order/subnavi.tpl') !== false) {
                    $objTransform->select('ul', NULL, false)->appendChild(
                        file_get_contents($template_dir . 'contact_navi.tpl'));
                }
                break;
        }

        $source = $objTransform->getHTML();
    }


    function lfRegisterContactData($info, $message, $order_id = null) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objCustomer = new SC_Customer();

        $sqlval['contact_id'] = $objQuery->nextVal('plg_ContactReply_contact_contact_id');
        $sqlval['name01'] = $info['name01'];
        $sqlval['name02'] = $info['name02'];
        $sqlval['kana01'] = $info['kana01'];
        $sqlval['kana02'] = $info['kana02'];
        $sqlval['zip01'] = $info['zip01'];
        $sqlval['zip02'] = $info['zip02'];
        $sqlval['pref'] = $info['pref'];
        $sqlval['addr01'] = $info['addr01'];
        $sqlval['addr02'] = $info['addr02'];
        $sqlval['tel01'] = $info['tel01'];
        $sqlval['tel02'] = $info['tel02'];
        $sqlval['tel03'] = $info['tel03'];
        $sqlval['email'] = $info['email'];
        $sqlval['contents'] = $message;
        $sqlval['order_id'] = $order_id;
        $sqlval['create_date'] = 'Now()';

        // ログイン状態である場合は顧客IDを格納するそれ以外はNULLを格納する
        if ($objCustomer->isLoginSuccess()){
            $sqlval['customer_id'] = $objCustomer->getValue('customer_id');
        } else {
            $sqlval['customer_id'] = null;
        }

        $objQuery->insert('plg_ContactReply_contact', $sqlval);
    }
}

?>
