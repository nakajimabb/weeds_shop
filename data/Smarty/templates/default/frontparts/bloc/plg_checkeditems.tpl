<!--{*
 * CheckedItems
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
#arrCheckItems h2 {
 padding: 5px 0 8px 10px;
 margin-bottom:10px;
 border-style: solid;
 border-color: #f90 #ccc #ccc;
 border-width: 1px 1px 0;
 background: url('<!--{$TPL_URLPATH}-->img/background/bg_btn_bloc_02.jpg') repeat-x left bottom #fef3d8;
}
#arrCheckItems{margin-bottom:10px;}
#arrCheckItems ul li {float:left; width:115px;}
#arrCheckItems ul li p.item_image{ text-align:center;}
#arrCheckItems ul li p.price{ font-size:90%;}
#arrCheckItems ul li p.price em{ color:#FF0000;}
</style>
<!--{if $arrCheckItems}-->
<!-- CheckedItems -->
<div id="arrCheckItems">
<h2><img src="<!--{$smarty.const.TOP_URLPATH}-->plugin/CheckedItems/media/images/tit_bloc_checkeditems.jpg" alt="*" class="title_icon" /></h2>
<ul class="clearfix">
<!--{section name=cnt loop=$arrCheckItems}-->
<li>
<p class="item_image">
<a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrCheckItems[cnt].product_id}-->">
<img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$arrCheckItems[cnt].main_list_image|sfNoImageMainList|h}-->&amp;width=110&amp;height=110" alt="<!--{$arrCheckItems[cnt].name|h}-->" /></a>
</p>
<p class="checkItemname"><a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrCheckItems[cnt].product_id}-->"><!--{$arrCheckItems[cnt].name}--></a></p>
<p class="price"><!--{$smarty.const.SALE_PRICE_TITLE}-->(税抜)<br /><em><!--{if $arrCheckItems[cnt].price02_min == $arrCheckItems[cnt].price02_max}--><!--{$arrCheckItems[cnt].price02_min|number_format}--><!--{else}--><!--{$arrCheckItems[cnt].price02_min|number_format}-->〜<!--{$arrCheckItems[cnt].price02_max|number_format}--><!--{/if}-->円</em></p>
</li>
<!--{/section}-->
</ul>
</div>
<!-- / CheckedItems END -->
<!--{/if}-->