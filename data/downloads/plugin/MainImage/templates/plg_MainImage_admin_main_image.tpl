<!--{*
 * MainImage
 * Copyright(c) 2012 DELIGHT Inc. All Rights Reserved.
 *
 * http://www.delight-web.com/
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *}-->
 
<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="edit" />
    <input type="hidden" name="image_number" value="" />
    <!--{foreach from=$arrForm.arrHidden key=key item=item}-->
    <!--{if is_array($item)}-->
        <!--{foreach from=$item key=nestKey item=nestItem}-->
        <input type="hidden" name="<!--{$key}-->[<!--{$nestKey}-->]" value="<!--{$nestItem}-->" />
        <!--{/foreach}-->
    <!--{else}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$item}-->" />
    <!--{/if}-->
    <!--{/foreach}-->
    <div id="main-images" class="contents-main">
        <!--{foreach from=$arrImageIndexes item=image_index}-->
        <table class="form">
            <tr>
                <th>
                    メインイメージ画像<br />
                    [<!--{$arrPlugin.image_width}-->×<!--{$arrPlugin.image_height}-->]
                </th>
                <td>
                    <span class="attention"><!--{$arrErr[$image_key][$image_index]}--></span>
                    <!--{if $arrForm.arrFile[$image_key][$image_index].filepath != ""}-->
                    <img src="<!--{$arrForm.arrFile[$image_key][$image_index].filepath}-->" alt="<!--{$arrForm.title[$image_index]|h}-->" /><br />
                    <!--{/if}-->
                    <input type="file" name="<!--{$image_key}-->[<!--{$image_index}-->]" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <a class="btn-normal" href="javascript:;" onclick="fnModeSubmit('upload_image','image_number','<!--{$image_index}-->');return false;">アップロード</a>
                </td>
            </tr>
            <!--{assign var=key value="title"}-->
            <tr>
                <th>
                    タイトル<br />
                    <small class="attention">(alt要素に適用されます)</small>
                </th>
                <td>
                    <span class="attention"><!--{$arrErr.title[$image_index]}--></span>
                    <input type="text" value="<!--{$arrForm.title[$image_index]|h}-->" name="<!--{$key}-->[<!--{$image_index}-->]" class="box60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <small class="attention">(上限<!--{$smarty.const.STEXT_LEN}-->文字)</small>
                </td>
            </tr>
            <!--{assign var=key value="url_pc"}-->
            <tr>
                <th>
                    PC用URL
                </th>
                <td>
                    <span class="attention"><!--{$arrErr[$key][$image_index]}--></span>
                    <input type="text" value="<!--{$arrForm[$key][$image_index]|h}-->" name="<!--{$key}-->[<!--{$image_index}-->]" class="box60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <small class="attention">(上限<!--{$smarty.const.URL_LEN}-->文字)</small><br />
                    <!--{assign var=key value="target_blank_pc"}-->
                    <input type="hidden" name="<!--{$key}-->[<!--{$image_index}-->]" value="0" />
                    <label for="<!--{$key}--><!--{$image_index}-->">
                        <input type="checkbox" id="<!--{$key}--><!--{$image_index}-->" value="1" <!--{if $arrForm[$key][$image_index] == "1"}-->checked<!--{/if}--> name="<!--{$key}-->[<!--{$image_index}-->]" />
                        新しいウィンドウで開く
                    </label>
                </td>
            </tr>
            <!--{assign var=key value="url_sp"}-->
            <tr>
                <th>
                    スマートフォン用URL
                </th>
                <td>
                    <span class="attention"><!--{$arrErr[$key][$image_index]}--></span>
                    <input type="text" value="<!--{$arrForm[$key][$image_index]|h}-->" name="<!--{$key}-->[<!--{$image_index}-->]" class="box60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <small class="attention">(上限<!--{$smarty.const.URL_LEN}-->文字)</small><br />
                    <!--{assign var=key value="target_blank_sp"}-->
                    <input type="hidden" name="<!--{$key}-->[<!--{$image_index}-->]" value="0" />
                    <label for="<!--{$key}--><!--{$image_index}-->">
                        <input type="checkbox" id="<!--{$key}--><!--{$image_index}-->" value="1" <!--{if $arrForm[$key][$image_index] == "1"}-->checked<!--{/if}--> name="<!--{$key}-->[<!--{$image_index}-->]" />
                        新しいウィンドウで開く
                    </label>
                </td>
            </tr>
            <!--{assign var=key value="url_mb"}-->
            <tr>
                <th>
                    モバイル用URL
                </th>
                <td>
                    <span class="attention"><!--{$arrErr[$key][$image_index]}--></span>
                    <input type="text" value="<!--{$arrForm[$key][$image_index]|h}-->" name="<!--{$key}-->[<!--{$image_index}-->]" class="box60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <small class="attention">(上限<!--{$smarty.const.URL_LEN}-->文字)</small><br />
                    <!--{assign var=key value="target_blank_mb"}-->
                    <input type="hidden" name="<!--{$key}-->[<!--{$image_index}-->]" value="0" />
                    <label for="<!--{$key}--><!--{$image_index}-->">
                        <input type="checkbox" id="<!--{$key}--><!--{$image_index}-->" value="1" <!--{if $arrForm[$key][$image_index] == "1"}-->checked<!--{/if}--> name="<!--{$key}-->[<!--{$image_index}-->]" />
                        新しいウィンドウで開く
                    </label>
                </td>
            </tr>
            <!--{assign var=key value="display_mb"}-->
            <tr>
                <th>
                    表示設定
                </th>
                <td>
                    <!--{assign var=key value="hidden_pc"}-->
                    <span class="attention"><!--{$arrErr[$key][$image_index]}--></span>
                    <label for="<!--{$key}--><!--{$image_index}-->">
                        <input type="hidden" name="<!--{$key}-->[<!--{$image_index}-->]" value="0" />
                        <input id="<!--{$key}--><!--{$image_index}-->" type="checkbox" name="<!--{$key}-->[<!--{$image_index}-->]" <!--{if $arrForm[$key][$image_index] == "1"}-->checked<!--{/if}--> value="1" />
                        PCで非表示
                    </label>
                    <!--{assign var=key value="hidden_mb"}-->
                    <span class="attention"><!--{$arrErr[$key][$image_index]}--></span>
                    <label for="<!--{$key}--><!--{$image_index}-->">
                        <input type="hidden" name="<!--{$key}-->[<!--{$image_index}-->]" value="0" />
                        <input id="<!--{$key}--><!--{$image_index}-->" type="checkbox" name="<!--{$key}-->[<!--{$image_index}-->]" <!--{if $arrForm[$key][$image_index] == "1"}-->checked<!--{/if}--> value="1" />
                        モバイルで非表示
                    </label>
                    <!--{assign var=key value="hidden_sp"}-->
                    <span class="attention"><!--{$arrErr[$key][$image_index]}--></span>
                    <label for="<!--{$key}--><!--{$image_index}-->">
                        <input type="hidden" name="<!--{$key}-->[<!--{$image_index}-->]" value="0" />
                        <input id="<!--{$key}--><!--{$image_index}-->" type="checkbox" name="<!--{$key}-->[<!--{$image_index}-->]" <!--{if $arrForm[$key][$image_index] == "1"}-->checked<!--{/if}--> value="1" />
                        スマートフォンで非表示
                    </label>
                </td>
            </tr>
            <!--{if $arrForm.arrFile[$image_key][$image_index].filepath != ""}-->
            <!--{assign var=key value="delete"}-->
            <tr>
                <th>
                    削除
                </th>
                <td>
                    <span class="attention"><!--{$arrErr[$key][$image_index]}--></span>
                    <input type="hidden" name="<!--{$key}-->[<!--{$image_index}-->]" value="0" />
                    <label for="<!--{$key}--><!--{$image_index}-->">
                        <input type="checkbox" id="<!--{$key}--><!--{$image_index}-->" value="1" <!--{if $arrForm[$key][$image_index] == "1"}-->checked<!--{/if}--> name="<!--{$key}-->[<!--{$image_index}-->]" />
                        削除する
                    </label>
                </td>
            </tr>      
            <!--{/if}-->      
        </table>
        <!--{/foreach}-->
        <div class="btn-area">
            <ul>
                <li>
                    <a class="btn-action" href="javascript:;" onclick="document.form1.submit();"><span class="btn-next">登録する</span></a>
                </li>
            </ul>
           </div>
    </div>
    
</form>
