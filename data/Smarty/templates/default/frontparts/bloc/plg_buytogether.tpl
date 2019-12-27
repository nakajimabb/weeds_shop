<!--{*
 * BuyTogether
 * Copyright(c) 2012 DELIGHT Inc. All Rights Reserved.
 *
 * http://www.delight-web.com/
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
<!-- BuyTogether -->
<!--{* こちらはお客様ごとに編集してください*}-->
<style type="text/css">
#BuyTogether h2 {
 padding: 5px 0 8px 10px;
 margin-bottom:10px;
 border-style: solid;
 border-color: #f90 #ccc #ccc;
 border-width: 1px 1px 0;
 background: url('<!--{$TPL_URLPATH}-->img/background/bg_btn_bloc_02.jpg') repeat-x left bottom #fef3d8;
}
#BuyTogether{margin:20px;}
#BuyTogether ul li {float:left; width:127px;}
#BuyTogether ul li p.item_image{ text-align:center;}
#BuyTogether ul li p.price{ font-size:90%;}
#BuyTogether ul li p.price em{ color:#FF0000;}
</style>
<!--{if $arrBuyTogether and $is_disp}-->
<!-- BuyTogether -->
<div class="block_outer clearfix">
    <h2 class="block_title">よく一緒に購入されている商品</h2>
        <div id="BuyTogether">
        <ul class="clearfix">
        <!--{section name=cnt loop=$arrBuyTogether}-->
            <li>
            <p class="item_image">
            <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrBuyTogether[cnt].product_id}-->">
            <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$arrBuyTogether[cnt].main_list_image|sfNoImageMainList|h}-->&amp;width=110&amp;height=110" alt="<!--{$arrBuyTogether[cnt].name|h}-->" /></a>
            </p>
            <p class="checkItemname"><a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrBuyTogether[cnt].product_id}-->"><!--{$arrBuyTogether[cnt].name}--></a></p>
            <p class="price"><!--{$smarty.const.SALE_PRICE_TITLE}-->(税抜)<br /><em><!--{if $arrBuyTogether[cnt].price02_min == $arrBuyTogether[cnt].price02_max}--><!--{$arrBuyTogether[cnt].price02_min|number_format}--><!--{else}--><!--{$arrBuyTogether[cnt].price02_min|number_format}-->〜<!--{$arrBuyTogether[cnt].price02_max|number_format}--><!--{/if}-->円</em></p>
            </li>
        <!--{/section}-->
        </ul>
    </div>
</div>
<!-- / BuyTogether END -->
<!--{/if}-->