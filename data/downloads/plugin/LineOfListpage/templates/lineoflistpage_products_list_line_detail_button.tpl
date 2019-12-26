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
<!--★商品詳細を見る★-->
<div class="detail_btn">
    <!--{assign var=name value="detail`$id`"}-->
    <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->" onmouseover="chgImg('<!--{$smarty.const.ROOT_URLPATH}-->plugin/LineOfListpage/media/btn_detail_on.jpg','<!--{$name}-->');" onmouseout="chgImg('<!--{$smarty.const.ROOT_URLPATH}-->plugin/LineOfListpage/media/btn_detail.jpg','<!--{$name}-->');">
        <img src="<!--{$smarty.const.ROOT_URLPATH}-->plugin/LineOfListpage/media/btn_detail.jpg" alt="商品詳細を見る" name="<!--{$name}-->" id="<!--{$name}-->" />
    </a>
</div>
<!--PLG:LineOfListpage↑-->