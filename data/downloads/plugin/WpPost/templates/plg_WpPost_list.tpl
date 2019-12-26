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
<div class="block_outer">
    <h2 class="block_title">ブログ速報</h2>
    <ul id="wppost_list">
    <!--{foreach from=$wp_postlist item=wp_post}-->
        <li><a href="<!--{$smarty.const.ROOT_URLPATH}-->wppost/plg_WpPost_post.php?postid=<!--{$wp_post.ID}-->"><!--{$wp_post.title}--><br /><!--{$wp_post.date}--></a></li>
    <!--{/foreach}-->
    </ul>
</div>
<!--▲ WpPost-->