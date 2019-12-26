<!--★★メインコンテンツ★★-->
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="mode" value="confirm">
<input type="hidden" name="contact_id" value="<!--{$arrContactDetail.contact_id|escape}-->">
<input type="hidden" name="from" value="<!--{$arrForm.from|escape}-->">
<input type="hidden" name="edit_customer_id" value="" />
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

<!--{foreach from=$arrSearchData key="key" item="item"}-->
    <!--{if $key ne "customer_id" && $key ne "mode" && $key ne "del_mode" && $key ne "edit_customer_id" && $key ne "del_customer_id" && $key ne "csv_mode" && $key ne "job" && $key ne "sex"}-->
        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$item|escape}-->">
    <!--{/if}-->
<!--{/foreach}-->

<!--{foreach from=$arrForm key=key item=item}-->
    <!--{if $key ne "mode" && $key ne "subm"}-->
        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$item|escape}-->">
    <!--{/if}-->
<!--{/foreach}-->

<div id="customer" class="contents-main">
    <h2>お問い合わせ返信(確認)</h2>

        <table>
            <tr>
                <th>宛先</th>
                <td>
                    <!--{$arrContactDetail.name01}--> <!--{$arrContactDetail.name02}--> 様 &lt;<!--{$arrContactDetail.email}-->&gt;
                </td>
            </tr>
            <tr>
                <th>メールタイトル</th>
                <td><!--{$arrForm.title|escape}--></td>
            </tr>
            <tr>
                <th>本文</th>
                <td>
                    <!--{$arrForm.contents|escape}-->
                </td>
            </tr>
        </table>
        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="fnModeSubmit('send_return', '', ''); return false;"><span class="btn-prev">前画面に戻る</span></a></li>
                <li><a class="btn-action" href="javascript:;" onclick="fnModeSubmit('send_complete', '', ''); return false;"><span class="btn-next">送信</span></a></li>
            </ul>
        </div>
        <!--{*▲ここまで返信機能▲*}-->
</div>
</form>
<!--★★メインコンテンツ★★-->


