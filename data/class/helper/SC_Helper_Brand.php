<?php

// SC_Helper_Maker の文字列置換をしただけのクラス(2015/2/24)
// メーカー => ブランド
// Maker => Brand
// maker => maker

/**
 * ブランドを管理するヘルパークラス.
 *
 * @package Helper
 * @author pineray
 * @version $Id:$
 */
class SC_Helper_Brand
{
    /**
     * ブランドの情報を取得.
     *
     * @param  integer $brand_id    ブランドID
     * @param  boolean $has_deleted 削除されたブランドも含む場合 true; 初期値 false
     * @return array
     */
    public function getBrand($brand_id, $has_deleted = false)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = 'brand_id = ?';
        if (!$has_deleted) {
            $where .= ' AND del_flg = 0';
        }
        $arrRet = $objQuery->select('*', 'dtb_brand', $where, array($brand_id));

        return $arrRet[0];
    }

    /**
     * 名前からブランドの情報を取得.
     *
     * @param  integer $name        ブランド名
     * @param  boolean $has_deleted 削除されたブランドも含む場合 true; 初期値 false
     * @return array
     */
    public function getByName($name, $has_deleted = false)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = 'name = ?';
        if (!$has_deleted) {
            $where .= ' AND del_flg = 0';
        }
        $arrRet = $objQuery->select('*', 'dtb_brand', $where, array($name));

        return $arrRet[0];
    }

    /**
     * ブランド一覧の取得.
     *
     * @param  boolean $has_deleted 削除されたブランドも含む場合 true; 初期値 false
     * @return array
     */
    public function getList($has_deleted = false)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = 'brand_id, name';
        $where = '';
        if (!$has_deleted) {
            $where .= 'del_flg = 0';
        }
        $table = 'dtb_brand';
        $objQuery->setOrder('rank DESC');
        $arrRet = $objQuery->select($col, $table, $where);

        return $arrRet;
    }

    /**
     * ブランドの登録.
     *
     * @param  array    $sqlval
     * @return multiple 登録成功:ブランドID, 失敗:FALSE
     */
    public function saveBrand($sqlval)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $brand_id = $sqlval['brand_id'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        // 新規登録
        if ($brand_id == '') {
            // INSERTの実行
            $sqlval['rank'] = $objQuery->max('rank', 'dtb_brand') + 1;
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            $sqlval['brand_id'] = $objQuery->nextVal('dtb_brand_brand_id');
            $ret = $objQuery->insert('dtb_brand', $sqlval);
        // 既存編集
        } else {
            unset($sqlval['creator_id']);
            unset($sqlval['create_date']);
            $where = 'brand_id = ?';
            $ret = $objQuery->update('dtb_brand', $sqlval, $where, array($brand_id));
        }

        return ($ret) ? $sqlval['brand_id'] : FALSE;
    }

    /**
     * ブランドの削除.
     *
     * @param  integer $brand_id ブランドID
     * @return void
     */
    public function delete($brand_id)
    {
        $objDb = new SC_Helper_DB_Ex();
        // ランク付きレコードの削除
        $objDb->sfDeleteRankRecord('dtb_brand', 'brand_id', $brand_id, '', true);
    }

    /**
     * ブランドの表示順をひとつ上げる.
     *
     * @param  integer $brand_id ブランドID
     * @return void
     */
    public function rankUp($brand_id)
    {
        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfRankUp('dtb_brand', 'brand_id', $brand_id);
    }

    /**
     * ブランドの表示順をひとつ下げる.
     *
     * @param  integer $brand_id ブランドID
     * @return void
     */
    public function rankDown($brand_id)
    {
        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfRankDown('dtb_brand', 'brand_id', $brand_id);
    }

    /**
     * ブランドIDをキー, 名前を値とする配列を取得.
     *
     * @return array
     */
    public static function getIDValueList()
    {
        return SC_Helper_DB_Ex::sfGetIDValueList('dtb_brand', 'brand_id', 'name');
    }
}
