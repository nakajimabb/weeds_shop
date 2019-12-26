<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
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

<style>
    div.productContents {
        padding-top: 20px;
    }

    p.comment {
        padding: 0 20px;
    }

</style>

<!--{strip}-->
    <!--{if count($arrBestProducts) > 0}-->
        <div class="block_outer clearfix">
            <h2 class="block_title">おすすめ商品</h2>
            <div id="recommend_area">
                <div class="block_body clearfix">
                    <!--{foreach from=$arrBestProducts item=arrProduct name="recommend_products"}-->
                        <div class="product_item clearfix">
                            <div class="productImage">
                                <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->">
                                    <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct.main_list_image|sfNoImageMainList|h}-->" style="max-width: 80px;max-height: 80px;" alt="<!--{$arrProduct.name|h}-->" />
                                </a>
                            </div>
                            <div class="productContents clearfix">
                                <h3>
                                    <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->"><!--{$arrProduct.name|h}--></a>
                                </h3>
                                <p class="sale_price">
                                    <!--{$smarty.const.SALE_PRICE_TITLE}-->(税抜)： <span class="price"><!--{$arrProduct.price02_min|number_format}--> 円</span>
                                </p>
                            </div>
                            <p class="mini comment"><!--{$arrProduct.comment|h|nl2br}--></p>
                        </div>
                        <!--{if $smarty.foreach.recommend_products.iteration % 3 === 0}-->
                            <div class="clear"></div>
                        <!--{/if}-->
                    <!--{/foreach}-->
                </div>
            </div>
        </div>
    <!--{/if}-->
<!--{/strip}-->
