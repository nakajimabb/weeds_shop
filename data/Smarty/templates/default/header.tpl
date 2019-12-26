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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA    02111-1307, USA.
 *}-->

<!--▼HEADER-->

<style>
/* メインメニュー項目の装飾 */
div#header_navi ul#dropmenu {
    margin: 0;              /* メニューバー外側の余白 */
    padding: 0;             /* メニューバー内側の余白 */
    /*width: 900px;*/
    /*border: 1px solid #ccc;*/
}
div#header_navi ul#dropmenu li {
    margin: 0;
    padding: 0;
    float: left;
    width: 120px;
    list-style-type: none;
    position: relative;
}

div#header_navi ul#dropmenu li + li {
    border-left: 1px solid #ccc;
}
/*div#header_navi ul li {
    display:inline;
    padding:0 10px 0 8px;
    vertical-align:bottom;
    margin-top: 6px;
    border-right: 1px solid #ccc;
}

div#header_navi ul li.end_of_list {
    background:none;
}
*/

div#header_navi ul#dropmenu a {
    font-family: "ヒラギノ角ゴ Pro W3",'Helvetica Neue',"メイリオ","ＭＳ Ｐゴシック",sans-serif;
    text-align: center;        /* メインメニューの文字列の配置（中央寄せ） */
    text-decoration: none;     /* メニュー項目の装飾（下線を消す） */
    display: block;
    width: 100%;
    height: 100%;
    /*padding: 5px 0px;*/
    margin: 0px;
    font-size:  13px;
    line-height: 1;  
    /*border: 1px solid #ccc;*/
    color: black;
}
/* サブメニュー項目の装飾 */
div#header_navi ul#dropmenu ul.dropsubmenu {
    background-color: #fafafa;
    list-style: none;
    margin: 0px;
    padding: 0px;
    top: 100%;
    left: 0;
    position: absolute;
    padding: 0;
    display: none;
    border: solid 1px #ccc;
    z-index: 1000;
}

div#header_navi ul#dropmenu ul.dropsubmenu li {
    padding: 5px;
    width: 180px;
    border-style: dotted;
    border-color: #d4d8d4;
    border-width: 0px 0px 1px 0px;
    background:none;
    line-height: 38px;
}

div#header_navi ul#dropmenu ul.dropsubmenu li.end_of_list {
    border:none;
}

div#header_navi ul#dropmenu ul.dropsubmenu li a {
    line-height: 25px;
    text-align: left; 
    text-indent: 5px; 
}

div#header_navi ul#dropmenu ul.dropsubmenu li:hover {
    background:#eee;
}
div#header_navi ul#dropmenu ul.dropsubmenu li a:hover {
    color:#39c;
}

div#header_navi ul#dropmenu ul.dropsubmenu ul.dropsubmenu2 {
    background-color: #fafafa;
    list-style: none;
    padding: 0px;
    top: -1px;
    left: 100%;
    position: absolute;
    padding: 0px;
    display: none;
    border: solid 1px #ccc;
    z-index: 1000;
}
div#header_navi ul#dropmenu ul.dropsubmenu ul.dropsubmenu2 li {
    width: 250px;
}
</style>

<script type="text/javascript">//<![CDATA[

$(function(){
      $("#dropmenu li").hover(function(){
         $("ul",this).show();
      },
      function(){
         $("ul",this).hide();
      });
   });
//]]></script>

