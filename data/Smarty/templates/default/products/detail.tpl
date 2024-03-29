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
    ul#product_title {
        border-bottom: dotted 2px #888;
        margin: 10px;
        padding: 10px;
    }
    ul#product_title li {
        float:left;
        vertical-align: middle;
        padding-right: 40px;
    }
    ul#product_title p {
        font-size: 90%;
    }
    ul#product_title h2 {
        display: inline;
        font-size: 160%;
    }
    ul#product_title li img {
        float:left;
        height: 30px;
        padding: 5px 10px;
        border-right: 1px solid #ccc;
    }
    ul#product_title li:last-child {
        float:right;
    }
    div.photo {
        text-align: center;
        padding-top: 20px;
        padding-right: 30px;
    }
    div#detailarea .product_code dt,
    div#detailarea .product_code dd {
        display: inline;
    }
    #detailrightbloc dl {
        padding: 5px 20px;
    }
    #detailrightbloc dl.sale_price {
        font-size: 140%;
    }
    #detailrightbloc dl.normal_price {
        font-size: 120%;
    }
    #detailrightbloc .brand dt,
    #detailrightbloc .brand dd {
        display: inline;
    }
    #detailrightbloc .relative_cat dt,
    #detailrightbloc .relative_cat dd {
        display: inline;
    }
    #detailrightbloc .cart_area {
        margin-top: 10px;
        padding: 10px;
        background-color: #ecf5ff;
        border: 1px solid #cef0f4;
        border-radius: 10px;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        font-family: "ヒラギノ角ゴ Pro W3","ＭＳ Ｐゴシック",sans-serif;
    }
    #detailrightbloc div.classlist {
        padding: 5px 20px;
    }
    #detailrightbloc dl.quantity {
        padding: 5px 20px;
        float: left;
    }
    #detailrightbloc div.cartin_btn {
        padding: 0;
        margin-right: 100px;
        text-align: right;
    }
    #detailrightbloc div.favorite_btn {
        padding: 0;
        /*margin-right: 100px;*/
        text-align: right;
    }
    div.main_comment {
        /*margin-top: 5px;*/
        margin: 15px 0;
        padding: 0;
    }
    div.main_comment p {
        font-size: 110%;
        font-weight: bold;
        margin: 5px 0;        
    }
    div.main_comment table {
        margin: 0;
    }
    div.main_comment table th {
        width: 80px;
    }
</style>
<script type="text/javascript">//<![CDATA[
    // 規格2に選択肢を割り当てる。
    function fnSetClassCategories(form, classcat_id2_selected) {
        var $form = $(form);
        var product_id = $form.find('input[name=product_id]').val();
        var $sele1 = $form.find('select[name=classcategory_id1]');
        var $sele2 = $form.find('select[name=classcategory_id2]');
        eccube.setClassCategories($form, product_id, $sele1, $sele2, classcat_id2_selected);
    }
//]]></script>

