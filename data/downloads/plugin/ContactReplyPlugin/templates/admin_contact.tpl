
<!--★★メインコンテンツ★★-->

<div id="customer" class="contents-main">
    <h2>お問い合わせ一覧</h2>

<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="delete">
<input type="hidden" name="contact_id" value="<!--{$list_data.contact_id|escape}-->">
<input type="hidden" name="search_pageno" value="<!--{$tpl_pageno}-->">

<!--お問い合わせ履歴一覧-->
<table>
    <tr>
        <td>
            <div style="padding-bottom:10px;">
                対応状況
                <select name="contact_status" id="contact_status" onchange="fnSubmit(); return false;">
                    <option value="">指定なし</option>
                    <!--{html_options options=$arrCONTACTSTATUS selected=$contact_status}-->
                </select>
            </div>

            <!--{if $tpl_linemax > 0}-->
                お問い合わせ履歴一覧　<span class="reselt"><!--購入履歴一覧--><!--{$tpl_linemax}-->件</span>&nbsp;が該当しました。
            <!--{else}-->
                お問い合わせ履歴はありません。
            <!--{/if}-->
        </td>
    </tr>
<!--お問い合わせ履歴一覧-->
</table>

<!--{$strnavi}-->

<!--お問い合わせ履歴一覧表示テーブル-->
<table>
    <col width="10%" />
    <col width="20%" />
    <col width="5%" />
    <col width="5%" />
    <col width="20%" />
    <col width="20%" />
    <col width="15%" />
    <col width="5%" />
    <tr align="center">
        <th><span>状況</span></th>
        <th><span>名前</span></th>
        <th><span>会員ID</span></th>
        <th><span>注文ID</span></th>
        <th><span>内容</span></th>
        <th><span>メールアドレス</span></th>
        <th><span>日時</span></th>
        <th><span>削除</span></th>
    </tr>
    <!--{section name=cnt loop=$arrContacts}-->
    <tr align="center">
            <td>
                <!--{assign var="status" value="`$arrContacts[cnt].status`"}-->
                <a href = "./admin_contact.php?contact_id=<!--{$arrContacts[cnt].contact_id}-->&amp;from=contact" <!--{if $status == 0}-->style="color:red; font-weight:bold;"<!--{/if}--> >
                    <!--{$arrCONTACTSTATUS[$status]}-->
                </a>
            </td>
            <td><!--{$arrContacts[cnt].name01}--> <!--{$arrContacts[cnt].name02}--></td>
            <td>
                <!--{if $arrContacts[cnt].customer_id != ''}-->
                    <!--{$arrContacts[cnt].customer_id}-->
                <!--{else}-->
                    非会員
                <!--{/if}-->
            </td>
            <td>
                <!--{if $arrContacts[cnt].order_id != ''}-->
                    <a href="#" onclick="fnOpenWindow('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/disp.php?order_id=<!--{$arrContacts[cnt].order_id}-->','order_disp','800','900'); return false;" ><!--{$arrContacts[cnt].order_id}--></a>
                <!--{/if}-->
            </td>
            <td><!--{$arrContacts[cnt].contents|truncate:30}--></td>
            <td><a href="mailto:<!--{$arrContacts[cnt].email}-->"><!--{$arrContacts[cnt].email}--></a></td>
            <td><!--{$arrContacts[cnt].create_date|sfDispDBDate}--></td>
            <td>
                <a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="fnModeSubmit('delete', 'contact_id', <!--{$arrContacts[cnt].contact_id}-->); return false;" />削除</a>
            </td>
    </tr>
    <!--{/section}-->
</table>
</form>

</div>



