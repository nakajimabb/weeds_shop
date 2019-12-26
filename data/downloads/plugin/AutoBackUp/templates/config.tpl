<!--{*
 * BulkBuy
 * Copyright(c) 2012 SUNATMARK Inc. All Rights Reserved.
 *
 * http://www.sunatmark.co.jp/
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
<script type="text/javascript">
</script>

<h2><!--{$tpl_subtitle}--></h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit">
<p>バックアップ機能自動化の詳細な設定が行えます。<br/>
    <br/>
</p>

<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
        <td bgcolor="#f3f3f3">自動バックアップ<span class="red">※</span></td>
        <td>
            <!--{if $arrErr.auto_backup}--><span class="attention"><!--{$arrErr.auto_backup}--></span><!--{/if}-->
            <!--{assign var=key value="auto_backup"}-->
            <input type="radio" id="<!--{$key}-->_1" name="<!--{$key}-->" value="1" style="<!--{$arrErr.auto_backup|sfGetErrorColor}-->" <!--{$arrForm.auto_backup|sfGetChecked:1}--> /><label for="<!--{$key}-->_1">する</label>&nbsp;&nbsp;
            <input type="radio" id="<!--{$key}-->_2" name="<!--{$key}-->" value="2" style="<!--{$arrErr.auto_backup|sfGetErrorColor}-->" <!--{$arrForm.auto_backup|sfGetChecked:2}--> /><label for="<!--{$key}-->_2">しない</label>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">実行時<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="exec_time"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
            <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"><option value="">選択してください</option><!--{html_options options=$arrExecTime selected=$arrForm[$key]}--></select>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">実行分<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="exec_minutes"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
            <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"><option value="">選択してください</option><!--{html_options options=$arrExecMinutes selected=$arrForm[$key]}--></select>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">メールアドレス</td>
        <td>
        <!--{assign var=key value="email"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" /></span>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">自動バックアップ上限</td>
        <td>
        <!--{assign var=key value="backup_limit"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" /></span>
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
