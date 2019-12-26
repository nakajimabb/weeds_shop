<!--{*
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
*}-->

<form name="form1" id="form1" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="" />
    <input type="hidden" name="tpl_shop_id" value="<!--{$tpl_shop_id}-->" />

    <div id="basis" class="contents-main">

        <table class="form">
            <tr>
                <th>店舗コード<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.shop_id}--><span class="attention"><!--{$arrErr.shop_id}--></span><!--{/if}-->
                    <input type="text" name="shop_id" value="<!--{$arrForm.shop_id|h}-->" maxlength="10" style="" size="10" class="box10"/>
                </td>
            </tr>
            <tr>
                <th>店舗名<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.name}--><span class="attention"><!--{$arrErr.name}--></span><!--{/if}-->
                    <input type="text" name="name" value="<!--{$arrForm.name|h}-->" maxlength="30" style="" size="30" class="box30"/>
                </td>
            </tr>
            <tr>
                <th>郵便番号<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.zip01}--><!--{$arrErr.zip02}--></span>
                    〒 <input type="text" name="zip01" value="<!--{$arrForm.zip01|h}-->" maxlength="<!--{$smarty.const.ZIP01_LEN}-->" size="6" class="box6" maxlength="3" <!--{if $arrErr.zip01 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> - <input type="text" name="zip02" value="<!--{$arrForm.zip02|h}-->" maxlength="<!--{$smarty.const.ZIP02_LEN}-->" size="6" class="box6" maxlength="4" <!--{if $arrErr.zip02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                    <a class="btn-normal" href="javascript:;" name="address_input" onclick="fnCallAddress('<!--{$smarty.const.INPUT_ZIP_URLPATH}-->', 'zip01', 'zip02', 'pref', 'addr01'); return false;">住所入力</a>
                </td>
            </tr>
            <tr>
                <th>住所<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.pref}--><!--{$arrErr.addr01}--><!--{$arrErr.addr02}--></span>
                    <select class="top" name="pref" <!--{if $arrErr.pref != ""}--><!--{sfSetErrorStyle}--><!--{/if}-->>
                        <option class="top" value="" selected="selected">都道府県を選択</option>
                        <!--{html_options options=$arrPref selected=$arrForm.pref}-->
                    </select><br />
                    <input type="text" name="addr01" value="<!--{$arrForm.addr01|h}-->" size="60" class="box60" <!--{if $arrErr.addr01 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /><br />
                    <!--{$smarty.const.SAMPLE_ADDRESS1}--><br />
                    <input type="text" name="addr02" value="<!--{$arrForm.addr02|h}-->" size="60" class="box60" <!--{if $arrErr.addr02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /><br />
                    <!--{$smarty.const.SAMPLE_ADDRESS2}-->
                </td>
            </tr>
            <tr>
                <th>電話番号<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.tel01}--><!--{$arrErr.tel02}--><!--{$arrErr.tel03}--></span>
                    <input type="text" name="tel01" value="<!--{$arrForm.tel01|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.tel01 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> - <input type="text" name="tel02" value="<!--{$arrForm.tel02|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.tel01 != "" || $arrErr.tel02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> - <input type="text" name="tel03" value="<!--{$arrForm.tel03|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.tel01 != "" || $arrErr.tel03 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            <tr>
                <th>FAX</th>
                <td>
                    <span class="attention"><!--{$arrErr.fax01}--><!--{$arrErr.fax02}--><!--{$arrErr.fax03}--></span>
                    <input type="text" name="fax01" value="<!--{$arrForm.fax01|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.fax01 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> - <input type="text" name="fax02" value="<!--{$arrForm.fax02|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.fax01 != "" || $arrErr.fax02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> - <input type="text" name="fax03" value="<!--{$arrForm.fax03|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.fax01 != "" || $arrErr.fax03 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            <tr>
                <th>発送可能</th>
                <td>
                    <input type="checkbox" name="valid" value="1" <!--{if $arrForm.valid == "1"}-->checked<!--{/if}--> />発送可能
                </td>
            </tr>
        </table>

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
    </div>
</form>

   <p style="height:30px">総 <!--{$arrShop|@count}--> 店舗</p>
    <table class="list">
        <col width="5%" />
        <col width="20%" />
        <col width="8%" />
        <col width="25%" />
        <col width="12%" />
        <col width="12%" />
        <col width="6%" />
        <col width="6%" />
        <col width="6%" />
        <tr>
            <th class="center">店舗コード</th>
            <th>店舗名</th>
            <th>郵便番号</th>
            <th>住所</th>
            <th>TEL</th>
            <th>FAX</th>
            <th>発送可</th>
            <th>編集</th>
            <th>削除</th>
        </tr>
        <!--{section name=cnt loop=$arrShop}-->
        <!--{assign var=shop_id value=$arrShop[cnt].shop_id}-->
        <tr style="background:<!--{if $tpl_shop_id != $arrShop[cnt].shop_id}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->;">
            <td class="center"><!--{$arrShop[cnt].shop_id|h}--></td>
            <td class="left"><!--{$arrShop[cnt].name|h}--></td>
            <td class="left"><!--{$arrShop[cnt].zip|h}--></td>
            <td class="left"><!--{$arrShop[cnt].addr|h}--></td>
            <td class="left"><!--{$arrShop[cnt].tel|h}--></td>
            <td class="left"><!--{$arrShop[cnt].fax|h}--></td>
            <td class="center"><!--{if $arrShop[cnt].valid}-->○<!--{else}-->×<!--{/if}--></td>
            <td class="center">
                <!--{if $tpl_shop_id != $arrShop[cnt].shop_id}-->
                <a href="?" onclick="fnModeSubmit('pre_edit', 'tpl_shop_id', <!--{$arrShop[cnt].shop_id}-->); return false;">編集</a>
                <!--{else}-->
                編集中
                <!--{/if}-->
            </td>
            <td class="center">
                <!--{if $arrClassCatCount[$class_id] > 0}-->
                -
                <!--{else}-->
                <a href="?" onclick="fnModeSubmit('delete', 'tpl_shop_id', <!--{$arrShop[cnt].shop_id}-->); return false;">削除</a>
                <!--{/if}-->
            </td>
        </tr>
        <!--{/section}-->
    </table>
</div>
</form>
