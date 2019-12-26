<!--{*
 * SiteMaintenance
 *
 * Copyright(c) 2009-2012 CUORE CO.,LTD. All Rights Reserved.
 *
 * http://ec.cuore.jp/
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

<form name="point_form" id="point_form" method="post" action="">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="<!--{$tpl_mode}-->" />
<div id="basis" class="contents-main">
    <table>
        <tr>
            <th>営業状態<span class="attention"> *</span></th>
            <td>
                <span class="red12"><!--{$arrErr.plg_sitemaintenance_maintenance}--></span>
                <!--{html_radios name="plg_sitemaintenance_maintenance" options=$arrMainte selected=$arrForm.plg_sitemaintenance_maintenance}-->
            </td>
        </tr>
        <tr>
            <th>メンテナンスメッセージ<span class="attention"> *</span></th>
            <td>
                <!--{assign var=key value="plg_sitemaintenance_maintenance_msg"}-->
                <!--{if $arrErr[$key]}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{/if}-->
                <textarea name="<!--{$key}-->" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" cols="60" rows="8" class="area60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ><!--{$arrForm[$key]|escape}--></textarea><span class="red"> （上限<!--{$smarty.const.LLTEXT_LEN}-->文字）</span>
            </td>
        </tr>
    </table>

    <div class="btn-area">
        <ul>
            <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('point_form', '<!--{$tpl_mode}-->', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
        </ul>
    </div>
</div>
</form>
