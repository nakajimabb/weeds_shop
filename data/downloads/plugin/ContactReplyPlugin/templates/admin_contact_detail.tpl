<!--★★メインコンテンツ★★-->
<style>
    th.contact {
        max-width: 140px;
        min-width: 140px;
        width: 140px;
    }
    hr {
        border: none;
        border-top: solid 1px #CCCCCC;
    }
</style>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="mode" value="confirm">
<input type="hidden" name="contact_id" value="<!--{$arrContactDetail.contact_id|escape}-->">
<input type="hidden" name="from" value="<!--{$arrForm.from|escape}-->">
<input type="hidden" name="edit_customer_id" value="" />
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

<!--{foreach from=$arrSearchData key="key" item="item"}-->
    <!--{if $key ne "customer_id" && $key ne "mode" && $key ne "del_mode" && $key ne "edit_customer_id" && $key ne "del_customer_id" && $key ne "csv_mode" && $key ne "job" && $key ne "sex"}--><input type="hidden" name="<!--{$key|escape}-->" value="<!--{$item|escape}-->"><!--{/if}-->
<!--{/foreach}-->

<div id="customer" class="contents-main">
    <h2>お問い合わせ詳細</h2>
        <table>
            <tr>
                <th class="contact">対応状況</th>
                <td>
                    <!--{assign var="status" value="`$arrContactDetail.status`"}-->
                    <select name="status" id="status">
                        <!--{html_options options=$arrCONTACTSTATUS selected=$status}-->
                    </select>
                    <a class="btn-normal" href="javascript:;" onclick="fnModeSubmit('change_status', '', ''); return false;"><span class="btn-next">変更する</span></a>
                </td>
            </tr>
            <tr>
                <th class="contact">受信日時</th>
                <td>
                    <!--{$arrContactDetail.create_date}-->
                </td>
            </tr>
            <tr>
                <th class="contact">会員ID</th>
                <td>
                <!--{if $arrContactDetail.customer_id}-->
                    <!--{$arrContactDetail.customer_id|escape}-->
                <!--{else}-->
                    非会員
                <!--{/if}-->
                </td>
            </tr>
            <tr>
                <th class="contact">お名前</th>
                <td>
                    <!--{$arrContactDetail.name01|escape}-->&nbsp;<!--{$arrContactDetail.name02|escape}-->&nbsp;様
                </td>
            </tr>
            <tr>
                <th class="contact">電話番号</th>
                <td><!--{$arrContactDetail.tel01|escape}-->-<!--{$arrContactDetail.tel02|escape}-->-<!--{$arrContactDetail.tel03|escape}-->
                </td>
            </tr>
            <tr>
                <th class="contact">ご住所</th>
                <td>
                    〒 <!--{$arrContactDetail.zip01|escape}--> - <!--{$arrContactDetail.zip02|escape}-->
                    <!--{$arrPref[$arrContactDetail.pref]}--><!--{$arrContactDetail.addr01|escape}--><!--{$arrContactDetail.addr02|escape}-->
                </td>
            </tr>
            <tr>
                <th class="contact">メールアドレス</th>
                <td><!--{$arrContactDetail.email|escape}--></td>
            </tr>
            <tr>
                <th class="contact">問い合わせ内容</th>
                <td></span>
                    <!--{$arrContactDetail.contents|escape|nl2br}-->
                </td>
            </tr>
        </table>
        <hr>

        <!--{*▲ここから返信一覧▼*}-->
        <!--{section name=cnt loop=$arrContactReplies}-->
        <!--{if $arrContactReplies[cnt].direction == 1}-->
            <!--{assign var="dir_style" value="#DDDDEE"}-->
        <!--{else}-->
            <!--{assign var="dir_style" value="#EEDDDD"}-->
        <!--{/if}-->
        <table>
            <tr>
                <th class="contact" style="background-color: <!--{$dir_style}-->">日時</th>
                <td>
                    <!--{$arrContactReplies[cnt].create_date}-->
                </td>
            </tr>
            <tr>
                <th class="contact" style="background-color: <!--{$dir_style}-->">送信者</th>
                <td>
                <!--{if $arrContactReplies[cnt].direction == 1}-->
                    管理者
                <!--{elseif $arrContactReplies[cnt].direction == 2}-->
                    <!--{$arrContactDetail.name01}--> <!--{$arrContactDetail.name02}--> 様 &lt;<!--{$arrContactDetail.email}-->&gt;
                <!--{/if}-->
                </td>
            </tr>
            <tr>
                <th class="contact" style="background-color: <!--{$dir_style}-->">宛先</th>
                <td>
                <!--{if $arrContactReplies[cnt].direction == 1}-->
                    <!--{$arrContactDetail.name01}--> <!--{$arrContactDetail.name02}--> 様 &lt;<!--{$arrContactDetail.email}-->&gt;
                <!--{elseif $arrContactReplies[cnt].direction == 2}-->
                    管理者
                <!--{/if}-->
                </td>
            </tr>
            <tr>
                <th class="contact" style="background-color: <!--{$dir_style}-->">メールタイトル</th>
                <td>
                    <pre><!--{$arrContactReplies[cnt].title}--></pre>
                </td>
            </tr>
            <tr>
                <th class="contact" style="background-color: <!--{$dir_style}-->">本文</th>
                <td>
                    <pre><!--{$arrContactReplies[cnt].contents}--></pre>
                </td>
            </tr>
        </table>
        <hr />
        <!--{/section}-->
        <!--{*▲ここまで返信一覧▲*}-->

        <!--{*▲ここから返信機能▼*}-->
        <table>
            <tr>
                <th class="contact">宛先</th>
                <td>
                    <!--{$arrContactDetail.name01}--> <!--{$arrContactDetail.name02}--> 様 &lt;<!--{$arrContactDetail.email}-->&gt;
                </td>
            </tr>
            <tr>
                <th class="contact">メールタイトル</th>
                <td>
                    <span class="attention"><!--{$arrErr.title}--></span>
                    <input type="text" size="30" name="title" value="<!--{$arrForm.title|escape}-->" <!--{if $arrErr.title != ""}--><!--{sfSetErrorStyle}--><!--{/if}-->/>
                </td>
            </tr>
            <tr>
                <th class="contact">本文</th>
                <td>
                    <span class="attention"><!--{$arrErr.contents}--></span>
                    <textarea name="contents" cols="60" rows="10" <!--{if $arrErr.contents != ""}--><!--{sfSetErrorStyle}--><!--{/if}-->><!--{$arrForm.contents|escape}--></textarea>
                </td>
            </tr>
        </table>
        <div class="btn-area">
	        <ul>
	            <li><a class="btn-action" href="javascript:;" onclick="fnModeSubmit('return', '', ''); return false;"><span class="btn-prev">検索画面に戻る</span></a></li>
	            <li><a class="btn-action" href="javascript:;" onclick="fnModeSubmit('send_confirm', '', ''); return false;"><span class="btn-next">送信確認</span></a></li>
	        </ul>
        </div>
    <!--{*▲ここまで返信機能▲*}-->

</div>
</form>
<!--★★メインコンテンツ★★-->