<div id="undercolumn">

    <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

        <ul id="product_title" class="clearfix">
            <!--{if $isCosmetic === true}-->
                <li>
                <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/<!--{$arrProduct.maker_image|h}-->" >
                <!-- <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/<!--{$arrProduct.brand_image|h}-->" > -->
                </li>
            <!--{/if}-->

            <li>
                <!--★商品名★-->
                <!--{if $isCosmetic === true}-->
                    <p><!--{$arrProduct.brand_name|h}--></p>
                    <h2><!--{$arrProduct.name|h}--></h2>
                <!--{else}-->
                    <h2><!--{$arrProduct.maker_name|h}--> <!--{$arrProduct.name|h}--></h2>
                <!--{/if}-->
            </li>

            <li>
            <!--★商品コード★-->
            <!-- <div style="float:left;vertical-align: bottom"> -->
                商品コード：
                <span id="product_code_default">
                    <!--{if $arrProduct.product_code_min == $arrProduct.product_code_max}-->
                        <!--{$arrProduct.product_code_min|h}-->
                    <!--{else}-->
                        <!--{$arrProduct.product_code_min|h}-->～<!--{$arrProduct.product_code_max|h}-->
                    <!--{/if}-->
                </span><span id="product_code_dynamic"></span>
            <!-- </div> -->
            </li>
        </ul>

        <div id="detailarea" class="clearfix">
            <div id="detailphotobloc">
                <div class="photo">
                    <!--{assign var=key value="main_image"}-->
                    <!--★画像★-->
                    <!--{if $arrProduct.main_large_image|strlen >= 1}-->
                        <a
                            href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct.main_large_image|h}-->"
                            class="expansion"
                            target="_blank"
                        >
                    <!--{/if}-->
                        <img src="<!--{$arrFile[$key].filepath|h}-->" width="<!--{$arrFile[$key].width}-->" height="<!--{$arrFile[$key].height}-->" alt="<!--{$arrProduct.name|h}-->" />
                    <!--{if $arrProduct.main_large_image|strlen >= 1}-->
                        </a>
                    <!--{/if}-->
                </div>
                <!--{if $arrProduct.main_large_image|strlen >= 1}-->
                    <span class="mini">
                            <!--★拡大する★-->
                            <a
                                href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct.main_large_image|h}-->"
                                class="expansion"
                                target="_blank"
                            >
                                画像を拡大する</a>
                    </span>
                <!--{/if}-->
            </div>

            <div id="detailrightbloc">
                <!--▼商品ステータス-->
                <!--{assign var=ps value=$productStatus[$tpl_product_id]}-->
                <!--{if count($ps) > 0}-->
                    <ul class="status_icon clearfix">
                        <!--{foreach from=$ps item=status}-->
                        <li>
                            <img src="<!--{$TPL_URLPATH}--><!--{$arrSTATUS_IMAGE[$status]}-->" width="60" height="17" alt="<!--{$arrSTATUS[$status]}-->" id="icon<!--{$status}-->" />
                        </li>
                        <!--{/foreach}-->
                    </ul>
                <!--{/if}-->
                <!--▲商品ステータス-->

                <!--▼買い物かご-->

                <div class="cart_area clearfix">
                    <input type="hidden" name="mode" value="cart" />
                    <input type="hidden" name="product_id" value="<!--{$tpl_product_id}-->" />
                    <input type="hidden" name="product_class_id" value="<!--{$tpl_product_class_id}-->" id="product_class_id" />
                    <input type="hidden" name="favorite_product_id" value="" />

                    <!--★販売価格★-->
                    <!--{if true}-->
                    <dl class="sale_price">
                        <dt><!--{$smarty.const.SALE_PRICE_TITLE}-->(税抜)：</dt>
                        <dd class="price">
                            <span id="price02_default"><!--{strip}-->
                                <!--{if $arrProduct.price02_min == $arrProduct.price02_max}-->
                                    <!--{$arrProduct.price02_min|number_format}-->
                                <!--{else}-->
                                    <!--{$arrProduct.price02_min|number_format}-->～<!--{$arrProduct.price02_max|number_format}-->
                                <!--{/if}-->
                            <!--{/strip}--></span><span id="price02_dynamic"></span>
                            円
                        </dd>
                    </dl>
                    <!--{/if}-->
                    <!--{if false}-->
                    <dl class="sale_price">
                        <dt><!--{$smarty.const.SALE_PRICE_TITLE}-->(税込)：</dt>
                        <dd class="price">
                            <span id="price02_default"><!--{strip}-->
                                <!--{if $arrProduct.price02_min_inctax == $arrProduct.price02_max_inctax}-->
                                    <!--{$arrProduct.price02_min_inctax|number_format}-->
                                <!--{else}-->
                                    <!--{$arrProduct.price02_min_inctax|number_format}-->～<!--{$arrProduct.price02_max_inctax|number_format}-->
                                <!--{/if}-->
                            <!--{/strip}--></span><span id="price02_dynamic"></span>
                            円
                        </dd>
                    </dl>
                    <!--{/if}-->

                    <!--★通常価格★-->
                    <!--{if true}-->
                    <!--{if $arrProduct.price01_min_inctax > 0}-->
                        <dl class="normal_price">
                            <dt><!--{$smarty.const.NORMAL_PRICE_TITLE}-->(税抜)：</dt>
                            <dd class="price">
                                <span id="price01_default"><!--{strip}-->
                                    <!--{if $arrProduct.price01_min == $arrProduct.price01_max}-->
                                        <!--{$arrProduct.price01_min|number_format}-->
                                    <!--{else}-->
                                        <!--{$arrProduct.price01_min|number_format}-->～<!--{$arrProduct.price01_max|number_format}-->
                                    <!--{/if}-->
                                <!--{/strip}--></span><span id="price01_dynamic"></span>
                                円
                            </dd>
                        </dl>
                        <!--{/if}-->
                    <!--{/if}-->

                    <!--★ポイント★-->
                    <!--{if $smarty.const.USE_POINT !== false}-->
                        <div class="point">ポイント：
                            <span id="point_default"><!--{strip}-->
                                <!--{if $arrProduct.price02_min == $arrProduct.price02_max}-->
                                    <!--{$arrProduct.price02_min|sfPrePoint:$arrProduct.point_rate|number_format}-->
                                <!--{else}-->
                                    <!--{if $arrProduct.price02_min|sfPrePoint:$arrProduct.point_rate == $arrProduct.price02_max|sfPrePoint:$arrProduct.point_rate}-->
                                        <!--{$arrProduct.price02_min|sfPrePoint:$arrProduct.point_rate|number_format}-->
                                    <!--{else}-->
                                        <!--{$arrProduct.price02_min|sfPrePoint:$arrProduct.point_rate|number_format}-->～<!--{$arrProduct.price02_max|sfPrePoint:$arrProduct.point_rate|number_format}-->
                                    <!--{/if}-->
                                <!--{/if}-->
                            <!--{/strip}--></span><span id="point_dynamic"></span>
                            Pt
                        </div>
                    <!--{/if}-->

                    <!--{if $tpl_stock_find}-->
                        <!--{if $tpl_classcat_find1}-->
                            <div class="classlist">
                                <!--▼規格1-->
                                <ul class="clearfix">
                                    <li>種類：</li>
                                    <li>
                                        <select name="classcategory_id1" style="<!--{$arrErr.classcategory_id1|sfGetErrorColor}-->">
                                        <!--{html_options options=$arrClassCat1 selected=$arrForm.classcategory_id1.value}-->
                                        </select>
                                        <!--{if $arrErr.classcategory_id1 != ""}-->
                                        <br /><span class="attention">※ <!--{$tpl_class_name1}-->を入力して下さい。</span>
                                        <!--{/if}-->
                                    </li>
                                </ul>
                                <!--▲規格1-->
                                <!--{if $tpl_classcat_find2}-->
                                <!--▼規格2-->
                                <ul class="clearfix">
                                    <li><!--{$tpl_class_name2|h}-->：</li>
                                    <li>
                                        <select name="classcategory_id2" style="<!--{$arrErr.classcategory_id2|sfGetErrorColor}-->">
                                        </select>
                                        <!--{if $arrErr.classcategory_id2 != ""}-->
                                        <br /><span class="attention">※ <!--{$tpl_class_name2}-->を入力して下さい。</span>
                                        <!--{/if}-->
                                    </li>
                                </ul>
                                <!--▲規格2-->
                                <!--{/if}-->
                            </div>
                        <!--{/if}-->

                        <!--★数量★-->
                        <dl class="quantity">
                            <dt>数量：</dt>
                            <dd><input type="text" class="box60" name="quantity" value="<!--{$arrForm.quantity.value|default:1|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr.quantity|sfGetErrorColor}-->" />
                                <!--{if $arrErr.quantity != ""}-->
                                    <br /><span class="attention"><!--{$arrErr.quantity}--></span>
                                <!--{/if}-->
                            </dd>
                        </dl>

                        <div class="cartin">
                            <div class="cartin_btn">
                                <div id="cartbtn_default">
                                    <!--★カゴに入れる★-->
                                    <a href="javascript:void(document.form1.submit())">
                                        <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_cartin.jpg" alt="カゴに入れる" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="attention" id="cartbtn_dynamic"></div>
                    <!--{else}-->
                        <div class="attention">申し訳ございませんが、只今品切れ中です。</div>
                    <!--{/if}-->
                </div>
                <!--▲買い物かご-->

                <!--★お気に入り登録★-->
                <!--{if $smarty.const.OPTION_FAVORITE_PRODUCT == 1 && $tpl_login === true}-->
                    <div class="favorite_btn">
                        <!--{assign var=add_favorite value="add_favorite`$product_id`"}-->
                        <!--{if $arrErr[$add_favorite]}-->
                            <div class="attention"><!--{$arrErr[$add_favorite]}--></div>
                        <!--{/if}-->
                        <!--{if !$is_favorite}-->
                            <a href="javascript:eccube.changeAction('?product_id=<!--{$arrProduct.product_id|h}-->'); eccube.setModeAndSubmit('add_favorite','favorite_product_id','<!--{$arrProduct.product_id|h}-->');"><img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_add_favorite.jpg" alt="お気に入りに追加" /></a>
                        <!--{else}-->
                            <img src="<!--{$TPL_URLPATH}-->img/button/btn_add_favorite_on.jpg" alt="お気に入り登録済" name="add_favorite_product" id="add_favorite_product" />
                            <script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.tipsy.js"></script>
                            <script type="text/javascript">
                                var favoriteButton = $("#add_favorite_product");
                                favoriteButton.tipsy({gravity: $.fn.tipsy.autoNS, fallback: "お気に入りに登録済み", fade: true });

                                <!--{if $just_added_favorite == true}-->
                                favoriteButton.load(function(){$(this).tipsy("show")});
                                $(function(){
                                    var tid = setTimeout('favoriteButton.tipsy("hide")',5000);
                                });
                                <!--{/if}-->
                            </script>
                        <!--{/if}-->
                    </div>
                <!--{/if}-->

                    <!--★詳細メインコメント★-->
                    <div class="main_comment">
                        <p><!--{$arrProduct.name|h}--> の商品説明</p>
                        <!--{$arrProduct.main_comment}-->
                    </div>

                    <!--▼メーカーURL-->
                    <!--{if $arrProduct.comment1|strlen >= 1}-->
                        <ul class="clearfix">
                            <li>詳細説明：</li>
                            <li>
                                <p><a href="<!--{$arrProduct.comment1|h}-->" target=_blank><!--{$arrProduct.maker_name|h}-->ホームページへ</a></p>
                                <p>(こちらからは購入できません。注文時は戻って下さい。)</p>
                            </li>
                        </ul>
                    <!--{/if}-->
                    <!--▼メーカーURL-->

            </div>
        </div>

    </form>

    <!--詳細ここまで-->

    <!--▼サブコメント-->
    <!--{section name=cnt loop=$smarty.const.PRODUCTSUB_MAX}-->
        <!--{assign var=key value="sub_title`$smarty.section.cnt.index+1`"}-->
        <!--{assign var=ikey value="sub_image`$smarty.section.cnt.index+1`"}-->
        <!--{if $arrProduct[$key] != "" or $arrProduct[$ikey]|strlen >= 1}-->
            <div class="sub_area clearfix">
                <h3><!--★サブタイトル★--><!--{$arrProduct[$key]|h}--></h3>
                <!--{assign var=ckey value="sub_comment`$smarty.section.cnt.index+1`"}-->
                <!--▼サブ画像-->
                <!--{assign var=lkey value="sub_large_image`$smarty.section.cnt.index+1`"}-->
                <!--{if $arrProduct[$ikey]|strlen >= 1}-->
                    <div class="subtext"><!--★サブテキスト★--><!--{$arrProduct[$ckey]|nl2br_html}--></div>
                    <div class="subphotoimg">
                        <!--{if $arrProduct[$lkey]|strlen >= 1}-->
                            <a href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct[$lkey]|h}-->" class="expansion" target="_blank" >
                        <!--{/if}-->
                        <img src="<!--{$arrFile[$ikey].filepath}-->" alt="<!--{$arrProduct.name|h}-->" width="<!--{$arrFile[$ikey].width}-->" height="<!--{$arrFile[$ikey].height}-->" />
                        <!--{if $arrProduct[$lkey]|strlen >= 1}-->
                            </a>
                            <span class="mini">
                                <a href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct[$lkey]|h}-->" class="expansion" target="_blank">
                                    画像を拡大する</a>
                            </span>
                        <!--{/if}-->
                    </div>
                <!--{else}-->
                    <p class="subtext"><!--★サブテキスト★--><!--{$arrProduct[$ckey]|nl2br_html}--></p>
                <!--{/if}-->
                <!--▲サブ画像-->
            </div>
        <!--{/if}-->
    <!--{/section}-->
    <!--▲サブコメント-->

    <!--この商品に対するお客様の声-->
    <div id="customervoice_area">
        <h2>この商品に対するお客様の声</h2>

        <div class="review_bloc clearfix">
            <p>この商品に対するご感想をぜひお寄せください。</p>
            <div class="review_btn">
                <!--{if count($arrReview) < $smarty.const.REVIEW_REGIST_MAX}-->
                    <!--★新規コメントを書き込む★-->
                    <a href="./review.php"
                        onclick="eccube.openWindow('./review.php?product_id=<!--{$arrProduct.product_id}-->','review','600','640'); return false;"
                        target="_blank">
                        <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_comment.jpg" alt="新規コメントを書き込む" />
                    </a>
                <!--{/if}-->
            </div>
        </div>

        <!--{if count($arrReview) > 0}-->
            <ul>
                <!--{section name=cnt loop=$arrReview}-->
                    <li>
                        <p class="voicetitle"><!--{$arrReview[cnt].title|h}--></p>
                        <p class="voicedate"><!--{$arrReview[cnt].create_date|sfDispDBDate:false}-->　投稿者：<!--{if $arrReview[cnt].reviewer_url}--><a href="<!--{$arrReview[cnt].reviewer_url}-->" target="_blank"><!--{$arrReview[cnt].reviewer_name|h}--></a><!--{else}--><!--{$arrReview[cnt].reviewer_name|h}--><!--{/if}-->　おすすめレベル：<span class="recommend_level"><!--{assign var=level value=$arrReview[cnt].recommend_level}--><!--{$arrRECOMMEND[$level]|h}--></span></p>
                        <p class="voicecomment"><!--{$arrReview[cnt].comment|h|nl2br}--></p>
                    </li>
                <!--{/section}-->
            </ul>
        <!--{/if}-->
    </div>
    <!--お客様の声ここまで-->

    <!--▼関連商品-->
    <!--{if $arrRecommend}-->
        <div id="whobought_area">
            <h2><img src="<!--{$TPL_URLPATH}-->img/title/tit_product_recommend.png" alt="その他のオススメ商品" /></h2>
            <!--{foreach from=$arrRecommend item=arrItem name="arrRecommend"}-->
                <div class="product_item">
                    <div class="productImage">
                        <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrItem.product_id|u}-->">
                            <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrItem.main_list_image|sfNoImageMainList|h}-->" style="max-width: 65px;max-height: 65px;" alt="<!--{$arrItem.name|h}-->" /></a>
                    </div>
                    <!--{assign var=price02_min value=`$arrItem.price02_min_inctax`}-->
                    <!--{assign var=price02_max value=`$arrItem.price02_max_inctax`}-->
                    <div class="productContents">
                        <h3><a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrItem.product_id|u}-->"><!--{$arrItem.name|h}--></a></h3>
                        <p class="sale_price"><!--{$smarty.const.SALE_PRICE_TITLE}-->(税込)：<span class="price">
                            <!--{if $price02_min == $price02_max}-->
                                <!--{$price02_min|number_format}-->
                            <!--{else}-->
                                <!--{$price02_min|number_format}-->～<!--{$price02_max|number_format}-->
                            <!--{/if}-->円</span></p>
                        <p class="mini"><!--{$arrItem.comment|h|nl2br}--></p>
                    </div>
                </div><!--{* /.item *}-->
                <!--{if $smarty.foreach.arrRecommend.iteration % 2 === 0}-->
                    <div class="clear"></div>
                <!--{/if}-->
            <!--{/foreach}-->
        </div>
    <!--{/if}-->
    <!--▲関連商品-->

</div>
