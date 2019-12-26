<!--{*
 * TplAsYouLike
 * Copyright (C) 2012 SUNATMARK CO.,LTD. All Rights Reserved.
 * http://www.sunatmark.co.jp/
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
 
<!--▼ TplAsYouLike-->
    <!--{if $plg_TplAsYouLike_isTaylMode}-->
    <input type="hidden" name="filename" value="<!--{$tayl_dummy_filename}-->" />
    <!--{else}-->
        <tr>
        <th>URL</th>
            <td>
                <!--{assign var=key value="filename"}-->
                <!--{if $arrForm.edit_flg.value == 2}-->
                    <!--{$smarty.const.HTTP_URL|h}--><!--{$arrForm[$key].value|h}-->.php
                    <input type="hidden" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" />
                <!--{else}-->
                    <!--{$smarty.const.USER_URL|h}--><input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ime-mode: disabled;" size="40" class="box40" />.php<span class="attention"> (上限<!--{$arrForm[$key].length|h}-->文字)</span>
                <!--{/if}-->
                <!--{if $arrErr[$key] != ""}-->
                    <div class="attention">
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                    </div>
                <!--{/if}-->
            </td>
        </tr>
    <!--{/if}-->
<!--▲ TplAsYouLike-->    
