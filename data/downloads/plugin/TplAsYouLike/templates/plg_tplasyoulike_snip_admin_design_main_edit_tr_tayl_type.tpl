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
        <tr>
            <th>独自テンプレート種類</th>
            <td>
                <!--{assign var=key value="plg_tplasyoulike_tayl_type"}-->
                <select name="plg_tplasyoulike_tayl_type" style="<!--{$arrErr.plg_tplasyoulike_tayl_type|sfGetErrorColor}-->">
                    <!--{html_options options=$arrTaylTypeList selected=$arrForm.plg_tplasyoulike_tayl_type}-->
                </select>
                <!--{if $arrErr[$key] != ""}-->
                    <div class="message">
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                    </div>
                <!--{/if}-->
            </td>
        </tr>
    <!--{/if}-->
<!--▲ TplAsYouLike-->    
