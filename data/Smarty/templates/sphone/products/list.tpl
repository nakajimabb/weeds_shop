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
ul.pagecond_area input {
    margin-right: 3px;
    margin-left: 4px;
}
section.condition {
    margin: 2px 10px;
}
ul.pagecond_area {
    padding: 10px;
    border: 1px solid #ccc;
}
ul.pagecond_area li {
    font-size: 87%;
    padding-bottom: 5px;
}
ul.pagecond_area li:last-child {
    padding-bottom: 0;
}
div.listleftblock {
    margin: 0;
    padding: 10px 0;
    width:35%;
    float:left;
}
div.listrightblock {
    margin: 0;
    padding: 10px 10px 10px 0;
    width:60%;
}

div.procuct_list {
    margin: 0;
    padding: 0;
    width: 100%;
    vertical-align: top;
    float: none;
    clear: both;
    display: block;
    position: relative;
    border-bottom: 1px solid #ccc;
    /* cursor: pointer; */
    cursor: auto;
    background: white;
}
div.procuct_list a {
    display: inline;
}
div.procuct_list h3 {
    font-size: 85%;
}
div.procuct_list  div.listcomment table {
    margin: 0px;
    text-align: left;
    font-size: 75%;
}
div.procuct_list  div.listcomment table tr td {
    padding: 4px;
    border: 1px solid #ddd;
}
div.procuct_list  div.listcomment table tr th {
    width: 30%;
    padding: 4px;
    border: 1px solid #ddd;
}
div.procuct_list  ul.detail_btn li {
    float:left;
}
</style>

<script type="text/javascript">//<![CDATA[
    // 並び順を変更
    function fnChangeOrderby(orderby) {
        eccube.setValue('orderby', orderby);
        eccube.setValue('pageno', 1);
        eccube.submitForm();
    }
    // 表示件数を変更
    function fnChangeDispNumber(dispNumber) {
        eccube.setValue('disp_number', dispNumber);
        eccube.setValue('pageno', 1);
        eccube.submitForm();
    }
//]]></script>

