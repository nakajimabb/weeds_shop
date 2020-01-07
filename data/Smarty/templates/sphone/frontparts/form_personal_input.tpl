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

<!--{if $mode_entry}-->
    <dt>社員番号<span class="attention">※</span></dt>
    <dd>
        <!--{assign var=key1 value="`$prefix`staff_no"}-->
        <!--{if $arrErr[$key1]}-->
            <div class="attention"><!--{$arrErr[$key1]}--></div>
        <!--{/if}-->
        &nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="boxLong text data-role-none" />&nbsp;
    </dd>
<!--{else}-->
    <dt>社員番号</dt>
    <dd style="margin:5px 10px;">
        <!--{$arrForm.staff_no.value|h}-->
        <!--{assign var=key1 value="`$prefix`staff_no"}-->
        <input type="hidden" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->"  />
    </dd>
<!--{/if}-->

<!--{if $mode_entry}-->
<dt>
    <p>お名前(姓)&nbsp;<span class="attention">※</span></p>
    <p class="attention">漢字で入力してください</p>
</dt>
<dd>
    <!--{assign var=key1 value="`$prefix`name01"}-->
    <!--{if $arrErr[$key1]}-->
        <div class="attention"><!--{$arrErr[$key1]}--></div>
    <!--{/if}-->
    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" class="boxHarf text data-role-none" placeholder="姓" />&nbsp;&nbsp;
</dd>
<!--{else}-->
    <dt>お名前&nbsp;<span class="attention">※</span></dt>
    <dd>
        <!--{assign var=key1 value="`$prefix`name01"}-->
        <!--{assign var=key2 value="`$prefix`name02"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
        <!--{/if}-->
        <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" class="boxHarf text data-role-none" placeholder="姓" />&nbsp;&nbsp;
        <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" class="boxHarf text data-role-none" placeholder="名" />
    </dd>
<!--{/if}-->

<dt>電話番号&nbsp;<span class="attention">※</span></dt>
<dd>
    <!--{assign var=key1 value="`$prefix`tel01"}-->
    <!--{assign var=key2 value="`$prefix`tel02"}-->
    <!--{assign var=key3 value="`$prefix`tel03"}-->
    <!--{if $arrErr[$key1] || $arrErr[$key2] || $arrErr[$key3]}-->
        <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--><!--{$arrErr[$key3]}--></div>
    <!--{/if}-->
    <input type="tel" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" class="boxShort text data-role-none" />&nbsp;－&nbsp;<input type="tel" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" class="boxShort text data-role-none" />&nbsp;－&nbsp;<input type="tel" name="<!--{$key3}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->" class="boxShort text data-role-none" />
</dd>

<!--{if $flgFields > 1}-->

    <dt>メールアドレス&nbsp;<span class="attention">※</span></dt>
    <dd>
        <!--{assign var=key1 value="`$prefix`email"}-->
        <!--{assign var=key2 value="`$prefix`email02"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
        <!--{/if}-->
        <input type="email" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" class="boxLong text top data-role-none" />
        <input type="email" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" class="boxLong text data-role-none" placeholder="確認のため2回入力してください" />
    </dd>

    <!--{if $flgFields > 2}-->
        <dt>希望するパスワード&nbsp;<span class="attention">※</span></dt>
        <dd>
            <!--{assign var=key1 value="`$prefix`password"}-->
            <!--{assign var=key2 value="`$prefix`password02"}-->
            <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
            <!--{/if}-->
            <input type="password" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" class="boxLong text top data-role-none" />
            <input type="password" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key1]|cat:$arrErr[$key2]|sfGetErrorColor}-->" class="boxLong text data-role-none" placeholder="確認のため2回入力してください" />
            <p class="attention mini">半角英数字<!--{$smarty.const.PASSWORD_MIN_LEN}-->～<!--{$smarty.const.PASSWORD_MAX_LEN}-->文字</p>
        </dd>

        <dt>
            <p>デフォルトの受取店舗<span class="attention">※</span><p>
            <p>（注文時に変更可能）</p>
        </dt>
        <dd>
            <!--{if $arrErr.default_shop_id}-->
                <div class="attention"><!--{$arrErr.default_shop_id}--></div>
            <!--{/if}-->
            <select name="default_shop_id" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="boxLong top data-role-none">
                <option value="" selected="selected">選択してください</option>
                <!--{html_options options=$arrShop selected=$arrForm.default_shop_id|h}-->
            </select>
        </dd>
    <!--{/if}-->
<!--{/if}-->
