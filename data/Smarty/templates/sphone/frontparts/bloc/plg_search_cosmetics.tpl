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

<style>
#main_search div.content_wrap {
    /*margin: 10px auto 20px 2px;*/
}
#main_search ul li {
    float: left;
}
#main_search img.picture {
    border: #ccc solid 1px;
    padding: 0;
    margin: 2px;
    text-align: center;
}
#main_search ul#search_item_tab {
    margin-bottom: 10px;
}
ul.list_brand {
    margin-bottom: 15px;
}
ul.list_brand a {
    display: block;
}
ul.list_cate {
    border-bottom: #ccc solid 1px;
    margin:  0px;
    padding: 15px;
    background-color: #FEFEFE;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FEFEFE),color-stop(1, #EEEEEE));
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FEFEFE),color-stop(1, #EEEEEE));
}
ul.list_cate li {
    clear: both;
}
ul.list_cate li.cate_name {
    font-weight: bold;
    text-align: center;
    font-size: 120%;
    padding-bottom: 5px;
}

#main_search ul.navi {
    width: auto;
    clear: both;
    margin: 0 auto;
}
#main_search ul.navi li {
    float: left;
    text-align: center;
    border-left: #FFF solid 1px;
    border-right: #CCC solid 1px;
    border-bottom: #CCC solid 1px;
    white-space: nowrap;
    font-size: 70%;
    /*width: 24.5%;*/
    width: 32.8%;
}
#main_search ul.navi li.select {
    font-weight: bold;
}
#main_search ul.navi li a {
    color: #000;
    padding: 9px 0 8px 0;
    display: block;
    text-shadow: 0px -1px 1px rgba(255,255,255,0.7);
    background-color: #DAE0E5;
    background: -moz-linear-gradient(center top, #EEF0F3 0%,#DAE0E5 90%,#DAE0E5 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #EEF0F3),color-stop(0.9, #DAE0E5),color-stop(1, #DAE0E5));
}
#main_search ul.navi li a:hover {
    color: #FFF;
    text-shadow: 0px -1px 1px rgba(0,0,0,0.5);
    background: #5393c5;
    background: -moz-linear-gradient(center top, #5393c5 10%,#80b6e2 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #5393c5),color-stop(1, #80b6e2));
}

#main_search table.formlist {
    width: 95%;
    margin: 5px auto;
    /*border: solid #ccc 1px;*/
}
#main_search table.formlist td {
    padding: 5px;
}
#main_search table.formlist select {
    width: 100%;
}
   
</style>

 <!--　div class="bloc_outer">
    <div id="search_area">
        <div class="bloc_body">
　-->

<div id="main_search">
    <ul id="search_item_tab" class="navi">
        <!--{if $searchMode == 1}--><li class="select"><!--{else}--><li><!--{/if}-->
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=1">ブランド検索</a>
        </li>
        <!--{if $searchMode == 2}--><li class="select"><!--{else}--><li><!--{/if}-->
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=2">カテゴリ検索</a>
        </li>
        <!--{if $searchMode == 3}--><li class="select"><!--{else}--><li><!--{/if}-->
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=3">キーワード検索</a>
        </li>
    </ul>
    
    <!--{if $searchMode == 1}-->
        <div class="content_wrap" style="border-radius: 0px 5px 5px 5px;">

            <!--{foreach key=id item=maker name=makerinf from=$arrMakerInfo}-->

                <!--{if is_array($arrBrandInfo[$id])}-->
                    <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/<!--{$maker.maker_image}-->" alt="<!--{$maker.name}-->" height=30px /><br> 
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
