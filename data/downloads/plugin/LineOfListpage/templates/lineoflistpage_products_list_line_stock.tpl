<!--{*
 * LineOfListpage
 * Copyright (C) 2013 BLUE STYLE All Rights Reserved.
 * http://bluestyle.jp/
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
 *}-->

<!--PLG:LineOfListpage↓-->
<!--▼在庫-->
<div class="list_stock"><span class="mini">在庫：</span>
    <!--{if $arrProduct.stock_max == NULL}-->
        有り
    <!--{elseif $arrProduct.stock_max < 1 && $arrProduct.stock_unlimited_max != 1}-->
        在庫切れ
    <!--{else}-->
        <!--{$arrProduct.stock_max|escape}--> 個
    <!--{ /if }-->
</div>
<!--▲在庫-->
<!--PLG:LineOfListpage↑-->