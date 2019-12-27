<!--{*
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
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

<!--{strip}-->
    <col width="30%" />
    <col width="70%" />
<!--{if $mode_entry}-->
    <tr>
        <th>社員番号<span class="attention">※</span></th>
        <td>
            <!--{assign var=key1 value="`$prefix`staff_no"}-->
            <!--{if $arrErr[$key1]}-->
                <div class="attention"><!--{$arrErr[$key1]}--></div>
            <!--{/if}-->
            &nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="box20" />&nbsp;
        </td>
    </tr>
<!--{else}-->
    <tr>
        <th>社員番号</th>
        <td>
            <!--{$arrForm.staff_no.value|h}-->
            <!--{assign var=key1 value="`$prefix`staff_no"}-->
            <input type="hidden" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->"  />
        </td>
    </tr>
<!--{/if}-->
<!--{if $mode_entry}-->
<tr>
    <th>
        <p>お名前(姓)<span class="attention">※</span></p>
        <p class="attention">漢字で入力してください</p>
    </th>
    <td>
        <!--{assign var=key1 value="`$prefix`name01"}-->
        <!--{if $arrErr[$key1]}-->
            <div class="attention"><!--{$arrErr[$key1]}--></div>
        <!--{/if}-->
        姓&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: active;" class="box120" />&nbsp;
    </td>
</tr>
<!--{else}-->
    <tr>
        <th>お名前<span class="attention">※</span></th>
        <td>
            <!--{assign var=key1 value="`$prefix`name01"}-->
            <!--{assign var=key2 value="`$prefix`name02"}-->
            <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
                <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
            <!--{/if}-->
            姓&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: active;" class="box120" />&nbsp;
            名&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: active;" class="box120" />
        </td>
    </tr>
<!--{/if}-->
    <!--{assign var=key1 value="`$prefix`zip01"}-->
    <!--{assign var=key2 value="`$prefix`zip02"}-->
    <!--{assign var=key3 value="`$prefix`pref"}-->
    <!--{assign var=key4 value="`$prefix`addr01"}-->
    <!--{assign var=key5 value="`$prefix`addr02"}-->
    <!--{assign var=key6 value="`$prefix`country_id"}-->
    <!--{assign var=key7 value="`$prefix`zipcode"}-->
    <input type="hidden" name="<!--{$key6}-->" value="<!--{$smarty.const.DEFAULT_COUNTRY_ID}-->" />
    <tr>
        <th>電話番号<span class="attention">※</span></th>
        <td>
            <!--{assign var=key1 value="`$prefix`tel01"}-->
            <!--{assign var=key2 value="`$prefix`tel02"}-->
            <!--{assign var=key3 value="`$prefix`tel03"}-->
            <!--{if $arrErr[$key1] || $arrErr[$key2] || $arrErr[$key3]}-->
                <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--><!--{$arrErr[$key3]}--></div>
            <!--{/if}-->
            <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />&nbsp;-&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />&nbsp;-&nbsp;<input type="text" name="<!--{$key3}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />
        </td>
    </tr>
    <!--{if $flgFields > 1}-->
        <tr>
            <th>メールアドレス<span class="attention">※</span></th>
            <td>
                <!--{assign var=key1 value="`$prefix`email"}-->
                <!--{assign var=key2 value="`$prefix`email02"}-->
                <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
                    <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
                <!--{/if}-->
                <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="box300 top" /><br />
                <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" style="<!--{$arrErr[$key1]|cat:$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" class="box300" /><br />
                <span class="attention mini">確認のため2度入力してください。</span>
            </td>
        </tr>
        <!--{if $flgFields > 2}-->
            <tr>
                <th>希望するパスワード<span class="attention">※</span><br />
                </th>
                <td>
                    <!--{assign var=key1 value="`$prefix`password"}-->
                    <!--{assign var=key2 value="`$prefix`password02"}-->
                    <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
                        <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
                    <!--{/if}-->
                    <input type="password" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" class="box120" />
                    <p><span class="attention mini">半角英数字<!--{$smarty.const.PASSWORD_MIN_LEN}-->～<!--{$smarty.const.PASSWORD_MAX_LEN}-->文字でお願いします。（記号可）</span></p>
                    <input type="password" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key1]|cat:$arrErr[$key2]|sfGetErrorColor}-->" class="box120" />
                    <p><span class="attention mini">確認のために2度入力してください。</span></p>
                </td>
            </tr>
            <tr>
                <th>
                    <p>デフォルトの受取店舗<span class="attention">※</span><p>
                    <p>（注文時に変更可能）</p>
                </th>
                <td>
                    <!--{if $arrErr.default_shop_id}-->
                        <div class="attention"><!--{$arrErr.default_shop_id}--></div>
                    <!--{/if}-->
                    <select name="default_shop_id" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                    <option value="" selected="selected">選択してください</option>
                    <!--{html_options options=$arrShop selected=$arrForm.default_shop_id|h}-->
                    </select>
                </td>
            </tr>
        <!--{/if}-->
    <!--{/if}-->
<!--{/strip}-->
