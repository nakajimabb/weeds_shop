<!--{*
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
 *}-->
<!--{$arrOrder.order_name01}--> <!--{$arrOrder.order_name02}--> 様

<!--{$CONF.shop_name}-->でございます。
下記のご注文いただいた商品を本日発送しましたので、お知らせいたします。
商品到着はプロジェクトαの着日に準じます。

<!--{$tpl_header}-->

************************************************
　ご請求金額
************************************************

ご注文番号：<!--{$arrOrder.order_id}-->
お支払合計：￥ <!--{$arrOrder.payment_total|number_format|default:0}-->
ご決済方法：<!--{$arrOrder.payment_method}-->
受取店舗：<!--{$arrOrder.receive_shop}-->

************************************************
　ご注文商品明細
************************************************

<!--{section name=cnt loop=$arrOrderDetail}-->
商品コード: <!--{$arrOrderDetail[cnt].product_code}-->
商品名: <!--{$arrOrderDetail[cnt].product_name}--> <!--{$arrOrderDetail[cnt].classcategory_name1}--> <!--{$arrOrderDetail[cnt].classcategory_name2}-->
単価：￥ <!--{$arrOrderDetail[cnt].price|sfCalcIncTax|number_format}-->
数量：<!--{$arrOrderDetail[cnt].quantity}-->

<!--{/section}-->
-------------------------------------------------
小　計 ￥ <!--{$arrOrder.subtotal|number_format|default:0}--> (うち消費税 ￥<!--{$arrOrder.tax|number_format|default:0}-->）
<!--{if $arrOrder.use_point > 0}-->
値引き ￥ <!--{$arrOrder.use_point*$smarty.const.POINT_VALUE+$arrOrder.discount|number_format|default:0}-->
<!--{/if}-->
送　料 ￥ <!--{$arrOrder.deliv_fee|number_format|default:0}-->
手数料 ￥ <!--{$arrOrder.charge|number_format|default:0}-->
============================================
合　計 ￥ <!--{$arrOrder.payment_total|number_format|default:0}-->

<!--{$tpl_footer}-->
