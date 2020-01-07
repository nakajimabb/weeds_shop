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
nav.top_menu {
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #808080),color-stop(1, #272a2b));
}
nav.top_menu ul {
    width: 100%;
    display: block;
    clear: both;
    padding: 0;
}   

nav.top_menu ul {
    width: 100%;
    padding: 0px;
    margin: 0px;
}

nav.top_menu li {
    width: 24.8%;
    height: 50px;
    margin: 0;
    padding: 0;
    float: left;
    background: green;
    background: -webkit-linear-gradient(green 0%, green 50%, #050 100%);
    background: -moz-linear-gradient(green 0%, green 50%, #050 100%);
    background: -o-linear-gradient(green 0%, green 50%, #050 100%);
    background: -ms-linear-gradient(green 0%, green 50%, #050 100%);
    /*-webkit-box-shadow: 3px 3px 3px 3px rgba(0,0,0,0.2) inset,-3px -3px 3px 3px rgba(0,0,0,0.2) inset;*/
    /*-moz-box-shadow: 3px 3px 3px 3px rgba(0,0,0,0.2) inset,-3px -3px 3px 3px rgba(0,0,0,0.2) inset;*/
    border-right: #ccc solid 1px;
}
nav.top_menu li:last-child {
    border: none;
}
nav.top_menu li p {
    padding: 0px;
    padding-top: 8px;
    font-size: 13px;
    color: #E6E6E6;
    text-align: center;
}
nav.top_menu li p.small {
    padding: 0px;
    font-size: 8px;
}
</style>

<nav class="top_menu clearfix">
    <ul>
        <!--{if false}-->
        <li>
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/category.php">
                <div class="navi">
                    <p>商品検索</p><p class="small">Products</p>
                </div>
            </a>
        </li>    
        <!--{/if}-->

        <li>
            <a href="<!--{$smarty.const.TOP_URLPATH}-->">
                <div class="navi">
                    <p>ホーム</p><p class="small">Home</p>
                </div>
            </a>
        </li>    
        <li>
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->products/cosmetic.php?srch=1">
                <div class="navi">
                    <p>化粧品</p><p class="small">Cosmetics</p>
                </div>
            </a>
        </lib>

        <li>
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->contents/intro_movie.php">
                <div class="navi">
                    <p>動画紹介</p><p class="small">Movie</p>
                </div>
            </a>
        </li>

        <li>
            <a href="<!--{$smarty.const.TOP_URLPATH}-->../blog" target="_brank">
                <div class="navi">
                    <p>ブログ</p><p class="small">Blog</p>
                </div>
            </a>
        </li>

    </ul>
</nav>

<form name="login_form_bloc" id="login_form_bloc" method="post" action="<!--{$smarty.const.HTTPS_URL}-->frontparts/login_check.php"<!--{if !$tpl_login}--> onsubmit="return eccube.checkLoginFormInputted('login_form_bloc')"<!--{/if}-->>
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="logout" />
    <input type="hidden" name="url" value="<!--{$smarty.server.SCRIPT_NAME|h}-->" />
</form>
