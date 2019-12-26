<!--{*
 * BuyTogether
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
<p>よく一緒に購入されている商品の詳細な設定が行えます。<br/>
    <br/>
</p>

<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼検索条件設定</td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">検索対象期間<span class="red">※</span>
        </td>
        <td>
        <!--{assign var=key value="search_date"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" />&nbsp;日間
        </td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">検索対象受注数<span class="red">※</span>
          <div style="font-size: 80%; color: #666666">よく一緒に購入されていると判定する受注数を設定します。</div>
        </td>
        <td>
        <!--{assign var=key value="order_count"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" />&nbsp;回以上
        </td>
    </tr>
    <tr>
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼表示個数設定</td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">PC表示個数<span class="red">※</span>
        </td>
        <td>
        <!--{assign var=key value="disp_count_pc"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" />&nbsp;個
        </td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">モバイル表示個数<span class="red">※</span>
        </td>
        <td>
        <!--{assign var=key value="disp_count_mb"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" />&nbsp;個
        </td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">スマートフォン表示個数<span class="red">※</span>
        </td>
        <td>
        <!--{assign var=key value="disp_count_sp"}-->
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
