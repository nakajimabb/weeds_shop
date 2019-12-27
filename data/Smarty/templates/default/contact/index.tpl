<!--{*
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
 *}-->

<div id="undercolumn">
    <h2 class="title"><!--{$tpl_title|h}--></h2>

    <div id="undercolumn_contact">

        <p>内容によっては回答をさしあげるのにお時間をいただくこともございます。<br />
        また、休業日は翌営業日以降の対応となりますのでご了承ください。</p>

        <form name="form1" id="form1" method="post" action="?">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="confirm" />

            <table summary="お問い合わせ">
                <tr>
                    <th>お名前<span class="attention">※</span></th>
                    <td>
                        <span class="attention"><!--{$arrErr.name01}--><!--{$arrErr.name02}--></span>
                        姓&nbsp;<input type="text" class="box120" name="name01" value="<!--{$arrForm.name01.value|default:$arrData.name01|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.name01|sfGetErrorColor}-->; ime-mode: active;" />　
                        名&nbsp;<input type="text" class="box120" name="name02" value="<!--{$arrForm.name02.value|default:$arrData.name02|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.name02|sfGetErrorColor}-->; ime-mode: active;" />
                    </td>
                </tr>
                <tr>
                    <th>メールアドレス<span class="attention">※</span></th>
                    <td>
                        <span class="attention"><!--{$arrErr.email}--><!--{$arrErr.email02}--></span>
                        <input type="text" class="box380 top" name="email" value="<!--{$arrForm.email.value|default:$arrData.email|h}-->" style="<!--{$arrErr.email|sfGetErrorColor}-->; ime-mode: disabled;" /><br />
                        <!--{* ログインしていれば入力済みにする *}-->
                        <!--{if $smarty.server.REQUEST_METHOD != 'POST' && $smarty.session.customer}-->
                        <!--{assign var=email02 value=$arrData.email}-->
                        <!--{/if}-->
                        <input type="text" class="box380" name="email02" value="<!--{$arrForm.email02.value|default:$email02|h}-->" style="<!--{$arrErr.email02|sfGetErrorColor}-->; ime-mode: disabled;" /><br />
                        <p class="mini"><span class="attention">確認のため2度入力してください。</span></p>
                    </td>
                </tr>
                <tr>
                    <th>お問い合わせ内容<span class="attention">※</span><br />
                    <span class="mini">（全角<!--{$smarty.const.MLTEXT_LEN}-->字以下）</span></th>
                    <td>
                        <span class="attention"><!--{$arrErr.contents}--></span>
                        <textarea name="contents" class="box380" cols="60" rows="20" style="<!--{$arrErr.contents.value|h|sfGetErrorColor}-->; ime-mode: active;"><!--{"\n"}--><!--{$arrForm.contents.value|h}--></textarea>
                        <p class="mini attention">※ご注文に関するお問い合わせには、必ず「ご注文番号」をご記入くださいますようお願いいたします。</p>
                    </td>
                </tr>
            </table>

            <div class="btn_area">
                <ul>
                    <li>
                        <input type="image" class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_confirm.jpg" alt="確認ページへ" name="confirm" />
                    </li>
                </ul>
            </div>

        </form>
    </div>
</div>
