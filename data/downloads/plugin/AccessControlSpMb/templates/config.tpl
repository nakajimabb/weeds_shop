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

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<script type="text/javascript">
</script>

<h2><!--{$tpl_subtitle}--></h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit">
<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr >
        <th bgcolor="#f3f3f3">項目</th>
        <th>設定値</th>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3" width="20%">スマートフォンアクセス時</td>
        <td>
            <!--{assign var=key value="free_field1"}-->
            <label><input type="radio" name="free_field1" value="0" <!--{if $arrForm[$key]==0}-->checked<!--{/if}--> >制御なし</label><br />
            <label><input type="radio" name="free_field1" value="1" <!--{if $arrForm[$key]==1}-->checked<!--{/if}--> >PC画面を表示</label><br />
            <label><input type="radio" name="free_field1" value="2" <!--{if $arrForm[$key]==2}-->checked<!--{/if}--> >スマートフォン画面を表示し、PC画面を表示ボタンを設置</label><br />

            <br />
            切り替え時の制御<br />
            <!--{assign var=key value="free_field3"}-->
            <label><input type="radio" name="free_field3" value="0" <!--{if $arrForm[$key]==0}-->checked<!--{/if}--> >トップページへ遷移</label><br />
            <label><input type="radio" name="free_field3" value="1" <!--{if $arrForm[$key]==1}-->checked<!--{/if}--> >同じページヘ遷移</label><br />
        </td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">携帯アクセス時</td>
        <td>
            <!--{assign var=key value="free_field2"}-->
            <label><input type="radio" name="free_field2" value="0" <!--{if $arrForm[$key]==0}-->checked<!--{/if}--> >制御なし</label><br />
            <label><input type="radio" name="free_field2" value="1" <!--{if $arrForm[$key]==1}-->checked<!--{/if}--> >静的HTMLを表示</label><br />
            <br />
            ※静的HTMLは「このサイトは携帯端末には対応していません」と表示します。<br />
            ※文言を修正する場合は、以下のファイルを編集してください。<br />
            <!--{$smarty.const.HTML_REALDIR}-->plg_mobile.html
        </td>
    </tr>
</table>

<div class="btn-area">
    <ul>
        <li>
            <a class="btn-action" href="javascript:;" onclick="document.form1.submit();return false;"><span class="btn-next">この内容で登録する</span></a>
        </li>
    </ul>
</div>

</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
