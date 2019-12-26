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
    div#news_area {
        padding : 10px 25px 15px 25px;
    }
    #news_area dl.newslist dd {
        margin: 0;
        padding: 5px 0 0 0;
        font-size: 100%;
        line-height: 180%;
    }
    #news_area dl.newslist {
        background: none;
        padding-bottom: 10px;
    }
</style>

<!--{if count($arrNews) > 0}-->
<!--{strip}-->
<div class="block_outer clearfix">
    <h2 class="block_title">お知らせ</h2>
        <div id="news_area">
                <!-- <div class="news_contents"> -->
                <!--{section name=data loop=$arrNews}-->
                <!--{assign var="date_array" value="-"|explode:$arrNews[data].cast_news_date}-->
                <dl class="newslist">
                    <!--{if false}-->
                    <dt><!--{$date_array[0]}-->年<!--{$date_array[1]}-->月<!--{$date_array[2]}-->日</dt>
                    <dt>
                    <!--{/if}-->
                        <a
                            <!--{if $arrNews[data].news_url}--> href="<!--{$arrNews[data].news_url}-->" <!--{if $arrNews[data].link_method eq "2"}--> target="_blank"
                                <!--{/if}-->
                            <!--{/if}-->
                        >
                            <!--{$arrNews[data].news_title}--></a>
                    </dt>
                    <dd><!--{$arrNews[data].news_comment}--></dd>
                </dl>
                <!--{/section}-->
                <!-- </div> -->
        </div>
    </div>
<!--{/strip}-->
<!--{/if}-->
