<!--{*
 * LineOfListpage
 * Copyright (C) 2013 BLUE STYLE All Rights Reserved.
 * http://bluestyle.jp/
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

<h1><span class="title"><!--{$tpl_subtitle}--></span></h1>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="register">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="image_key" value="" />
    <!--{foreach key=key item=item from=$arrForm.arrHidden}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$item|h}-->" />
    <!--{/foreach}-->

    <h2>表示項目設定</h2>
    <table border="0" cellspacing="1" cellpadding="8" summary="表示項目設定">
        <tr>
            <th>商品コード</th>
            <td>
                <input type="checkbox" name="product_code" <!--{if $arrForm.product_code == "on"}-->checked="checked"<!--{/if}--> />表示する
            </td>
        </tr>
        <tr>
            <th>商品画像</th>
            <td>
                <input type="checkbox" name="image" <!--{if $arrForm.image == "on"}-->checked="checked"<!--{/if}--> />表示する
            </td>
        </tr>
        <tr>
            <th>ステータス</th>
            <td>
                <input type="checkbox" name="status" <!--{if $arrForm.status == "on"}-->checked="checked"<!--{/if}--> />表示する
            </td>
        </tr>
        <tr>
            <th>商品名</th>
            <td>
                <input type="checkbox" name="name" <!--{if $arrForm.name == "on"}-->checked="checked"<!--{/if}--> />表示する
            </td>
        </tr>
        <tr>
            <th>販売価格</th>
            <td>
                <input type="checkbox" name="price" <!--{if $arrForm.price == "on"}-->checked="checked"<!--{/if}--> />表示する
            </td>
        </tr>
        <tr>
            <th>一覧コメント</th>
            <td>
                <input type="checkbox" name="listcomment" <!--{if $arrForm.listcomment == "on"}-->checked="checked"<!--{/if}--> />表示する
            </td>
        </tr>
        <tr>
            <th>詳細ボタン</th>
            <td>
                <input type="checkbox" name="detail_btn" <!--{if $arrForm.detail_btn == "on"}-->checked="checked"<!--{/if}--> />表示する
            </td>
        </tr>
        <tr>
            <th>カートインボタン</th>
            <td>
                <input type="checkbox" name="cartin_btn" <!--{if $arrForm.cartin_btn == "on"}-->checked="checked"<!--{/if}--> />表示する
            </td>
        </tr>
        <tr>
            <th>在庫表示</th>
            <td>
                <input type="checkbox" name="stock" <!--{if $arrForm.stock == "on"}-->checked="checked"<!--{/if}--> />表示する
            </td>
        </tr>
        <tr>
            <th>商品ブロックの高さ揃え　※一部ブラウザでは機能しません</th>
            <td>
                <input type="checkbox" name="jqueryAutoHeight" <!--{if $arrForm.jqueryAutoHeight == "on"}-->checked="checked"<!--{/if}--> />揃える
            </td>
        </tr>
        <tr>
            <th>角丸（R3px）枠線（1px）</th>
            <td>
                <input type="checkbox" name="line_list_css" <!--{if $arrForm.line_list_css == "on"}-->checked="checked"<!--{/if}--> />表示する
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