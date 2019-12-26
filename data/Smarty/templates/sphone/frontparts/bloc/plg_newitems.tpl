<!--{*
 * NewItems
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

<!--{* こちらはお客様ごとに編集してください*}-->
<style type="text/css">
#arrNewItems{margin-bottom:10px;}
#arrNewItems ul li {float:left; width:33%;}
#arrNewItems ul li p.item_image{ text-align:center;}
#arrNewItems ul li p.price{ font-size:90%;}
#arrNewItems ul li p.price em{ color:#FF0000;}
</style>
<!--{if $arrNewItems}-->
<!-- arrNewItems -->
<section id="arrNewItems">
<h2 class="title_block">新着商品</h2>
<ul class="clearfix">
<!--{section name=cnt loop=$arrNewItems}-->
<li>
<p class="item_image">
<a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrNewItems[cnt].product_id}-->">
<img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$arrNewItems[cnt].main_list_image|sfNoImageMainList|h}-->&amp;width=110&amp;height=110" alt="<!--{$arrNewItems[cnt].name|h}-->" /></a>
</p>
<p class="checkItemname"><a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrNewItems[cnt].product_id}-->"><!--{$arrNewItems[cnt].name}--></a></p>
<p class="price"><!--{$smarty.const.SALE_PRICE_TITLE}-->(税込)<br /><em><!--{if $arrNewItems[cnt].price02_min_inctax == $arrNewItems[cnt].price02_max_inctax}--><!--{$arrNewItems[cnt].price02_min_inctax|number_format}--><!--{else}--><!--{$arrNewItems[cnt].price02_min_inctax|number_format}-->〜<!--{$arrNewItems[cnt].price02_max_inctax|number_format}--><!--{/if}-->円</em></p>
</li>
<!--{/section}-->
</ul>
</section>
<!-- / arrNewItems END -->
<!--{/if}-->