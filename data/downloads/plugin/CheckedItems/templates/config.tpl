<!--{*
 * CheckedItems
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
<p>最近チェックした商品の詳細な設定が行えます。<br/>
    <br/>
</p>

<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼最近チェックした商品設定</td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">履歴保存日数<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="save_limit"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" />&nbsp;日
        </td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">履歴表示個数<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="save_count"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" />&nbsp;個
        </td>
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
