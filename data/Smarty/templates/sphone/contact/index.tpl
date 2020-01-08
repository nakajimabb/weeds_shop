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

<script>
    $(function() {
        $('#contents')
            .css('font-size', '100%')
            .autoResizeTextAreaQ({
                'max_rows': 50,
                'extra_rows': 0
            });
    });
</script>
<section id="undercolumn">
    <h2 class="title"><!--{$tpl_title|h}--></h2>
    <div class="intro">
        <p>ご意見やご質問をお受けしております。<br />
            休業日は翌営業日以降のご返信となりますのでご了承ください。</p>
    </div>

    <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="confirm" />

        <dl class="form_entry">
            <dt>お名前&nbsp;<span class="attention">※</span></dt>
            <dd>
                <span class="attention"><!--{$arrErr.name01}--><!--{$arrErr.name02}--></span>
                <input type="text" name="name01"
                    value="<!--{$arrForm.name01.value|default:$arrData.name01|h}-->"
                    maxlength="<!--{$smarty.const.STEXT_LEN}-->"
                    style="<!--{$arrErr.name01|sfGetErrorColor}-->" class="boxHarf text data-role-none" placeholder="姓" />&nbsp;&nbsp;
                <input type="text" name="name02"
                    value="<!--{$arrForm.name02.value|default:$arrData.name02|h}-->"
                    maxlength="<!--{$smarty.const.STEXT_LEN}-->"
                    style="<!--{$arrErr.name02|sfGetErrorColor}-->" class="boxHarf text data-role-none" placeholder="名" />
            </dd>

            <dt>メールアドレス&nbsp;<span class="attention">※</span></dt>
            <dd>
                <span class="attention"><!--{$arrErr.email}--><!--{$arrErr.email02}--></span>
                <input type="email" name="email"
                    value="<!--{$arrForm.email.value|default:$arrData.email|h}-->"
                    style="<!--{$arrErr.email|sfGetErrorColor}-->"
                    maxlength="<!--{$smarty.const.MTEXT_LEN}-->" class="boxLong top text data-role-none" />

                <!--{* ログインしていれば入力済みにする *}-->
                <!--{if $smarty.server.REQUEST_METHOD != 'POST' && $smarty.session.customer}-->
                    <!--{assign var=email02 value=$arrData.email}-->
                <!--{/if}-->

                <input type="email" name="email02"
                    value="<!--{$arrForm.email02.value|default:$email02|h}-->"
                    style="<!--{$arrErr.email02|sfGetErrorColor}-->"
                    maxlength="<!--{$smarty.const.MTEXT_LEN}-->" class="boxLong text data-role-none" placeholder="確認のため2回入力してください" />
            </dd>

            <dt>お問い合わせ内容&nbsp;<span class="attention">※</span>
                <span class="mini">（全角<!--{$smarty.const.MLTEXT_LEN}-->字以下）</span></dt>
            <dd><span class="attention"><!--{$arrErr.contents}--></span>
                <textarea name="contents" id="contents" class="textarea data-role-none" rows="4" cols="42" style="<!--{$arrErr.contents|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm.contents.value|h}--></textarea>
            </dd>

        </dl>

        <div class="btn_area">
            <input type="submit" value="確認ページへ" class="btn data-role-none" name="confirm" id="confirm" />
        </div>
    </form>
</section>

<!--{include file= 'frontparts/search_area.tpl'}-->

