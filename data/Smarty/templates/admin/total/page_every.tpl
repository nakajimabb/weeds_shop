<!--{*
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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
 */
*}-->

<table id="total-member" class="list">
    <tr>
        <th>社員番号</th>
        <th>会員ID</th>
        <th>氏名</th>
        <th>購入件数</th>
        <th>支払合計</th>
        <th>詳細</th>
    </tr>

    <!--{section name=cnt loop=$arrResults}-->
        <!--{* 色分け判定 *}-->
        <!--{assign var=type value="`$smarty.section.cnt.index%2`"}-->
        <!--{if $type == 0}-->
            <!--{* 偶数行 *}-->
            <!--{assign var=color value="even"}-->
        <!--{else}-->
            <!--{* 奇数行 *}-->
            <!--{assign var=color value="odd"}-->
        <!--{/if}-->

        <tr class="<!--{$color}-->">
            <td class="center"><!--{*社員番号*}--><!--{$arrResults[cnt].staff_no}--></td>
            <td class="center"><!--{*会員ID*}--><!--{$arrResults[cnt].customer_id}--></td>
            <td class="left"><!--{*氏名*}--><!--{$arrResults[cnt].member_name}--></td>
            <td class="right"><!--{*購入件数*}--><!--{$arrResults[cnt].order_count}-->件</td>
            <td class="right"><!--{*購入合計*}--><!--{$arrResults[cnt].total|number_format}-->円</td>
            <td class="right">
                <a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->total/detail.php?id=<!--{$arrResults[cnt].customer_id}-->&year=<!--{$Year}-->&month=<!--{$Month}-->">
                    <!--{*詳細*}-->詳細
                </a>
            </td>
        </tr>
    <!--{/section}-->

    <tr>
        <th>社員番号</th>
        <th>会員ID</th>
        <th>氏名</th>
        <th>購入件数</th>
        <th>支払合計</th>
        <th>詳細</th>
    </tr>
</table>
