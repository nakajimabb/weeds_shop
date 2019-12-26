<!--{*
 * NewItems
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
<h2><!--{$tpl_subtitle}--></h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit">
<p style="margin-bottom:10px;">新着商品の表示設定を行なうことができます。</p>

<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼新着商品設定</td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">表示条件</td>
        <td>
        <!--{assign var=key value="disp_rule"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="radio" name="<!--{$key}-->" id="<!--{$key}-->" value="1"<!--{if $arrForm[$key] == 1}--> checked="checked"<!--{/if}--> />登録日が新しい順
        <input type="radio" name="<!--{$key}-->" id="<!--{$key}-->" value="2"<!--{if $arrForm[$key] == 2}--> checked="checked"<!--{/if}--> />更新日が新しい順
        </td>
    </tr>

    <tr >
    <td bgcolor="#f3f3f3">PC表示個数<span class="red">※</span></td>
    <td>
    <!--{assign var=key value="disp_count_pc"}-->
    <span class="red"><!--{$arrErr[$key]}--></span>
    <input name="<!--{$key}-->" type="text" value="<!--{$arrForm[$key]}-->" size="5" maxlength="<!--{$smarty.const.INT_LEN}-->" />&nbsp;個
    </td>
</tr>
<tr >
<td bgcolor="#f3f3f3">モバイル表示個数<span class="red">※</span></td>
<td>
    <!--{assign var=key value="disp_count_mb"}-->
    <span class="red"><!--{$arrErr[$key]}--></span>
    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" size="5" maxlength="<!--{$smarty.const.INT_LEN}-->" />&nbsp;個
</td>
</tr>
<tr >
<td bgcolor="#f3f3f3">スマートフォン表示個数<span class="red">※</span></td>
<td>
    <!--{assign var=key value="disp_count_sp"}-->
    <span class="red"><!--{$arrErr[$key]}--></span>
    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" size="5" maxlength="<!--{$smarty.const.INT_LEN}-->" />&nbsp;個
</td>
</tr>
<tr >
<td bgcolor="#f3f3f3">表示ステータス<div style="font-size: 80%; color: #666666">未選択の場合は、全商品が対象となります。</div></td>
<td><!--{html_checkboxes name="product_status" options=$arrSTATUS selected=$arrForm.product_status separator='&nbsp;&nbsp;'}--></td>
</tr>
</table>

<div class="btn-area">
    <ul>
        <li>
            <a class="btn-action" href="javascript:void(0);" onclick="document.form1.submit();return false;"><span class="btn-next">この内容で登録する</span></a>
        </li>
    </ul>
</div>

</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
