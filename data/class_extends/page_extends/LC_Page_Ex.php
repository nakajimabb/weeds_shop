<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once CLASS_REALDIR . 'pages/LC_Page.php';

class LC_Page_Ex extends LC_Page
{
    public function init() {
        parent::init();

        $objCustomer = new SC_Customer();
        if ($objCustomer->isLoginSuccess()) {
           $this->tpl_login = true;
        }

        $this->arrCatList = $this->lfGetCatTree();
        // SC_Utils::sfPrintR($this->arrCatList);
    }

    public function lfGetCategoryList()
    {
        $objDb = new SC_Helper_DB_Ex();
        // カテゴリ検索用選択リスト
        $arrCategoryList = $objDb->sfGetCategoryList('T1.level <= 2', true, '　');
        if (is_array($arrCategoryList)) {
            // 文字サイズを制限する
            foreach ($arrCategoryList as $key => $val) {
                $truncate_str = SC_Utils_Ex::sfCutString($val, SEARCH_CATEGORY_LEN, false);
                $arrCategoryList[$key] = preg_replace('/　/u', '&nbsp;&nbsp;', $truncate_str);
            }
        }

        return $arrCategoryList;
    }

    public function lfGetCatTree() {
        $catTree = $this->sfGetCatTree(0, true);
        
        $tree = array();

        foreach($catTree as $key=>$value) {
            $level = $value['level'];

            if($level > 2) continue;

            $cate_id = $value['category_id'];
            if($level == 1) {
                $tree[$cate_id]['name'] = $value['category_name'];
            }
            else {
                $parent_id = $value['parent_category_id'];
                $tree[$parent_id]['child'][$cate_id]['name'] = $value['category_name'];
            }
        }
        return $tree;
    }

    public function sfGetCatTree($parent_category_id, $count_check = false)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = '';
        $col .= ' cat.category_id,';
        $col .= ' cat.category_name,';
        $col .= ' cat.parent_category_id,';
        $col .= ' cat.level,';
        $col .= ' cat.rank,';
        $col .= ' cat.creator_id,';
        $col .= ' cat.create_date,';
        $col .= ' cat.update_date,';
        $col .= ' cat.del_flg, ';
        $col .= ' ttl.product_count';
        $from = 'dtb_category as cat left join dtb_category_total_count as ttl on ttl.category_id = cat.category_id';
        // 登録商品数のチェック
        if ($count_check) {
            $where = 'del_flg = 0 AND product_count > 0';
        } else {
            $where = 'del_flg = 0';
        }
        $objQuery->setOption('ORDER BY rank ASC');
        $arrRet = $objQuery->select($col, $from, $where);

        $arrParentID = SC_Utils_Ex::getTreeTrail($parent_category_id, 'category_id', 'parent_category_id', $arrRet);

        foreach ($arrRet as $key => $array) {
            foreach ($arrParentID as $val) {
                if ($array['category_id'] == $val) {
                    $arrRet[$key]['display'] = 1;
                    break;
                }
            }
        }

        return $arrRet;
    }
}
