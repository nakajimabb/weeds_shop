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
    <tr>
        <th>お名前</th>
        <td>
            <!--{assign var=key1 value="`$prefix`name01"}-->
            <!--{assign var=key2 value="`$prefix`name02"}-->
            <!--{$arrForm[$key1].value|h}-->&nbsp;
            <!--{$arrForm[$key2].value|h}-->
        </td>
    </tr>
    <tr>
        <th>電話番号</th>
        <td>
            <!--{assign var=key1 value="`$prefix`tel01"}-->
            <!--{assign var=key2 value="`$prefix`tel02"}-->
            <!--{assign var=key3 value="`$prefix`tel03"}-->
            <!--{$arrForm[$key1].value|h}--> - <!--{$arrForm[$key2].value|h}--> - <!--{$arrForm[$key3].value|h}-->
        </td>
    </tr>
    <!--{if $flgFields > 1}-->
        <tr>
            <th>メールアドレス</th>
            <td>
                <!--{assign var=key1 value="`$prefix`email"}-->
                <a href="mailto:<!--{$arrForm[$key1].value|escape:'hex'}-->"><!--{$arrForm[$key1].value|escape:'hexentity'}--></a>
            </td>
        </tr>
        <!--{if $flgFields > 2}-->
            <tr>
                <th>希望するパスワード</th>
                <td><!--{$passlen}--></td>
            </tr>
            <tr>
                <th>
                    <p>デフォルトの受取店舗<p>
                    <p>（注文時に変更可能）</p>
                </th>
                <!--{assign var=shop_id value=$arrForm.default_shop_id.value}-->
                <td><!--{$arrShop[$shop_id]}--></td>
            </tr>
        <!--{/if}-->
    <!--{/if}-->
<!--{/strip}-->
