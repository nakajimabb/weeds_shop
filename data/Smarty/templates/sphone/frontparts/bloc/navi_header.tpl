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
.btn_navi, a.btn_navi, a.btn_navi:link, a.btn_navi:visited, a.btn_navi:hover {
    color: #444;
    font-size: 100%;
    padding: 5px;
    font-weight: bold;
    text-decoration: none;
    text-align: center;
    text-shadow: 0 -1px 1px rgba(255,255,255,1);
    border: 1px solid #A9ABAD;
    border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    background: #FDFDfD;
    background: -moz-linear-gradient(center top, #FDFDFD 0%,#D7DDE3 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FDFDFD),color-stop(1, #D7DDE3));
}    
</style>


<nav class="header_navi">
    <div style="padding-top:10px;">
        <!--{if $tpl_login}-->
        <a href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/index.php" class="btn_navi" >マイページ</a>
        <!--{else}-->
        <a href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/login.php" class="btn_navi" >ログイン</a>
        <!--{/if}-->
        <a href="<!--{$smarty.const.CART_URLPATH}-->" class="btn_navi">買い物かご</a>
    </div>
</nav>