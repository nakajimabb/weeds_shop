<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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
 *}-->

 <!--　div class="bloc_outer">
    <div id="search_area">
        <div class="bloc_body">
　-->

<div id="main_search">
    <ul id="search_item_tab" class="navi">
        <!--{if $searchMode == 1}--><li class="select"><!--{else}--><li><!--{/if}-->
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/search_page.php?srch=1">ブランド検索</a>
        </li>
        <!--{if $searchMode == 2}--><li class="select"><!--{else}--><li><!--{/if}-->
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/search_page.php?srch=2">カテゴリ検索</a>
        </li>
        <!--{if $searchMode == 3}--><li class="select"><!--{else}--><li><!--{/if}-->
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/search_page.php?srch=3">キーワード検索</a>
        </li>
        <!--{if $searchMode == 4}--><li class="select"><!--{else}--><li><!--{/if}-->
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php?smode=4">新着＆おすすめ</a>
        </li>
    </ul>
    
    <!--{if $searchMode == 1}-->
        <div class="content_wrap" style="border-radius: 0px 5px 5px 5px;">

            <!--{foreach key=id item=maker name=makerinf from=$arrMakerInfo}-->

                <!--{if is_array($arrBrandInfo[$id])}-->
                    <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/maker/<!--{$maker.maker_image}-->" alt="<!--{$maker.name}-->" height=30px /><br> 
                    <ul class="list_brand">
                    <!--{foreach key=id2 item=item from=$arrBrandInfo[$id]}-->
                        <li>
                        <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php?maker_id=<!--{$id}-->&brand_id=<!--{$id2}-->&smode=1">
                            <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/<!--{$item.brand_image}-->" alt="<!--{$item.name|h}-->" class="picture" style="vertical-align:middle;" />
                        </a>
                        </li>
                    <!--{/foreach}-->
                    </ul>
                <!--{/if}-->
            <!--{/foreach}-->
        </div>
    <!--{elseif $searchMode == 2}-->
        <div class="content_wrap">

        <!--{foreach key=id item=cate_id from=$treeCategories.$CosmeticID.child_id name=cateLoop}-->

            <!--{assign var=cur_cate value=$treeCategories.$cate_id}-->

            <ul class="list_cate">
            <li class="cate_name">
                <p><!--{$cur_cate.category_name}--></p>
            </li>
            <!--{foreach item=cate_id2 from=$cur_cate.child_id}-->
                <li><a href="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php?category_id=<!--{$cate_id2}-->">
                    <!--{$treeCategories.$cate_id2.category_name}-->
                </a></li>
            <!--{/foreach}-->
            </ul>

        <!--{/foreach}-->

        </div>

    <!--{elseif $searchMode == 3}-->
        <div class="content_wrap">
            <!--検索フォーム-->
            <form name="search_form" id="search_form" method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
                <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

                <table class="formlist" >
                    <tr>
                        <td>カテゴリ</td>
                        <td>
                        <input type="hidden" name="mode" value="search" />
                        <select name="category_id" class="data-role-none">
                            <option label="指定しない" value="">指定しない</option>
                            <!--{html_options options=$arrCatList selected=$category_id}-->
                        </select>                
                        </td>
                    </tr>
                    <tr>
                        <td>メーカー</td>
                        <td>
                            <select name="maker_id" class="data-role-none">
                                <option label="指定しない" value="">指定しない</option>
                                <!--{html_options options=$arrMakerList selected=$maker_id}-->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>キーワード</td>
                        <td>
                            <input type="text" name="name" class="data-role-none" maxlength="50" value="<!--{$smarty.get.name|h}-->" />
                        </td>
                    </tr>
                </table>

                <div class="btn_area">
                    <a rel="external" href="javascript:void(document.search_form.submit());" class="btn">検索する</a>
                </div>
                    
            </form>
        </div>
    <!--{/if}-->
</div>
