<!--{*
 * WPPost
 * Copyright(c) 2000-2012 GIZMO CO.,LTD. All Rights Reserved.
 * http://www.gizmo.co.jp/
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

<!--▼ WpPost-->

<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/products.js"></script>
<script type="text/javascript">//<![CDATA[
    function fnSetClassCategories(form, classcat_id2_selected) {
        var $form = $(form);
        var product_id = $form.find('input[name=product_id]').val();
        var $sele1 = $form.find('select[name=classcategory_id1]');
        var $sele2 = $form.find('select[name=classcategory_id2]');
        setClassCategories($form, product_id, $sele1, $sele2, classcat_id2_selected);
    }
    // カゴに入れる
    function fnInCart(productForm) {
        var searchForm = $("#form1");
        var cartForm = $(productForm);
        // 検索条件を引き継ぐ
        var hiddenValues = ['mode','category_id','maker_id','name','orderby','disp_number','pageno','rnd'];
        $.each(hiddenValues, function(){
            // 商品別のフォームに検索条件の値があれば上書き
            if (cartForm.has('input[name='+this+']')) {
                cartForm.find('input[name='+this+']').val(searchForm.find('input[name='+this+']').val());
            }
            // なければ追加
            else {
                cartForm.append($("<input/>").attr("name", this).val(searchForm.find('input[name='+this+']').val()));
            }
        });
        // 商品別のフォームを送信
        cartForm.submit();
    }
//]]></script>

    <form name="form1" id="form1" method="get" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="<!--{$mode|h}-->" />
        <!--{* ▼注文関連 *}-->
        <input type="hidden" name="product_id" value="" />
        <input type="hidden" name="classcategory_id1" value="" />
        <input type="hidden" name="classcategory_id2" value="" />
        <input type="hidden" name="product_class_id" value="" />
        <input type="hidden" name="quantity" value="" />
        <!--{* ▲注文関連 *}-->
        <input type="hidden" name="rnd" value="<!--{$tpl_rnd|h}-->" />
    </form>

<div id="wppost">

    <!--▼パンクズ-->
    <div id="topicpath_area">
        <!--{if $cat_lists}-->
            <!--{foreach from=$cat_lists item=cat_list}-->
                <ul id="topicpath" class="clearfix">
                    <li><a href="<!--{$smarty.const.TOP_URLPATH}-->">トップ</a></li>
                        <!--{foreach from=$cat_list item=cat}-->
                            <li><a href="./plg_WpPost_category.php?catid=<!--{$cat.catid}-->" title="<!--{$cat.cat_title}-->"><!--{$cat.cat_name}--></a></li>
                        <!--{/foreach}-->
                    <li><!--{$wp_post.title}--></li>
                </ul>
            <!--{/foreach}-->
        <!--{/if}-->
    </div>
    <!--▲パンクズ-->


    <!--{if $wp_post}-->

        <!--▼ポスト・ページの内容-->
        <h2 class="title"><!--{$wp_post.title}--></h2>
        <div id="wppost_content">
            <!--{if $wp_post.date}--><div class="date"><!--{$wp_post.date|date_format:"%Y/%m/%d（%a）"}--></div><!--{/if}-->
            <!--{if $wp_post.content}--><div class="content"><!--{$wp_post.content}--></div><!--{/if}-->
            <!--{if $wp_post.meta}--><div class="meta"><!--{$wp_post.meta}--></div><!--{/if}-->
        </div><!--#wppost_content-->
        <!--▲ポスト・ページの内容-->

    <!--{else}-->
        <div class="error">記事がありません。</div>
    <!--{/if}-->



    <!--{* 商品表示 *}-->
    <!--{if $arrProducts}-->
    <!--{foreach from=$arrProducts item=arrProduct name=arrProducts}-->

        <!--{if $smarty.foreach.arrProducts.first}-->

            <!--▼ページナビ(上部)-->
            <form name="page_navi_top" id="page_navi_top" action="?">
                <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                <!--{if $tpl_linemax > 0}--><!--{$smarty.capture.page_navi_body|smarty:nodefaults}--><!--{/if}-->
            </form>
            <!--▲ページナビ(上部)-->
        <!--{/if}-->

        <!--{assign var=id value=$arrProduct.product_id}-->
        <!--{assign var=arrErr value=$arrProduct.arrErr}-->
        <!--▼商品-->
        <form name="product_form<!--{$id|h}-->" action="?" onsubmit="return false;">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <div class="list_area clearfix">
            <a name="product<!--{$id|h}-->"></a>
            <div class="listphoto">
                <!--★画像★-->
                <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->">
                    <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct.main_list_image|sfNoImageMainList|h}-->" alt="<!--{$arrProduct.name|h}-->" class="picture" /></a>
            </div>

            <div class="listrightbloc">
                <!--▼商品ステータス-->
                <!--{if count($productStatus[$id]) > 0}-->
                    <ul class="status_icon clearfix">
                        <!--{foreach from=$productStatus[$id] item=status}-->
                            <li>
                                <img src="<!--{$TPL_URLPATH}--><!--{$arrSTATUS_IMAGE[$status]}-->" width="60" height="17" alt="<!--{$arrSTATUS[$status]}-->"/>
                            </li>
                        <!--{/foreach}-->
                    </ul>
                <!--{/if}-->
                <!--▲商品ステータス-->

                <!--★商品名★-->
                <h3>
                    <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->"><!--{$arrProduct.name|h}--></a>
                </h3>
                <!--★価格★-->
                <div class="pricebox sale_price">
                    <!--{$smarty.const.SALE_PRICE_TITLE}-->(税抜)：
                    <span class="price">
                        <span id="price02_default_<!--{$id}-->"><!--{strip}-->
                            <!--{if $arrProduct.price02_min == $arrProduct.price02_max}-->
                                <!--{$arrProduct.price02_min|number_format}-->
                            <!--{else}-->
                                <!--{$arrProduct.price02_min|number_format}-->～<!--{$arrProduct.price02_max|number_format}-->
                            <!--{/if}-->
                        </span><span id="price02_dynamic_<!--{$id}-->"></span><!--{/strip}-->
                        円</span>
                </div>

                <!--★コメント★-->
                <div class="listcomment"><!--{$arrProduct.main_list_comment|h|nl2br}--></div>

                <!--★商品詳細を見る★-->
                <div class="detail_btn">
                    <!--{assign var=name value="detail`$id`"}-->
                    <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_detail_on.jpg','<!--{$name}-->');" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_detail.jpg','<!--{$name}-->');">
                    <img src="<!--{$TPL_URLPATH}-->img/button/btn_detail.jpg" alt="商品詳細を見る" name="<!--{$name}-->" id="<!--{$name}-->" /></a>
                </div>

                <!--▼買い物かご-->
                <input type="hidden" name="product_id" value="<!--{$id|h}-->" />
                <input type="hidden" name="product_class_id" id="product_class_id<!--{$id|h}-->" value="<!--{$tpl_product_class_id[$id]}-->" />

                <div class="cart_area clearfix">
                    <!--{if $tpl_stock_find[$id]}-->
                        <!--{if $tpl_classcat_find1[$id]}-->
                            <div class="classlist">
                                <dl class="size01 clearfix">
                                        <!--▼規格1-->
                                        <dt><!--{$tpl_class_name1[$id]|h}-->：</dt>
                                        <dd>
                                            <select name="classcategory_id1" style="<!--{$arrErr.classcategory_id1|sfGetErrorColor}-->">
                                                <!--{html_options options=$arrClassCat1[$id] selected=$arrProduct.classcategory_id1}-->
                                            </select>
                                            <!--{if $arrErr.classcategory_id1 != ""}-->
                                                <p class="attention">※ <!--{$tpl_class_name1[$id]}-->を入力して下さい。</p>
                                            <!--{/if}-->
                                        </dd>
                                        <!--▲規格1-->
                                </dl>
                                <!--{if $tpl_classcat_find2[$id]}-->
                                    <dl class="size02 clearfix">
                                        <!--▼規格2-->
                                        <dt><!--{$tpl_class_name2[$id]|h}-->：</dt>
                                        <dd>
                                            <select name="classcategory_id2" style="<!--{$arrErr.classcategory_id2|sfGetErrorColor}-->">
                                            </select>
                                            <!--{if $arrErr.classcategory_id2 != ""}-->
                                                <p class="attention">※ <!--{$tpl_class_name2[$id]}-->を入力して下さい。</p>
                                            <!--{/if}-->
                                        </dd>
                                        <!--▲規格2-->
                                    </dl>
                                <!--{/if}-->
                            </div>
                        <!--{/if}-->
                        <div class="cartin clearfix">
                            <div class="quantity">
                                数量：<input type="text" name="quantity" class="box" value="<!--{$arrProduct.quantity|default:1|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr.quantity|sfGetErrorColor}-->" />
                                <!--{if $arrErr.quantity != ""}-->
                                    <br /><span class="attention"><!--{$arrErr.quantity}--></span>
                                <!--{/if}-->
                            </div>
                            <div class="cartin_btn">
                                <!--★カゴに入れる★-->
                                <div id="cartbtn_default_<!--{$id}-->">
                                    <input type="image" id="cart<!--{$id}-->" src="<!--{$TPL_URLPATH}-->img/button/btn_cartin.jpg" alt="カゴに入れる" onclick="fnInCart(this.form); return false;" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_cartin_on.jpg', this);" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_cartin.jpg', this);" />
                                </div>
                                <div class="attention" id="cartbtn_dynamic_<!--{$id}-->"></div>
                            </div>
                        </div>
                    <!--{else}-->
                        <div class="cartbtn attention">申し訳ございませんが、只今品切れ中です。</div>
                    <!--{/if}-->
                </div>
                <!--▲買い物かご-->
            </div>
        </div>
        </form>
        <!--▲商品-->

        <!--{if $smarty.foreach.arrProducts.last}-->
            <!--▼ページナビ(下部)-->
            <form name="page_navi_bottom" id="page_navi_bottom" action="?">
                <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                <!--{if $tpl_linemax > 0}--><!--{$smarty.capture.page_navi_body|smarty:nodefaults}--><!--{/if}-->
            </form>
            <!--▲ページナビ(下部)-->
        <!--{/if}-->

    <!--{foreachelse}-->
        <!--{include file="frontparts/search_zero.tpl"}-->
    <!--{/foreach}-->
    <!--{/if}-->
    <!--{* 商品表示ここまで *}-->

    <!--{if $wppost_comment_show == 1}--><!--{* コメント表示 *}-->

        <!--{if $wppost_comment_login == 1}-->
            <!--{if $tpl_login}-->
                <div class="comment_login">
                    ※会員ログインしています。
                </div>
            <!--{elseif $fb_auth ==1}-->
                <div class="comment_login">
                    ※Facebook認証しています。
                    <form action="<!--{$smarty.const.ROOT_URLPATH}-->wppost/plg_WpPost_post.php?postid=<!--{$postid}-->" method="post" name="fb_dest" id="fb_dest">
                        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                        <input type='hidden' name='mode' value="fb_stop" />
                        <a href="#" onclick="document.fb_dest.submit()">Facebook認証を停止</a>
                    </form>
                </div>
            <!--{elseif $tw_auth ==1}-->
                <div class="comment_login">
                    ※Twitter認証しています。
                    <form action="<!--{$smarty.const.ROOT_URLPATH}-->wppost/plg_WpPost_post.php?postid=<!--{$postid}-->" method="post" name="tw_status_destroy" id="tw_status_destroy">
                        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                        <input type='hidden' name='mode' value="tw_stop" />
                        <a href="#" onclick="document.tw_status_destroy.submit()">Twitter認証を停止</a>
                    </form>
                </div>
            <!--{else}-->
                <div class="comment_login">
                    ※コメントや返信には
                    <!--{if $wppost_comment_login_ec == 1}-->
                        <a href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/login.php">会員ログイン</a>
                    <!--{/if}-->
                    <!--{if $wppost_comment_login_fb == 1}-->
                        <!--{if $wppost_comment_login_ec == 1}-->もしくは<!--{/if}-->
                        <form action="<!--{$smarty.const.ROOT_URLPATH}-->wppost/plg_WpPost_post.php?postid=<!--{$postid}-->" method="post" name="fb_status_start" id="fb_status_start">
                            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                            <input type='hidden' name='mode' value="fb_start" />
                            <a href="#" onclick="document.fb_status_start.submit()">Facebook認証</a>
                        </form>
                    <!--{/if}-->
                    <!--{if $wppost_comment_login_tw == 1}-->
                        <!--{if $wppost_comment_login_ec == 1 || $wppost_comment_login_fb == 1}-->もしくは<!--{/if}-->
                        <form action="<!--{$smarty.const.ROOT_URLPATH}-->wppost/plg_WpPost_post.php?postid=<!--{$postid}-->" method="post" name="tw_status_start" id="tw_status_start">
                            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                            <input type='hidden' name='mode' value="tw_start" />
                            <a href="#" onclick="document.tw_status_start.submit()">Twitter認証</a>
                        </form>
                    <!--{/if}-->
                    が必要です。
                </div>
            <!--{/if}-->
        <!--{/if}-->

        <!--{if $wp_commentlist}--><!--{* コメント *}-->

        <!--{if $comment_num != 0}-->
            <script type="text/javascript">//<![CDATA[
                $(function() {
                    $("div#wppost > div.wp_comment_bloc:gt(<!--{$comment_num-1}-->)").css("display", "none");
                    $("div.comment_limit").css("display","none");
                });
            //]]></script>
        <!--{/if}-->

            <h3 class="comment_title">コメント</h3>

               <div class="comment_count">全部で<!--{$wp_post.comment_count}-->件のコメントがあります。</div>
               <!--{if $comment_num != 0}-->
                   <!--{if $comment_num < $wp_post.comment_count}-->
                       <div class="comment_all">
                           <!--{if $wppost_comment_format == 1}--><!--{* 入れ子 *}-->
                               <!--{$comment_num}-->件のコメントとその返信を表示しています。<a href="#" onclick="return show_all_comment()">全てのコメントを表示</a>
                           <!--{else}--><!--{* フラット *}-->
                               <!--{$comment_num}-->件のコメントを表示しています。<a href="#" onclick="return show_all_comment()">全てのコメントを表示</a>
                           <!--{/if}-->
                       </div>
                       <div  class="comment_limit">全コメントを表示しています。<a href="#" onclick="return show_limit_comment(<!--{$comment_num}-->)">表示数を<!--{$comment_num}-->件に戻す</a></div>
                   <!--{/if}-->
               <!--{/if}-->

            <!--{foreach from=$wp_commentlist item=wp_comment name=foo}--><!--{* コメント取り出し *}-->

                <!--{if $smarty.foreach.foo.first}--><!--{* ループ1回目 *}-->
                <div class="wp_comment_bloc">
                        <ul class="wp_comment" id="comment<!--{$wp_comment.comment_ID}-->">
                            <!--{if $wp_comment.comment_author}--><li class="comment_author"><!--{$wp_comment.comment_author}--></li><!--{/if}-->
                            <!--{if $wp_comment.comment_author_url}--><li class="comment_author_url"><a href="<!--{$wp_comment.comment_author_url}-->" target="_blank"><!--{$wp_comment.comment_author_url}--></a></li><!--{/if}-->
                            <!--{if $wp_comment.comment_date}--><li class="comment_date"><!--{$wp_comment.comment_date|date_format:"%Y/%m/%d（%a）"}--></li><!--{/if}-->
                            <!--{if $wppost_comment_login == 1}--><!--{* ログイン必要 *}-->
                                <!--{if ($tpl_login) || ($fb_auth ==1) || ($tw_auth ==1)}--><!--{* ログイン済み *}-->
                                    <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                                <!--{/if}--><!--{* ログイン済みここまで *}-->
                            <!--{else}--><!--{* ログインいらない *}-->
                                <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                            <!--{/if}--><!--{* ログインここまで *}-->

                            <!--{if $wp_comment.comment_content}--><li class="comment_content"><!--{$wp_comment.comment_content}--></li><!--{/if}-->
                        </ul><!--.wp_comment-->
                        <!--{assign var="root_id" value=$wp_comment.comment_ID}-->

                <!--{else}--><!--{* ループ2回目以降 *}-->

                <!--{if $wppost_comment_format == 1}--><!--{* 入れ子 *}-->

                    <!--▼コメントの内容-->
                    <!--{if $wp_comment.comment_parent <> 0}--><!--{* 基コメントでない場合 *}-->

                        <!--{if $root_id == $wp_comment.comment_parent}--><!--{* 子供コメントの場合 *}-->
                            <ul class="wp_comment_child1" id="comment<!--{$wp_comment.comment_ID}-->">
                                <!--{if $wp_comment.comment_author}--><li class="comment_author"><!--{$wp_comment.comment_author}--></li><!--{/if}-->
                                <!--{if $wp_comment.comment_author_url}--><li class="comment_author_url"><a href="<!--{$wp_comment.comment_author_url}-->" target="_blank"><!--{$wp_comment.comment_author_url}--></a></li><!--{/if}-->
                                <!--{if $wp_comment.comment_date}--><li class="comment_date"><!--{$wp_comment.comment_date|date_format:"%Y/%m/%d（%a）"}--></li><!--{/if}-->

                            <!--{if $wppost_comment_login == 1}--><!--{* ログイン必要 *}-->
                                <!--{if ($tpl_login) || ($fb_auth ==1) || ($tw_auth ==1)}--><!--{* ログイン済み *}-->
                                    <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                                <!--{/if}--><!--{* ログイン済みここまで *}-->
                            <!--{else}--><!--{* ログインいらない *}-->
                                <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                            <!--{/if}--><!--{* ログインここまで *}-->

                                <!--{if $wp_comment.comment_content}--><li class="comment_content"><!--{$wp_comment.comment_content}--></li><!--{/if}-->
                            </ul><!--.wp_comment_child1-->
                            <!--{assign var="child_lank" value=1}-->
                            <!--{assign var="before_comment_id" value=$wp_comment.comment_ID}-->

                        <!--{elseif $before_comment_id == $wp_comment.comment_parent}--><!--{* 直前コメントが自分のparentである場合の子供以下コメント *}-->

                            <ul class="wp_comment_child<!--{$child_lank+1}-->" id="comment<!--{$wp_comment.comment_ID}-->">
                                <!--{if $wp_comment.comment_author}--><li class="comment_author"><!--{$wp_comment.comment_author}--></li><!--{/if}-->
                                <!--{if $wp_comment.comment_author_url}--><li class="comment_author_url"><a href="<!--{$wp_comment.comment_author_url}-->" target="_blank"><!--{$wp_comment.comment_author_url}--></a></li><!--{/if}-->
                                <!--{if $wp_comment.comment_date}--><li class="comment_date"><!--{$wp_comment.comment_date|date_format:"%Y/%m/%d（%a）"}--></li><!--{/if}-->

                            <!--{if $wppost_comment_login == 1}--><!--{* ログイン必要 *}-->
                                <!--{if ($tpl_login) || ($fb_auth ==1) || ($tw_auth ==1)}--><!--{* ログイン済み *}-->
                                    <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                                <!--{/if}--><!--{* ログイン済みここまで *}-->
                            <!--{else}--><!--{* ログインいらない *}-->
                                <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                            <!--{/if}--><!--{* ログインここまで *}-->

                                <!--{if $wp_comment.comment_content}--><li class="comment_content"><!--{$wp_comment.comment_content}--></li><!--{/if}-->
                            </ul><!--.wp_comment_child-->
                            <!--{assign var="child_lank" value=$child_lank+1}-->
                            <!--{assign var="before_comment_id" value=$wp_comment.comment_ID}-->

                        <!--{else}--><!--{* 直前コメントが自分のparentでない場合の子供以下コメント *}-->

                            <ul class="wp_comment_child<!--{$child_lank-1}-->" id="comment<!--{$wp_comment.comment_ID}-->">
                                <!--{if $wp_comment.comment_author}--><li class="comment_author"><!--{$wp_comment.comment_author}--></li><!--{/if}-->
                                <!--{if $wp_comment.comment_author_url}--><li class="comment_author_url"><a href="<!--{$wp_comment.comment_author_url}-->" target="_blank"><!--{$wp_comment.comment_author_url}--></a></li><!--{/if}-->
                                <!--{if $wp_comment.comment_date}--><li class="comment_date"><!--{$wp_comment.comment_date|date_format:"%Y/%m/%d（%a）"}--></li><!--{/if}-->

                            <!--{if $wppost_comment_login == 1}--><!--{* ログイン必要 *}-->
                                <!--{if ($tpl_login) || ($fb_auth ==1) || ($tw_auth ==1)}--><!--{* ログイン済み *}-->
                                    <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                                <!--{/if}--><!--{* ログイン済みここまで *}-->
                            <!--{else}--><!--{* ログインいらない *}-->
                                <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                            <!--{/if}--><!--{* ログインここまで *}-->

                                <!--{if $wp_comment.comment_content}--><li class="comment_content"><!--{$wp_comment.comment_content}--></li><!--{/if}-->
                            </ul><!--.wp_comment_child-->
                            <!--{assign var="child_lank" value=$child_lank-1}-->
                            <!--{assign var="before_comment_id" value=$wp_comment.comment_ID}-->

                        <!--{/if}--><!--{* 子供コメントの場合ここまで *}-->

                    <!--{else}--><!--{* 基コメントの場合 *}-->
                </div><!--.wp_comment_bloc-->

                <div class="wp_comment_bloc">
                        <ul class="wp_comment" id="comment<!--{$wp_comment.comment_ID}-->">
                            <!--{if $wp_comment.comment_author}--><li class="comment_author"><!--{$wp_comment.comment_author}--></li><!--{/if}-->
                            <!--{if $wp_comment.comment_author_url}--><li class="comment_author_url"><a href="<!--{$wp_comment.comment_author_url}-->" target="_blank"><!--{$wp_comment.comment_author_url}--></a></li><!--{/if}-->
                            <!--{if $wp_comment.comment_date}--><li class="comment_date"><!--{$wp_comment.comment_date|date_format:"%Y/%m/%d（%a）"}--></li><!--{/if}-->
                            <!--{if $wppost_comment_login == 1}--><!--{* ログイン必要 *}-->
                                <!--{if ($tpl_login) || ($fb_auth ==1) || ($tw_auth ==1)}--><!--{* ログイン済み *}-->
                                    <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                                <!--{/if}--><!--{* ログイン済みここまで *}-->
                            <!--{else}--><!--{* ログインいらない *}-->
                                <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                            <!--{/if}--><!--{* ログインここまで *}-->

                            <!--{if $wp_comment.comment_content}--><li class="comment_content"><!--{$wp_comment.comment_content}--></li><!--{/if}-->
                        </ul><!--.wp_comment-->
                        <!--{assign var="root_id" value=$wp_comment.comment_ID}-->

                    <!--{/if}--><!--{* 基コメントここまで *}-->

                    <!--▲コメントの内容-->

                <!--{else}--><!--{* 入れ子ここまでフラットここから *}-->

                </div><!--.wp_comment_bloc-->

                <div class="wp_comment_bloc">

                    <ul class="wp_comment" id="comment<!--{$wp_comment.comment_ID}-->">
                        <!--{if $wp_comment.comment_author}--><li class="comment_author"><!--{$wp_comment.comment_author}--></li><!--{/if}-->
                        <!--{if $wp_comment.comment_author_url}--><li class="comment_author_url"><a href="<!--{$wp_comment.comment_author_url}-->" target="_blank"><!--{$wp_comment.comment_author_url}--></a></li><!--{/if}-->
                        <!--{if $wp_comment.comment_date}--><li class="comment_date"><!--{$wp_comment.comment_date|date_format:"%Y/%m/%d（%a）"}--></li><!--{/if}-->

                        <!--{if $wppost_comment_login == 1}--><!--{* ログイン必要 *}-->
                            <!--{if ($tpl_login) || ($fb_auth ==1) || ($tw_auth ==1)}--><!--{* ログイン済み *}-->
                                <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                            <!--{/if}--><!--{* ログイン済みここまで *}-->
                        <!--{else}--><!--{* ログインいらない *}-->
                            <li class="comment_reply"><a href="#" onclick="return comment_bloc('<!--{$smarty.const.ROOT_URLPATH}-->', '<!--{$wp_root}-->', <!--{$postid}-->, <!--{$wp_comment.comment_ID}-->, <!--{$wp_comment.comment_parent}-->)">このコメントに返信</a></li>
                        <!--{/if}--><!--{* ログインここまで *}-->

                        <!--{if $wp_comment.comment_content}--><li class="comment_content"><!--{$wp_comment.comment_content}--></li><!--{/if}-->
                    </ul><!--.wp_comment-->

                <!--{/if}--><!--{* フラットここまで *}-->

                <!--{/if}--><!--{* ループ2回目以降ここまで *}-->
            <!--{/foreach}--><!--{* コメント取り出しここまで *}-->

        <!--{if $wp_post.comment_count > 0}--></div><!--.wp_comment_bloc--><!--{/if}-->

        <!--{/if}--><!--{* コメントここまで *}-->

        <!--{if $comment_num != 0}-->
           <!--{if $comment_num < $wp_post.comment_count}-->
               <div class="comment_count">全部で<!--{$wp_post.comment_count}-->件のコメントがあります。</div>
               <div class="comment_all"><!--{$comment_num}-->件を表示しています。（入れ子の場合は親コメントの件数）<a href="#" onclick="return show_all_comment()">全てのコメントを表示</a></div>
               <div  class="comment_limit">全コメントを表示しています。<a href="#" onclick="return show_limit_comment(<!--{$comment_num}-->)">表示数を<!--{$comment_num}-->件に戻す</a></div>
           <!--{/if}-->
       <!--{/if}-->

        <!--{if $wppost_comment_login == 1}--><!--{* ログイン必要 *}-->
            <!--{if ($tpl_login) || ($fb_auth ==1) || ($tw_auth ==1)}--><!--{* ログイン済み *}-->
                <div id="page_comment">
                    <h3 id="reply-title">コメントを残す </h3>
                    <form action="<!--{$wp_root}-->/wp-comments-post.php" method="post" id="commentform">
                        <p class="comment-notes">メールアドレスが公開されることはありません。 <span class="required">*</span> が付いている欄は必須項目です</p>	                  <p class="comment-form-author"><label for="author">名前</label> <span class="required">*</span><input id="author" name="author" type="text" value="" size="30" aria-required='true' /></p>
                        <p class="comment-form-email"><label for="email">メールアドレス</label> <span class="required">*</span><input id="email" name="email" type="text" value="" size="30" aria-required='true' /></p>
                        <p class="comment-form-url"><label for="url">ウェブサイト</label><input id="url" name="url" type="text" value="" size="30" /></p>
                        <p class="comment-form-comment"><label for="comment">コメント</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>
                        <p class="form-allowed-tags">次の<abbr title="HyperText Markup Language">HTML</abbr> タグと属性が使えます:  <code>&lt;a href=&quot;&quot; title=&quot;&quot;&gt; &lt;abbr title=&quot;&quot;&gt; &lt;acronym title=&quot;&quot;&gt; &lt;b&gt; &lt;blockquote cite=&quot;&quot;&gt; &lt;cite&gt; &lt;code&gt; &lt;del datetime=&quot;&quot;&gt; &lt;em&gt; &lt;i&gt; &lt;q cite=&quot;&quot;&gt; &lt;strike&gt; &lt;strong&gt; </code></p>
                        <p class="form-submit">
                            <input name="submit" type="submit" id="submit" value="コメントを送信" />
                            <input type='hidden' name='comment_post_ID' value="<!--{$postid}-->" id='comment_post_ID' />
                            <input type='hidden' name='comment_parent' id='comment_parent' value='0' />
                            <input type='hidden' name='redirect_to' value="<!--{$smarty.const.ROOT_URLPATH}-->wppost/plg_WpPost_post.php?postid=<!--{$postid}-->" id='redirect_to' />
                        </p>
                    </form>
                </div><!-- #page_comment -->
            <!--{/if}--><!--{* ログイン済みここまで *}-->
        <!--{else}--><!--{* ログインいらない *}-->
            <div id="page_comment">
                <h3 id="reply-title">コメントを残す </h3>
                <form action="<!--{$wp_root}-->/wp-comments-post.php" method="post" id="commentform">
                    <p class="comment-notes">メールアドレスが公開されることはありません。 <span class="required">*</span> が付いている欄は必須項目です</p>	                  <p class="comment-form-author"><label for="author">名前</label> <span class="required">*</span><input id="author" name="author" type="text" value="" size="30" aria-required='true' /></p>
                    <p class="comment-form-email"><label for="email">メールアドレス</label> <span class="required">*</span><input id="email" name="email" type="text" value="" size="30" aria-required='true' /></p>
                    <p class="comment-form-url"><label for="url">ウェブサイト</label><input id="url" name="url" type="text" value="" size="30" /></p>
                    <p class="comment-form-comment"><label for="comment">コメント</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>
                    <p class="form-allowed-tags">次の<abbr title="HyperText Markup Language">HTML</abbr> タグと属性が使えます:  <code>&lt;a href=&quot;&quot; title=&quot;&quot;&gt; &lt;abbr title=&quot;&quot;&gt; &lt;acronym title=&quot;&quot;&gt; &lt;b&gt; &lt;blockquote cite=&quot;&quot;&gt; &lt;cite&gt; &lt;code&gt; &lt;del datetime=&quot;&quot;&gt; &lt;em&gt; &lt;i&gt; &lt;q cite=&quot;&quot;&gt; &lt;strike&gt; &lt;strong&gt; </code></p>
                    <p class="form-submit">
                        <input name="submit" type="submit" id="submit" value="コメントを送信" />
                        <input type='hidden' name='comment_post_ID' value="<!--{$postid}-->" id='comment_post_ID' />
                        <input type='hidden' name='comment_parent' id='comment_parent' value='0' />
                        <input type='hidden' name='redirect_to' value="<!--{$smarty.const.ROOT_URLPATH}-->wppost/plg_WpPost_post.php?postid=<!--{$postid}-->" id='redirect_to' />
                    </p>
                </form>
            </div><!-- #page_comment -->
        <!--{/if}--><!--{* ログインここまで *}-->

    <!--{/if}--><!--{* コメント表示ここまで *}-->


</div><!--#wppost-->
<!--▲ WpPost-->