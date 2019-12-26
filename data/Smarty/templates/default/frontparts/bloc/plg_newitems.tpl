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
#arrNewItems h2 {
 padding: 5px 0 8px 10px;
 margin-bottom:10px;
 border-style: solid;
 border-color: #f90 #ccc #ccc;
 border-width: 1px 1px 0;
 background: url('<!--{$TPL_URLPATH}-->img/background/bg_btn_bloc_02.jpg') repeat-x left bottom #fef3d8;
}
#arrNewItems{margin-bottom:10px;}
#arrNewItems ul li {float:left; width:115px;}
#arrNewItems ul li p.item_image{ text-align:center;}
#arrNewItems ul li p.price{ font-size:90%;}
#arrNewItems ul li p.price em{ color:#FF0000;}
</style>
<!--{if $arrNewItems}-->
<!-- arrNewItems -->
<div id="arrNewItems">
<h2><img src="<!--{$smarty.const.TOP_URLPATH}-->plugin/NewItems/media/images/tit_bloc_newitems.jpg" alt="*" class="title_icon" /></h2>
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
</div>
<!-- / arrNewItems END -->
<!--{/if}-->