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

<!--▼ WpPost Category-->
<div id="wpcategory">
    <!--{if $wp_postcats}-->

        <div id="topicpath_area">
            <ul id="topicpath" class="clearfix">
                <li><a href="<!--{$smarty.const.TOP_URLPATH}-->">トップ</a></li>
                <!--{if $wp_parent_catID}-->
                    <li><a href="./plg_WpPost_category.php?catid=<!--{$wp_parent_catID}-->"><!--{$wp_parent_catName}--></a></li>
                <!--{/if}-->
                <li><!--{$wp_catname}--></li>
            </ul>
        </div>

        <h2 class="title"><!--{$wp_catname}--></h2>
        <div id="wpcategory_content">

            <ul id="post">
                <!--{foreach from=$wp_postcats item=wp_postcat}-->
                    <li>
                        <div class="post_title"><a href="./plg_WpPost_post.php?postid=<!--{$wp_postcat.postid}-->"><!--{$wp_postcat.title}--></a></div>
                        <div class="post_summary"><!--{$wp_postcat.summary}--><a href="./plg_WpPost_post.php?postid=<!--{$wp_postcat.postid}-->">...</a></div>
                        <div class="post_date"><!--{$wp_postcat.date}--></div>
                    </li>
                <!--{/foreach}-->
            </ul>

                <!--{foreach from=$wp_sec_postcats item=var_r}-->
                    <!--{section name=s loop=$var_r}-->
                        <!--{if $smarty.section.s.first}-->
                            <div id="subcategory"><a href="./plg_WpPost_category.php?catid=<!--{$var_r[s].categoryID}-->"><!--{$var_r[s].categoryName}--></a></div>
                            <ul id="subpost">
                                <li>
                                    <div class="post_title"><a href="./plg_WpPost_post.php?postid=<!--{$var_r[s].postid}-->"><!--{$var_r[s].title}--></a></div>
                                    <div class="post_summary"><!--{$var_r[s].summary}--><a href="./plg_WpPost_post.php?postid=<!--{$var_r[s].postid}-->">...</a></div>
                                    <div class="post_date"><!--{$var_r[s].date}--></div>
                                </li>
                        <!--{elseif $smarty.section.s.last}-->
                                <li>
                                    <div class="post_title"><a href="./plg_WpPost_post.php?postid=<!--{$var_r[s].postid}-->"><!--{$var_r[s].title}--></a></div>
                                    <div class="post_summary"><!--{$var_r[s].summary}--><!--{$var_r[s].summary}--><a href="./plg_WpPost_post.php?postid=<!--{$var_r[s].postid}-->">...</a></div>
                                    <div class="post_date"><!--{$var_r[s].date}--></div>
                                </li>
                            </ul>
                        <!--{else}-->
                                <li>
                                    <div class="post_title"><a href="./plg_WpPost_post.php?postid=<!--{$var_r[s].postid}-->"><!--{$var_r[s].title}--></a></div>
                                    <div class="post_summary"><!--{$var_r[s].summary}--><!--{$var_r[s].summary}--><a href="./plg_WpPost_post.php?postid=<!--{$var_r[s].postid}-->">...</a></div>
                                    <div class="post_date"><!--{$var_r[s].date}--></div>
                                </li>
                        <!--{/if}-->
                    <!--{/section}-->
                <!--{/foreach}-->

        </div><!--#wpcategory_content-->

    <!--{else}-->
        <div class="error">カテゴリがありません。</div>
    <!--{/if}-->

</div><!--#wpcategory-->