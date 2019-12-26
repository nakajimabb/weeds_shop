<!--{*
 * MainImage
 * Copyright(c) 2012 DELIGHT Inc. All Rights Reserved.
 *
 * http://www.delight-web.com/
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
 #imageArea img{
     width:100%;
 }
 </style>
     
<!--{if strlen($arrMainImages.arrFile.image[0].filepath) > 0}-->
<!--{if strlen($arrMainImages.url_mb[0]) > 0}-->
<!--{assign var=is_link value=true}-->
<!--{else}-->
<!--{assign var=is_link value=false}-->
<!--{/if}-->
 <div class="block_outer clearfix">
    <div id="imageArea">
        <!--{if $is_link}-->
        <a href="<!--{$arrMainImages.url_mb[0]|h}-->" <!--{if $arrMainImages.target_blank_mb[0] == 1}-->target="_blank"<!--{/if}-->>
        <!--{/if}-->
           <img src="<!--{$arrMainImages.arrFile.image[0].filepath}-->" alt="<!--{$arrMainImages.title[0]|h}-->"/>
         <!--{if $is_link}-->
       </a>
        <!--{/if}-->
     </div>
 </div>
 <!--{/if}-->