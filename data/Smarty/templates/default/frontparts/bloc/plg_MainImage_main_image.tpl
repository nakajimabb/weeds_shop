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
 
 <script type="text/javascript">
$(function(){
    
    var container = $('#plgMainImage');
    if(container.find('li').size() > 1){
        // エフェクト時間
        var speed = <!--{$arrPlugin.speed}-->;

        // 切り替え秒数の設定
        var interval = <!--{$arrPlugin.interval}-->;
        
        switch(<!--{$arrPlugin.effect}-->){
            //フェード
            case 0:
                // 画像用配列の準備
                var images = new Array;
                container.css({
                    overflow:'hidden'
                });

                // アンカーも含んだ画像オブジェクトを配列に登録
                container.find('li').each(function(){
                    images[images.length] = $(this);
                });

                // 配列用のインデックスの初期化、最初の画像を表示、
                var index = 0;
                container.html(images[index].clone(true));

                // 画像切り替え処理
                setInterval(function(){
                    index++;
                    // 配列用インデックスの初期化
                    if (index >= images.length) index = 0;

                    // 古い画像を削除
                    container.find('li').remove();
                    // 新しい画像をフェードインで表示
                    container.prepend(images[index].clone(true).fadeIn(speed));
                }, interval);
                break;
            
            //スライド
            case 1:
                var index = 0;
                var size = container.find('li').size();
                var liOptions;
                //IE7以下なら
                if(!jQuery.support.tbody){
                    liOptions = {
                        display:'inline',
                        zoom:'1',
                        position:'relative',
                        letterSpacing:'normal'
                    }
                }
                else{
                    liOptions = {
                        display:'inline-block',
                        position:'relative',
                        letterSpacing:'normal'
                    }
                }
                container.css({
                    position:'relative',
                    overflow:'hidden',
                    whiteSpace:'nowrap',
                    letterSpacing:'-0.4em'
                });
                container.find('li').css(liOptions);

                setInterval(function(){
                    index++;
                    if(size <= index) index = 0;
                    //最初の要素に戻る場合
                    if(index == 0){
                        container.find('li').eq(0).css({left:'100%'}).animate({left:0},speed);
                        container.find('li').eq(size-1).animate({left:'-'+size*100+'%'},speed,null,function(){container.find('li').css({left:0})});
                    }
                    else{
                        container.find('li').animate({left:(-index*100)+'%'},speed);
                    }
                }, interval);
                break;
        }
    }
});
 </script>
 
 <style>
#plgMainImage,
#plgMainImage li,
#plgMainImage img{
    width:100%;
}
 </style>
 
 <!--{if strlen($arrMainImages.arrFile.image[0].filepath) > 0}-->
 <div class="block_outer">
    <ul id="plgMainImage">
        <!--{foreach from=$arrMainImages.arrFile.image key=key item=item}-->

        <!--{if strlen($arrMainImages.url_pc[$key]) > 0}-->
        <!--{assign var=is_link value=true}-->
        <!--{else}-->
        <!--{assign var=is_link value=false}-->
        <!--{/if}-->
        <li>
            <!--{if $is_link}-->
            <a href="<!--{$arrMainImages.url_pc[$key]|h}-->" <!--{if $arrMainImages.target_blank_pc[$key] == 1}-->target="_blank"<!--{/if}-->>
            <!--{/if}-->
                <img src="<!--{$arrMainImages.arrFile.image[$key].filepath}-->" alt="<!--{$arrMainImages.title[$key]|h}-->" />
            <!--{if $is_link}-->
            </a>
            <!--{/if}-->
        </li>
        <!--{/foreach}-->
    </ul>
 </div>
 <!--{/if}-->