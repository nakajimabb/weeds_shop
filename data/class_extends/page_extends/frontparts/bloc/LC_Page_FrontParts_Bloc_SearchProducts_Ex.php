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

require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc_SearchProducts.php';

/**
 * 検索ブロック のページクラス(拡張).
 *
 * LC_Page_FrontParts_Bloc_SearchProducts をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_FrontParts_Bloc_SearchProducts_Ex extends LC_Page_FrontParts_Bloc_SearchProducts
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
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
}
