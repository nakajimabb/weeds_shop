<!--{*
 * TopicPath
 * Copyright (C) 2012 LOCKON CO.,LTD. All Rights Reserved.
 * http://www.lockon.co.jp/
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
<p>パンくずリスト表示する際の詳細な設定が行えます。<br/>
    <br/>
</p>

<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼パンくず詳細設定</td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">表示カテゴリ<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="level"}-->
        <span class="red"><!--{$arrErr[$key]}--></span>
        <input type="radio" name="level" value="1" <!--{if $arrForm.level == "1"}-->checked<!--{/if}--> >階層が深いカテゴリ</input><br/>
        <input type="radio" name="level" value="2" <!--{if $arrForm.level == "2"}-->checked<!--{/if}--> >階層が浅いカテゴリ</input>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">優先度<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="rank"}-->
        <span class="red"><!--{$arrErr[$key]}--></span><br />
        <input type="radio" name="rank" value="2" <!--{if $arrForm.rank == "2"}-->checked<!--{/if}--> >降順</input>
        <input type="radio" name="rank" value="1" <!--{if $arrForm.rank == "1"}-->checked<!--{/if}--> >昇順</input>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">CSS<span class="red">※</span></td>
        <td>
        <!--{assign var=key value="css_data"}-->
        <span class="red"><!--{$arrErr[$key]}--></span><br />
        <span>変更されますと、正常にパンくずが表示されない可能性があります。</span><br />
        <textarea name="<!--{$key}-->" cols="60" rows="8" class="area60" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" ><!--{$arrForm[$key]|h}--></textarea><br />
        <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
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
