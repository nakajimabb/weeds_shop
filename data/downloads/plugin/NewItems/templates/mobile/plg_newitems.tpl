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

<!--{if $arrNewItems}-->
<!-- arrNewItems -->
<div id="arrNewItems">
<!--{section name=cnt loop=$arrNewItems}-->
<p><img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$arrNewItems[cnt].main_list_image|sfNoImageMainList|h}-->&amp;width=50&amp;height=50" alt="&lt;!--{$arrNewItems[cnt].name|h}--&gt;" align="left" />
<a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrNewItems[cnt].product_id}-->"><!--{$arrNewItems[cnt].name}--></a><br>
<!--{$smarty.const.SALE_PRICE_TITLE}-->(税込)：<em style="color:red;"><!--{if $arrNewItems[cnt].price02_min_inctax == $arrNewItems[cnt].price02_max_inctax}--><!--{$arrNewItems[cnt].price02_min_inctax|number_format}--><!--{else}--><!--{$arrNewItems[cnt].price02_min_inctax|number_format}-->〜<!--{$arrNewItems[cnt].price02_max_inctax|number_format}--><!--{/if}-->円</em></p>
<!--{/section}-->
</div>
<hr>
<!-- / arrNewItems END -->
<!--{/if}-->