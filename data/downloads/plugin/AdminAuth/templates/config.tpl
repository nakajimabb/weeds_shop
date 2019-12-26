<!--{**
 *
 * AdminAuth
 * Copyright(c) 2012 Cyber-Will Inc. All Rights Reserved.
 *
 * http://www.cyber-will.co.jp/
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
**}-->

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<script type="text/javascript">
</script>

<h2><!--{$tpl_subtitle}--></h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit">
<p>管理画面の管理者権限設定が可能です。<br /><span class="attention">※閲覧（アクセス）不可にする項目にチェックを入れてください。<br />※管理者権限のアカウントの権限変更はできません</span><br/>
    <br/>
</p>

<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼管理画面閲覧可能設定</td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">個人/管理<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="person_flg"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <label><input type="radio" name="<!--{$key}-->" value="1" <!--{if $arrForm[$key] == "1"}-->checked<!--{/if}--> onclick="fnModeSubmit('person','','');" />個人</label>　
        <label><input type="radio" name="<!--{$key}-->" value="0" <!--{if $arrForm[$key] == "0"}-->checked<!--{/if}--> onclick="fnModeSubmit('person','','');" />管理権限毎</label>
        </td>
    </tr>
    <!--{if $arrForm[$key] != ""}-->
    <tr >
        <td bgcolor="#f3f3f3">ユーザ選択<span class="red">※</span></td>
        <td>
        <!--{assign var=key1 value="auth_id"}-->
        <span class="red"><!--{$arrErr[$key1]}--></span>
        <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                <option value="" selected="selected">選択してください</option>
        <!--{if $arrForm[$key] == '0'}-->
            <!--{html_options options=$arrAUTHORITY selected=$arrForm[$key1]}-->
        <!--{else}-->
            <!--{html_options options=$arrMember selected=$arrForm[$key1]}-->
        <!--{/if}-->
        </select>
        </td>
    </tr>
    <!--{if $arrForm[$key1] != ""}-->
    <tr >
        <td bgcolor="#f3f3f3">権限チェック<br /><span class="attention">※不可にチェック</span></td>
        <td>
    <!--{assign var=key2 value=""}-->
    <!--{foreach from=$arrAdminAuth item=authstore}-->
    <!--{if $smarty.foreach.authstore.first}-->
    <!--{/if}-->
        <!--{foreach from=$authstore item=item}-->
            <!--{if $key2 != $item.parent_name}-->
                ■<!--{$item.parent_name}--><br />
                <!--{assign var=key2 value="`$item.parent_name`"}-->
            <!--{/if}-->
            <!--{assign var=sid value="store`$item.store_id`"}-->
            　　<label><input type="checkbox" name="<!--{$sid}-->" value="1" <!--{if $arrForm[$sid] == "1"}-->checked<!--{/if}--> /><!--{$item.child_name}--></label><br />
        <!--{/foreach}-->
    <!--{/foreach}-->
        </td>
    </tr>
    <!--{/if}-->
    <!--{/if}-->
</table>
<!--{if $arrForm[$key] != "" && $arrForm[$key1] != ""}-->
<div class="btn-area">
    <ul>
        <li>
            <a class="btn-action" href="javascript:;" onclick="document.form1.submit();return false;"><span class="btn-next">この内容で登録する</span></a>
        </li>
    </ul>
</div>
<!--{elseif $arrForm[$key] != ""}-->
<div class="btn-area">
    <ul>
        <li>
            <a class="btn-action" href="javascript:;" onclick="fnModeSubmit('auth','','');return false;"><span class="btn-next">このユーザの権限を設定する</span></a>
        </li>
    </ul>
</div>
<!--{/if}-->
</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
