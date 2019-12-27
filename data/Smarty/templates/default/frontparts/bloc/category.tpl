<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
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
#container .category_block_outer {
    background-color: #FFF;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin: 0 0 15px 0;
    padding: 0;
    box-shadow: 2px 2px 5px #ccc; 
}
#container .category_block_outer h2 {
    color: #59493f;
    font-size: 15px;
    padding: 7px 15px;
    margin: 0;
    border-bottom: 1px dotted #ccc;
}
#container #category_area .block_body {
    background-color: #fffaf0;
}

#category_area li.level1:first-child {
    border-top: none;
}
#category_area li {
    padding: 0;
    margin: 0;
    border-top: solid 1px #ccc;
}
#category_area li.level1 {
    /*border-bottom: solid 1px #ccc;*/
}
#category_area li.level1 p {
    /*font-size: 120%;*/
    padding: 6px 10px 6px 30px;
}
#category_area li.level2 p {
    font-size: 100%;
    padding-left: 20px;
}
#category_area li.level3 p {
    padding-left: 35px;
}
#category_area li.level4 p {
    padding-left: 50px;
}
#category_area li.level5 p {
    padding-left: 65px;
}

#category_area li a {
    display: block;
    padding: 0;
    color: #444;
}
#category_area a.onlink:link {
    color: black;
    font-weight: bold;
    /*text-decoration: underline;*/
}
#category_area a.onlink:visited {
    color: black;
}
#category_area a.onlink:hover {
    color: black;
}
</style>
<script type="text/javascript">//<![CDATA[
    $(function(){
        // $('#category_area li.level1:last').css('border-bottom', 'none');
    });
//]]></script>

<!--{strip}-->
    <div class="category_block_outer">
        <h2>商品カテゴリ</h2>
        <div id="category_area">
            <!-- <div class="block_body"> -->
                <!--{include file="`$smarty.const.TEMPLATE_REALDIR`frontparts/bloc/category_tree_fork.tpl" children=$arrTree treeID="" display=1}-->
            <!-- </div> -->
        </div>
    </div>
<!--{/strip}-->
