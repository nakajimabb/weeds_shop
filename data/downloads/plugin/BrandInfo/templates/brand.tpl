<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="edit" />
    <input type="hidden" name="brand_id" value="<!--{$tpl_brand_id}-->" />
    <input type="hidden" name="keySet" value="">
    <input type="hidden" name="image_key" value="" />
    <!--{foreach key=key item=item from=$arrForm.arrHidden}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|h}-->" />
    <!--{/foreach}-->
    <div id="products" class="contents-main">

        <table class="form">
            <tr>
                <th>ブランド名<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.brand_id}--><span class="attention"><!--{$arrErr.brand_id}--></span><br /><!--{/if}-->
                    <!--{if $arrErr.name}--><span class="attention"><!--{$arrErr.name}--></span><!--{/if}-->
                    <input type="text" name="name" value="<!--{$arrForm.name|h}-->" maxlength="<!--{$smarty.const.SMTEXT_LEN}-->" style="" size="60" class="box60"/>
                    <span class="attention"> (上限<!--{$smarty.const.SMTEXT_LEN}-->文字)</span>
                </td>
            </tr>
            <tr>
                <th>ブランド情報</th>
                <td>
                    <textarea name="brand_info" cols="60" rows="8" class="area60" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" ><!--{$arrForm.brand_info}--></textarea><br />
                </td>
            </tr>
            <tr>
                <th>ブランド画像</th>
                <td>
                    <!--{assign var=key value="brand_image"}-->

                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                        <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />
                        <a href="" onclick="fnModeSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;"><br />[画像の取り消し]</a><br />
                    <!--{/if}-->

                    <input type="file" name="brand_image" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <a class="btn-normal" href="javascript:;" name="btn" onclick="fnModeSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a><br />
                </td>
            </tr>
            <tr>
                <th>メーカー</th>
                <td>
                    <span class="attention"><!--{$arrErr.maker_id}--></span>
                    <select name="maker_id" style="<!--{$arrErr.maker_id|sfGetErrorColor}-->">
                        <option value="">選択してください</option>
                        <!--{html_options options=$arrMaker selected=$arrForm.maker_id}-->
                    </select>
                </td>
            </tr>
        </table>

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
        <!--{if count($arrBrand) > 0}-->
        <table class="list">
            <col width="10%" />
            <col width="50%" />
            <col width="10%" />
            <col width="10%" />
            <col width="20%" />
            <tr>
                <th>ID</th>
                <th>ブランド</th>
                <th class="edit">編集</th>
                <th class="delete">削除</th>
                <th>移動</th>
            </tr>
            <!--{section name=cnt loop=$arrBrand}-->
            <tr style="background:<!--{if $tpl_brand_id != $arrBrand[cnt].brand_id}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->;">
                <!--{assign var=brand_id value=$arrBrand[cnt].brand_id}-->
                <td><!--{$brand_id|h}--></td>
                <td><!--{$arrBrand[cnt].name|h}--></td>
                <td class="center">
                    <!--{if $tpl_brand_id != $arrBrand[cnt].brand_id}-->
                    <a href="?" onclick="fnModeSubmit('pre_edit', 'brand_id', <!--{$arrBrand[cnt].brand_id}-->); return false;">編集</a>
                    <!--{else}-->
                    編集中
                    <!--{/if}-->
                </td>
                <td class="center">
                    <!--{if $arrClassCatCount[$class_id] > 0}-->
                    -
                    <!--{else}-->
                    <a href="?" onclick="fnModeSubmit('delete', 'brand_id', <!--{$arrBrand[cnt].brand_id}-->); return false;">削除</a>
                    <!--{/if}-->
                </td>
                <td class="center">
                    <!--{if $smarty.section.cnt.iteration != 1}-->
                    <a href="?" onclick="fnModeSubmit('up', 'brand_id', <!--{$arrBrand[cnt].brand_id}-->); return false;" />上へ</a>
                    <!--{/if}-->
                    <!--{if $smarty.section.cnt.iteration != $smarty.section.cnt.last}-->
                    <a href="?" onclick="fnModeSubmit('down', 'brand_id', <!--{$arrBrand[cnt].brand_id}-->); return false;" />下へ</a>
                    <!--{/if}-->
                </td>
            </tr>
            <!--{/section}-->
        </table>
        <!--{/if}-->
    </div>
</form>