<!--{strip}-->
    <div id="header_wrap">
        <div id="header" class="clearfix">
            <div id="logo_area">
                <a href="<!--{$smarty.const.TOP_URL}-->"><img src="<!--{$TPL_URLPATH}-->img/common/logo.png" alt="<!--{$arrSiteInfo.shop_name|h}-->/<!--{$tpl_title|h}-->" /></a>
            </div>
            <div id="header_utility">
                <div id="headerInternalColumn">
                <!--{* ▼HeaderInternal COLUMN *}-->
                <!--{if $arrPageLayout.HeaderInternalNavi|@count > 0}-->
                    <!--{* ▼上ナビ *}-->
                    <!--{foreach key=HeaderInternalNaviKey item=HeaderInternalNaviItem from=$arrPageLayout.HeaderInternalNavi}-->
                        <!-- ▼<!--{$HeaderInternalNaviItem.bloc_name}--> -->
                        <!--{if $HeaderInternalNaviItem.php_path != ""}-->
                            <!--{include_php file=$HeaderInternalNaviItem.php_path items=$HeaderInternalNaviItem}-->
                        <!--{else}-->
                            <!--{include file=$HeaderInternalNaviItem.tpl_path items=$HeaderInternalNaviItem}-->
                        <!--{/if}-->
                        <!-- ▲<!--{$HeaderInternalNaviItem.bloc_name}--> -->
                    <!--{/foreach}-->
                    <!--{* ▲上ナビ *}-->
                <!--{/if}-->
                <!--{* ▲HeaderInternal COLUMN *}-->
                </div>
            </div>
            <div id="header_navi">
                <ul id="dropmenu">
                    <!--{if false}-->
                    <li>
                        <a href="<!--{$smarty.const.TOP_URLPATH}-->products/list.php?category_id=0">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_category.png" alt="category" />
                        </a>
                        <ul class="dropsubmenu">
                        <!--{foreach key=cate_id item=cate from=$arrCatList}-->
                            <li>
                            <a href="<!--{$smarty.const.TOP_URLPATH}-->products/list.php?category_id=<!--{$cate_id}-->">
                                <!--{$cate.name}-->
                            </a>
                                <ul class="dropsubmenu2">
                                    <!--{foreach key=sub_id item=sub_cate from=$cate.child}-->
                                    <li>
                                    <a href="<!--{$smarty.const.TOP_URLPATH}-->products/list.php?category_id=<!--{$sub_id}-->">
                                        <!--{$sub_cate.name}-->
                                    </a>
                                    </li>
                                    <!--{/foreach}-->
                                </ul>
                            </li>
                        <!--{/foreach}-->
                        </ul>
                    </li>
                    <!--{/if}-->
                    <li>
                        <a href="<!--{$smarty.const.TOP_URLPATH}-->">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_home.png" alt="home" />
                        </a>
                    </li>
                    <li>
                        <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=1">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_cosmetic.png" alt="cosmetic" />
                        </a>
                        <ul class="dropsubmenu"> 
                            <li>
                                <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=1">ブランドから探す</a>
                            </li> 
                            <li>
                                <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=2">商品カテゴリから探す</a>
                            </li> 
                            <li>
                                <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=3">キーワードから探す</a>
                            </li> 
                        </ul> 
                    </li>
                    <li>
                        <a href="<!--{$smarty.const.ROOT_URLPATH}-->contents/intro_movie.php">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_movie.png" alt="movie" />
                        </a>
                    </li>
                    <!--{if $tpl_login}-->
                    <li>
                        <a href="<!--{$smarty.const.HTTPS_URL}-->mypage/login.php">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_mypage.png" alt="entry" />
                        </a>
                        <ul class="dropsubmenu"> 
                            <li>
                                <a href="<!--{$smarty.const.HTTPS_URL}-->mypage/index.php">
                                    購入履歴一覧
                                </a>
                            </li> 
                            <li class="end_of_list">
                                <a href="<!--{$smarty.const.HTTPS_URL}-->mypage/favorite.php">
                                    お気に入り一覧
                                </a>
                            </li> 
                        </ul> 
                    </li>
                    <li>
                        <a href="<!--{$smarty.const.ROOT_URLPATH}-->frontparts/login_check.php?mode=logout" >
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_logout.png" alt="entry" />
                        </a>
                    </li>
                    <!--{else}-->
                    <li>
                        <!-- <a href="<!--{$smarty.const.ROOT_URLPATH}-->entry/kiyaku.php"> -->
                        <a href="<!--{$smarty.const.ENTRY_URL}-->">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_entry.png" alt="entry" />
                        </a>
                    </li>
                    <li>
                        <a href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/login.php">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_login.png" alt="login" />
                        </a>
                    </li>
                    <!--{/if}-->
                    <li>
                        <a href="<!--{$smarty.const.CART_URLPATH}-->">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_cart.png" alt="cart" />
                        </a>
                    </li>
                    <li>
                        <a href="<!--{$smarty.const.ROOT_URLPATH}-->contact/<!--{$smarty.const.DIR_INDEX_PATH}-->">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_contact.png" alt="contact" />
                        </a>
                    </li>
                    <li class="end_of_list">
                        <a href="<!--{$smarty.const.TOP_URLPATH}-->../blog" target="_brank">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_menu_blog.png" alt="blog" />
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<!--{/strip}-->
<!--▲HEADER-->
