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

<style type="text/css">
#main_search {
    clear: both;
    margin: 15px;
    /*border: solid 1px black;*/
}
#main_search h1 {
    padding-left: 5px;
    font-size: 150%;
    color:  green;
}
#main_search h2 {
    font-size: 130%;
    color: green;
    font-weight: normal;
}
#main_search table {
    border: none;
}
#main_search table tr {
    padding: 0;
    margin: 0;
}
#main_search table tr td {
    padding: 10px;
    margin: 0;
    width: 270px;
    vertical-align: top;
    border: 1px solid #ccc;
}

#main_search table.search_keyword tr td {
    padding: 3px;
    margin: 0;
    border: none;
}

div#search_item_tab li {
    float: left;
    margin: 10px 5px;
    list-style: none;
/*    cursor: pointer;
    background: #f8f8f8;
    border-radius: 5px 5px 0px 0px;
    border: solid 1px black;
    border-bottom: none;
*/}
div#search_item_tab li.select {
  background: #ddd;
}
div#brand_list {
    clear: both;
    padding: 8px 0;
}
div#brand_list ul {
    clear: both;
}
div#brand_list ul li {
    list-style: none;
    float: left;
    padding: 1px;
}
ul.category_list {
    padding: 5px 10px;
}
</style>

<div class="block_outer clearfix">
    <h2 class="block_title">化粧品検索</h2>

    <div id="main_search">
        <div id="search_item_tab">
        <ul class="clearfix">
            <li><label>
                <input type="radio" name="method" value="1" onclick="location.href='<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=1'" <!--{if $searchMode == 1}-->checked<!--{/if}--> />
                    ブランドから探す
            </label></li>
            <li><label>
                <input type="radio" name="method" value="2" onclick="location.href='<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=2'" <!--{if $searchMode == 2}-->checked<!--{/if}--> />
                商品カテゴリから探す
            </label></li>
            <li><label>
                <input type="radio" name="method" value="3" onclick="location.href='<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=3'" <!--{if $searchMode == 3}-->checked<!--{/if}--> />
                    キーワードから探す
            </label></li>
        </ul>
        </div>
    
        <!--{if $searchMode == 1}-->

            <!--{foreach key=id item=maker name=makerinf from=$arrMakerInfo}-->

                <!--{if is_array($arrBrandInfo[$id])}-->

                <div id="brand_list">
                    <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/<!--{$maker.maker_image}-->" alt="<!--{$maker.name}-->" height=30px /><br> 
                    <ul class="clearfix">
                    <!--{foreach key=id2 item=item from=$arrBrandInfo[$id]}-->
                        <li>
                            <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php?maker_id=<!--{$id}-->&brand_id=<!--{$id2}-->&category_id=1&smode=1">
                                <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/<!--{$item.brand_image}-->" alt="<!--{$item.name|h}-->" class="picture" />
                            </a>
                        </li>
                    <!--{/foreach}-->
                    </ul>
                </div>
                <!--{/if}-->
            <!--{/foreach}-->
        <!--{elseif $searchMode == 2}-->
        <table>
        <!--{foreach key=id item=cate_id from=$treeCategories.$CosmeticID.child_id name=cateLoop}-->

            <!--{if $smarty.foreach.cateLoop.index % 3 == 0}-->
                <tr>
            <!--{/if}-->

            <!--{assign var=cur_cate value=$treeCategories.$cate_id}-->

            <td>
            <div style="font-weight:bold;">
                <!--{$cur_cate.category_name}-->
            </div>
            <ul class="category_list">
            <!--{foreach item=cate_id2 from=$cur_cate.child_id}-->
                <li><a href="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php?category_id=<!--{$cate_id2}-->&smode=2">
                <!--{$treeCategories.$cate_id2.category_name}-->
                </a></li>            
            <!--{/foreach}-->
            </ul>
            </td>

            <!--{if $smarty.foreach.cateLoop.index % 3 == 2}-->
                </tr>
            <!--{/if}-->
        <!--{/foreach}-->
        </table>
        <!--{elseif $searchMode == 3}-->

            <!--検索フォーム-->
            <form name="search_form" id="search_form" method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

            <table class="search_keyword">
            <tr>
                <td>カテゴリを指定</td>
                <td>メーカーを指定</td>
                <td>キーワードを入力</td>
            </tr>
            <tr>
                <td>
                <input type="hidden" name="mode" value="search" />
                <select name="category_id" class="box150">
                    <option label="指定しない" value="">指定しない</option>
                    <!--{html_options options=$arrCatList selected=$category_id}-->
                </select>                
                </td>

                <td>
                    <select name="maker_id" class="box150">
                        <option label="指定しない" value="">指定しない</option>
                        <!--{html_options options=$arrMakerList selected=$maker_id}-->
                    </select>
                </td>

                <td>
                    <input type="text" name="name" class="box240" maxlength="50" value="<!--{$smarty.get.name|h}-->" />
                </td>

                <td>
                    <p class="btn"><input type="image" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_bloc_search_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_bloc_search.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_bloc_search.jpg" alt="検索" name="search" /></p>
                </td>
            </tr>
            </table>

            </form>
    <!--{/if}-->
    </div>
</div>
