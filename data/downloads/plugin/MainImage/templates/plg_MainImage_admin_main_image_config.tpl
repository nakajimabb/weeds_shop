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

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<!--{assign var=key1 value="image_width"}-->
<!--{assign var=key2 value="image_height"}-->

<style>
#configTable th{
    width:25%;
</style>

<h2>
    <!--{$tpl_subtitle}-->
</h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="edit" />
    <table id="configTable">
        <tr>
            <th>
                画像サイズ
                <span class="attention">*</span>
            </th>
            <td>
                <span class="attention">
                    <!--{if $arrErr[$key1]}-->
                    <!--{$arrErr[$key1]}-->
                    <!--{/if}-->
                    <!--{if $arrErr[$key2]}-->
                    <!--{$arrErr[$key2]}-->
                    <!--{/if}-->
                </span>
                横: <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]}-->" /> ×
                縦: <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]}-->" />
            </td>
        </tr>
        <!--{assign var=key value="max_registration"}-->
        <tr>
            <th>
                最大登録数
                <span class="attention">*</span>
            </th>
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" /><span class="attention">(*0はすべて表示)</span>
            </td>
        </tr>
        <!--{assign var=key value="effect"}-->
        <tr>
            <th>
                アニメーション方法
            </th>
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <select name="<!--{$key}-->">
                    <option value="0">フェード</option>
                    <option value="1" <!--{if $arrForm[$key] == 1}-->selected<!--{/if}-->>スライド</option>
                </select>
            </td>
        </tr>
        <!--{assign var=key value="interval"}-->
        <tr>
            <th>
                アニメーション間隔
                <span class="attention">*</span>
            </th>
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" />
                ミリ秒
            </td>
        </tr>
        <!--{assign var=key value="speed"}-->
        <tr>
            <th>
                アニメーション速度
                <span class="attention">*</span>
            </th>
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" />
                ミリ秒
            </td>
        </tr>
    </table>
    <div class="btn-area">
        <ul>
            <li>
                <a class="btn-action" href="javascript:void(0);" onclick="document.form1.submit();return false;"><span class="btn-next">設定を保存する</span></a>
            </li>
        </ul>
    </div>
</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->