<section id="product_list">
    <form name="form1" id="form1" method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="<!--{$mode|h}-->" />
        <input type="hidden" name="category_id" value="<!--{$arrSearchData.category_id|h}-->" />
        <input type="hidden" name="maker_id" value="<!--{$arrSearchData.maker_id|h}-->" />
        <input type="hidden" name="brand_id" value="<!--{$arrSearchData.brand_id|h}-->" />  <!-- added -->
        <input type="hidden" name="name" value="<!--{$arrSearchData.name|h}-->" />
        <input type="hidden" name="orderby" value="<!--{$orderby|h}-->" />
        <input type="hidden" name="disp_number" value="<!--{$disp_number|h}-->" />
        <input type="hidden" name="pageno" value="<!--{$tpl_pageno|h}-->" />
        <input type="hidden" name="rnd" value="<!--{$tpl_rnd|h}-->" />

        <h2 class="title"><!--{$tpl_subtitle|h}--></h2>
        <p class="intro clear"><span class="attention"><span id="productscount"><!--{$tpl_linemax}--></span>件</span>の商品がございます。</p>

        <!--{if false}-->
        <!--▼ページナビ(本文)-->
        <section class="pagenumberarea clearfix">
            <ul>
                <!--{if $orderby != 'price'}-->
                    <li><a href="javascript:fnChangeOrderby('price');" rel="external">価格順</a></li>
                <!--{else}-->
                    <li class="on_number">価格順</li>
                <!--{/if}-->
                <!--{if $orderby != "date"}-->
                    <li><a href="javascript:fnChangeOrderby('date');" rel="external">新着順</a></li>
                <!--{else}-->
                    <li class="on_number">新着順</li>
                <!--{/if}-->
            </ul>
        </section>
        <!--▲ページナビ(本文)-->
        <!--{/if}-->

        <section class="condition">
            <!--▼検索条件-->
            <h3>検索条件</h3>
            <ul class="pagecond_area">
                <!--{if $arrSearchData.maker_id > 0}-->
                    <li><strong>メーカー：</strong><!--{$arrSearch.maker|h}--></li>
                <!--{/if}-->
                <!--{if $arrSearchData.brand_id > 0}-->
                    <li><strong>ブランド：</strong><!--{$arrSearch.brand|h}--></li>
                <!--{/if}-->
                <!--{if $smode == 1}-->
                    <li>
                        <strong>商品カテゴリ：</strong>
                        <!--{assign var=key value="category_id"}-->
                        <select name="<!--{$key}-->"  class="data-role-none" style="width:200px;" onchange="fnSubmit(); return false;">
                        <option value="">指定なし</option>
                        <!--{html_options options=$arrSearchCate selected=$arrForm[$key] }-->
                        </select>
                    </li>
                <!--{elseif $arrSearchData.category_id > 0}-->
                    <li><strong>商品カテゴリ：</strong><!--{$arrSearch.category|h}--></li>
                <!--{/if}-->
                <!--{if $arrSearch.name|strlen >= 1}-->
                    <li><strong>キーワード：</strong><!--{$arrSearch.name|h}--></li>
                <!--{/if}-->
                <li>
                <strong>ステータス：</strong>
                <!--{assign var=key value="search_product_statuses"}-->
                <span class="attention"><!--{$arrErr[$key]|h}--></span>
                <!--{html_checkboxes name="$key" class="data-role-none" options=$arrSTATUS selected=$arrForm[$key] onclick="fnSubmit(); return false;" }-->
                </li>
                <!--{if false}-->
                <li style="text-align: center;padding: 10px 0 0 0;">
                    <p class="btn"><input type="image" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_bloc_search_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_bloc_search.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_bloc_search.jpg" alt="検索" name="search" /></p>
                </li>
                <!--{/if}-->
            </ul>
            <!--▲検索条件-->
        </section>
    </form>

    <!--▼ページナビ(本文)-->
    <!--{capture name=page_navi_body}-->
        <div class="pagenumber_area clearfix">
            <div class="navi" style="font-size:150%;padding-left:10px;"><!--{$tpl_strnavi}--></div>
            <div class="change" style="text-align:right;padding-right:10px;">
                <p>表示件数</p>
                <select name="disp_number" onchange="javascript:fnChangeDispNumber(this.value);"  class="data-role-none">
                    <!--{foreach from=$arrPRODUCTLISTMAX item="dispnum" key="num"}-->
                        <!--{if $num == $disp_number}-->
                            <option value="<!--{$num}-->" selected="selected" ><!--{$dispnum}--></option>
                        <!--{else}-->
                            <option value="<!--{$num}-->" ><!--{$dispnum}--></option>
                        <!--{/if}-->
                    <!--{/foreach}-->
                </select>
            </div>
        </div>
    <!--{/capture}-->
    <!--▲ページナビ(本文)-->
    
    <!--▼ページナビ(上部)-->
    <form name="page_navi_top" id="page_navi_top" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <!--{if $tpl_linemax > 0}--><!--{$smarty.capture.page_navi_body|smarty:nodefaults}--><!--{/if}-->
    </form>
    <!--▲ページナビ(上部)-->

    <hr />   


    <!--{foreach from=$arrProducts item=arrProduct name=arrProducts}-->
        <!--{assign var=id value=$arrProduct.product_id}-->
        <!--{assign var=arrErr value=$arrProduct.arrErr}-->
        <!--▼商品-->
        <div class="procuct_list clearfix">
            <!--★画像★-->
            <div class="listleftblock">
                <a rel="external" href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->" name="product<!--{$arrProduct.product_id}-->" class="productName" style="width:100%;height:100%;display:block;text-align: center;vertical-align: middle;">
                    <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct.main_list_image|sfNoImageMainList|h}-->" style="max-width: 100px;max-height: 100px;text-align: center;vertical-align: middle;" alt="<!--{$arrProduct.name|h}-->" />
                </a>
            </div>

            <div class="listrightblock">
                <!--★商品名★-->
                <h3><a rel="external" href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->" name="product<!--{$arrProduct.product_id}-->" class="productName"><!--{$arrProduct.name|h}--></a></h3>

                <div class="statusArea">
                    <!--▼商品ステータス-->
                    <!--{if count($productStatus[$id]) > 0}-->
                        <ul class="status_icon">
                            <!--{foreach from=$productStatus[$id] item=status}-->
                                <li><!--{$arrSTATUS[$status]}--></li>
                            <!--{/foreach}-->
                        </ul>
                    <!--{/if}-->
                    <!--▲商品ステータス-->
                </div>

                <!--★商品価格★-->
                <p>
                    <span class="pricebox sale_price"><span class="mini"><!--{$smarty.const.SALE_PRICE_TITLE|h}-->(税抜):</span></span>
                    <span class="price">
                        <span id="price02_default_<!--{$id}-->">
                            <!--{if $arrProduct.price02_min == $arrProduct.price02_max}-->
                                <!--{$arrProduct.price02_min|n2s}-->
                            <!--{else}-->
                                <!--{$arrProduct.price02_min|n2s}-->～<!--{$arrProduct.price02_max|n2s}-->
                            <!--{/if}-->
                        </span><span id="price02_dynamic_<!--{$id}-->">
                        </span>円
                    </span>
                </p>

                <!--★商品コメント★-->
                <div class="listcomment"><!--{$arrProduct.main_list_comment}--></div>

                <!--★商品詳細を見る★-->
                <!--{if $arrProduct.comment1|strlen >= 1}-->
                    <ul class="detail_btn mini">
                        <li>詳細説明：</li>
                        <li>
                            <!--{assign var=key value=$arrProduct.maker_id}-->    
                            <a href="<!--{$arrProduct.comment1|h}-->" target=_blank ><!--{$arrMakers.$key}-->のページへ</a>
                            <p>(注文時は戻って下さい)</p>
                        </li>
                    </ul>
                <!--{/if}-->
            </div>
        </div>
        <!--▲商品-->

    <!--{foreachelse}-->
        <!--{include file="frontparts/search_zero.tpl"}-->
    <!--{/foreach}-->

    
    <!--▼ページナビ(上部)-->
    <form name="page_navi_top" id="page_navi_top" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <!--{if $tpl_linemax > 0}--><!--{$smarty.capture.page_navi_body|smarty:nodefaults}--><!--{/if}-->
    </form>
    <!--▲ページナビ(上部)-->

    <!--{if false}-->
    <!--{if count($arrProducts) < $tpl_linemax}-->
        <div class="btn_area">
            <p><a rel="external" href="javascript: void(0);" class="btn_more" id="btn_more_product" onClick="getProducts(<!--{$disp_number|h}-->); return false;">もっとみる(＋<!--{$disp_number|h}-->件)</a></p>
        </div>
    <!--{/if}-->
    <!--{/if}-->
