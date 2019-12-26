<!--{*
 * AccessControlSpMb
 * Copyright(c) C-Rowl Co., Ltd. All Rights Reserved.
 * http://www.c-rowl.com/
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
<!--{if $plg_accesscontrolspmb_device_flg == true}-->
<style type="text/css">
<!--
a.btn_acspmb {
    width: 150px;
    text-align: center;
    color: #FFF;
    background-color: #000;
    background: -moz-linear-gradient(center top, #5E5E5E 0%,#232323 48%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #5E5E5E),color-stop(0.48, #232323));
    border: #303030 solid 1px;
    text-shadow: 0 -1px 1px rgba(0,0,0,0.5);
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    padding: 5px;
    margin: 10px auto;
    transition: background-color 1s ease-in;
    -webkit-transition: background-color 1s ease-in;
    -moz-transition: opacity 1s ease-int;
    cursor: pointer;
}
.btn_acspmb, a.btn_acspmb:link, a.btn_acspmb:hover, a.btn_acspmb:visited {
    color: #FFF;
    text-decoration: none;
}
//-->
</style>
<br />
<a class="btn_acspmb" href="<!--{$plg_accesscontrolspmb_url|h}-->" rel="external">スマートフォン画面を表示</a>
<br />
<!--{/if}-->