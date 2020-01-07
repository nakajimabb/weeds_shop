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
    <dt>お名前</dt>
    <dd>
        <!--{assign var=key1 value="`$prefix`name01"}-->
        <!--{assign var=key2 value="`$prefix`name02"}-->
        <!--{$arrForm[$key1].value|h}-->&nbsp;
        <!--{$arrForm[$key2].value|h}-->
    </dd>
    <dt>電話番号</dt>
    <dd>
        <!--{assign var=key1 value="`$prefix`tel01"}-->
        <!--{assign var=key2 value="`$prefix`tel02"}-->
        <!--{assign var=key3 value="`$prefix`tel03"}-->
        <!--{$arrForm[$key1].value|h}--> - <!--{$arrForm[$key2].value|h}--> - <!--{$arrForm[$key3].value|h}-->
    </dd>
    <!--{if $flgFields > 1}-->
        <dt>メールアドレス</dt>
        <dd>
            <!--{assign var=key1 value="`$prefix`email"}-->
            <a href="mailto:<!--{$arrForm[$key1].value|escape:'hex'}-->"><!--{$arrForm[$key1].value|escape:'hexentity'}--></a>
        </dd>
        <!--{if $flgFields > 2}-->
            <dt>希望するパスワード</dt>
            <dd><!--{$passlen}--></dd>
            <dt>
                <p>デフォルトの受取店舗</p>
                <p>（注文時に変更可能）</p>
            </dt>
            <!--{assign var=shop_id value=$arrForm.default_shop_id.value}-->
            <dd><!--{$arrShop[$shop_id]}--></dd>
        <!--{/if}-->
    <!--{/if}-->
<!--{/strip}-->
