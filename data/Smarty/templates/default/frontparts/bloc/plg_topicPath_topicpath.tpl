<!--{*
 * TopicPath
 * Copyright (C) 2012 LOCKON CO.,LTD. All Rights Reserved.
 * http://www.lockon.co.jp/
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
<style>
div#topicpath_area {
    margin: 5px auto 0 auto;
    width: 960px;
}
div#topicpath_area li:last-child {
    font-weight: bold;
}

</style>

<!--▼ TopicPath-->
<div id="topicpath_area">
     <ul id="topicpath">
         <li><a href="<!--{$smarty.const.HTTP_URL}-->index.php">ホーム</a></li>
         <!--{section name=cnt loop=$arrTopicPath}-->
             <!--{if $arrTopicPath[cnt].link != ""}-->
             <li>＞ <a href="<!--{$arrTopicPath[cnt].link}-->"><!--{$arrTopicPath[cnt].name}--></a></li>
             <!--{else}-->
             <li>＞ <!--{$arrTopicPath[cnt].name}--></li>
             <!--{/if}-->
         <!--{/section}-->
     </ul>
</div>
<!--▲ TopicPath-->