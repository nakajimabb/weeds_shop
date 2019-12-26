<!--{*
* クロネコヤマト送り状発行ソフトB2対応CSVダウンロード
* Copyright (C) 2012/05/17 BOKUBLOCK.INC TAKAHIRO SUEMITSU
* http://www.bokublock.jp / ec-cube@bokublock.jp
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
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
        <th bgcolor="#f3f3f3">表示項目</th>
        <th>表示/非表示</th>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">商品コード</td>
        <td>
        	<!--{assign var=key value="free_field1"}-->
        	<input type="radio" name="free_field1" value="0" <!--{if $arrForm[$key]==0}-->checked<!--{/if}--> >表示
        	<input type="radio" name="free_field1" value="1" <!--{if $arrForm[$key]==1}-->checked<!--{/if}--> >非表示
        </td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">通常価格</td>
        <td>
        	<!--{assign var=key value="free_field2"}-->
        	<input type="radio" name="free_field2" value="0" <!--{if $arrForm[$key]==0}-->checked<!--{/if}--> >表示
        	<input type="radio" name="free_field2" value="1" <!--{if $arrForm[$key]==1}-->checked<!--{/if}--> >非表示
        </td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">ポイント</td>
        <td>
        	<!--{assign var=key value="free_field3"}-->
        	<input type="radio" name="free_field3" value="0" <!--{if $arrForm[$key]==0}-->checked<!--{/if}--> >表示
        	<input type="radio" name="free_field3" value="1" <!--{if $arrForm[$key]==1}-->checked<!--{/if}--> >非表示
        </td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">数量</td>
        <td>
        	<!--{assign var=key value="free_field4"}-->
        	<input type="radio" name="free_field4" value="0" <!--{if $arrForm[$key]==0}-->checked<!--{/if}--> >表示
        	<input type="radio" name="free_field4" value="1" <!--{if $arrForm[$key]==1}-->checked<!--{/if}--> >非表示
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
