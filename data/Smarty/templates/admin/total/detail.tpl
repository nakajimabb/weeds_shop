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

<div id="total" class="contents-main">
    
    <h1><!--{$Customer.name01}--> <!--{$Customer.name02}-->(社員番号 <!--{$Customer.staff_no}-->) <!--{$Year}-->年<!--{$Month}-->月 購入額</h1>

    <table style='width:300px;margin:30px;'>
        <tr>
            <th style='width:60%;'>支払総額</th>
            <td style='text-align:right'><!--{$TotalPayment}--></td>
        </tr>
        <tr>
            <th>使用ポイント合計</th>
            <td style='text-align:right'><!--{$TotalPoint}--></td>
        </tr>
    </table>
    

    <!--{foreach from=$Order item=item}-->
        <h4>注文番号：<!--{$item.order_id}-->　注文日：<!--{$item.create_date}-->　発送日：<!--{$item.commit_date}--></h4>
        <table id="total-member" class="list" style='margin-bottom:2px;'>
            <tr>
                <th style='width:50%;'>商品名</th>
                <th>単価</th>
                <th>数量</th>
            </tr>
        <!--{foreach from=$item.detail item=order}-->
            <tr>
                <td>
                    <!--{$order.product_name}-->
                    <!--{if strlen($order.classcategory_name1) > 0}-->
                        [<!--{$order.classcategory_name1}-->]
                    <!--{/if}-->
                </td>
                <td style='text-align:right'><!--{$order.price}--></td>
                <td style='text-align:center'><!--{$order.quantity}--></td>
            </tr>
        <!--{/foreach}-->
        </table>
        <table style='width:300px;margin-top:0px;'>
            <tr>
                <th style='width:60%'>消費税</th>
                <td style='text-align:right'><!--{$item.tax}--></td>
            </tr>
            <tr>
                <th>送料</th>
                <td style='text-align:right'><!--{$item.deliv_fee}--></td>
            </tr>
            <tr>
                <th>使用ポイント</th>
                <td style='text-align:right'><!--{$item.use_point}--></td>
            </tr>
            <tr>
                <th>合計金額</th>
                <td style='text-align:right'><!--{$item.payment_total}--></td>
            </tr>
        </table>
    <!--{/foreach}-->
</div>
