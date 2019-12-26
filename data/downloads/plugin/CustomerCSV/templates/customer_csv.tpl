<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="csv_upload" />
    <div id="customer" class="contents-main">
        <!--{if $tpl_errtitle != ""}-->
            <div class="message">
                <span class="attention"><!--{$tpl_errtitle}--></span><br />
                <!--{foreach key=key item=item from=$arrCSVErr}-->
                    <span class="attention"><!--{$item}-->
                    <!--{if $key != 'blank'}-->
                        [値：<!--{$arrParam[$key]}-->]
                    <!--{/if}-->
                    </span><br />
                <!--{/foreach}-->
            </div>
        <!--{/if}-->

        <!--▼登録テーブルここから-->
        <table>
            <tr>
                <th>CSVファイル</th>
                <td>
                    <!--{if $arrErr.csv_file}-->
                        <span class="attention"><!--{$arrErr.csv_file}--></span>
                    <!--{/if}-->
                    <input type="file" name="csv_file" size="40" /><span class="attention">(1行目タイトル行)(最大アップロードサイズ:<!--{$max_upload_csv_size}-->)</span>
                </td>
            </tr>
        </table>

        <p><!--{$regist_count}-->件を登録しました(登録済データ<!--{$exist_count}-->件)</p>
        <!--{foreach from=$messages item=message}-->
        <p><!--{$message}--></p>
        <!--{/foreach}-->


        <!--▲登録テーブルここまで-->
        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'csv_upload', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
    </div>
</form>