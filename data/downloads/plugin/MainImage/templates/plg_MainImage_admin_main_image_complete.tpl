<!--{*
 * MainImage
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

<div id="complete">
    <div class="complete-top"></div>
    <div class="contents">
        <div class="message">
            登録が完了致しました。
        </div>
    </div>
    <div class="btn-area-top"></div>
    <div class="btn-area">
        <ul>
            <li><a class="btn-action" href="./plg_MainImage_main_image.php"><span class="btn-prev">編集画面に戻る</span></a></li>
        </ul>
    </div>
    <div class="btn-area-bottom"></div>
</div>



<!--{* オペビルダー用 *}-->
<!--{if "sfViewAdminOpe"|function_exists === TRUE}-->
<!--{include file=`$smarty.const.MODULE_REALDIR`mdl_opebuilder/admin_ope_view.tpl}-->
<!--{/if}-->