</section>

<!--{include file= 'frontparts/search_area.tpl'}-->

<script>
    var pageNo = 2;
    var url = "<!--{$smarty.const.P_DETAIL_URLPATH}-->";
    var imagePath = "<!--{$smarty.const.IMAGE_SAVE_URLPATH}-->";
    var statusImagePath = "<!--{$TPL_URLPATH}-->";

    function getProducts(limit) {
        eccube.showLoading();
        var i = limit;
        //送信データを準備
        var postData = {};
        $('#form1').find(':input').each(function(){
            postData[$(this).attr('name')] = $(this).val();
        });
        postData["mode"] = "json";
        postData["pageno"] = pageNo;

        $.ajax({
            type: "POST",
            data: postData,
            url: "<!--{$smarty.const.ROOT_URLPATH}-->products/list.php",
            cache: false,
            dataType: "json",
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(textStatus);
                eccube.hideLoading();
            },
            success: function(result){
                var productStatus = result.productStatus;
                for (var product_id in result) {
                    if (isNaN(product_id)) continue;
                    var product = result[product_id];
                    var productHtml = "";
                    var maxCnt = $(".list_area").length - 1;
                    var productEl = $(".list_area").get(maxCnt);
                    productEl = $(productEl).clone(true).insertAfter(productEl);
                    maxCnt++;

                    //商品写真をセット
                    $($(".list_area .listphoto img").get(maxCnt)).attr({
                        src: "<!--{$smarty.const.IMAGE_SAVE_URLPATH}-->" + product.main_list_image,
                        alt: product.name
                    });

                    // 商品ステータスをセット
                    var statusAreaEl = $($(".list_area div.statusArea").get(maxCnt));
                    // 商品ステータスの削除
                    statusAreaEl.empty();

                    if (productStatus[product.product_id] != null) {
                        var statusEl = '<ul class="status_icon">';
                        var statusCnt = productStatus[product.product_id].length;
                        for (var k = 0; k < statusCnt; k++) {
                            var status = productStatus[product.product_id][k];
                            var statusImgEl = '<li>' + status.status_name + '</li>' + "\n";
                            statusEl += statusImgEl;
                        }
                        statusEl += "</ul>";
                        statusAreaEl.append(statusEl);
                    }

                    //商品名をセット
                    $($(".list_area a.productName").get(maxCnt)).text(product.name);
                    $($(".list_area a.productName").get(maxCnt)).attr("href", url + product.product_id);

                    //販売価格をセット
                    var price = $($(".list_area span.price").get(maxCnt));
                    //販売価格をクリア
                    price.empty();
                    var priceVale = "";
                    //販売価格が範囲か判定
                    if (product.price02_min == product.price02_max) {
                        priceVale = product.price02_min_format + '円';
                    } else {
                        priceVale = product.price02_min_format + '～' + product.price02_max_format + '円';
                    }
                    price.append(priceVale);

                    //コメントをセット
                    $($(".list_area .listcomment").get(maxCnt)).text(product.main_list_comment);
                }
                pageNo++;

                //全ての商品を表示したか判定
                if (parseInt($("#productscount").text()) <= $(".list_area").length) {
                    $("#btn_more_product").hide();
                }
                eccube.hideLoading();
            }
        });
    }
</script>
