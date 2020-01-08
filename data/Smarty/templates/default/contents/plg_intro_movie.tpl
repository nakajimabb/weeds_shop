<style>
#moviearea ul {
    margin: 0;  /* 余分なマージンを消去 */
    text-align: center; /* 中央寄せ */
    list-style-type: none;
    /*border: solid 1px #ccc;*/
    padding: 20px 100px;
}
#moviearea ul li {
    margin: 0;
    /*width: 49%;*/
    width: 100%;
    /*float: left;*/
}
#moviearea ul li:nth-child(odd) {
    padding-right: 15px;
}

#moviearea ul li p.title {
    font-size: 150%;
    padding-bottom: 10px;
}

#moviearea ul li p {
    /*padding: 5px 15px;*/
}

#moviearea video {
    width: 720px;
    padding-bottom: 10px;
}
</style>

<!--▼CONTENTS-->
<div id="undercolumn">
    <h2 class="title">動画紹介</h2>
    <div id="undercolumn_entry">
        <div id="moviearea" class="clearfix">
        <ul class="clearfix">
        <!--{foreach from=$arrMovie item=movie name=loop}--> 
            <li>
                <p class="title"><!--{$movie.title}--></p>

                <!--{if true}-->
                <div style="text-align:center;">
                    <!--{if $movie.snapimage|strlen >= 1}-->
                        <video controls poster="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_movie/<!--{$movie.snapimage}-->" >
                    <!--{else}-->
                        <video controls>
                    <!--{/if}-->

                    <!--{if $movie.url1|strlen >= 1}-->
                        <source src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_movie/<!--{$movie.url1}-->">
                    <!--{/if}-->
                    <!--{if $movie.url2|strlen >= 1}-->
                        <source src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_movie/<!--{$movie.url2}-->">
                    <!--{/if}-->
                    <!--{if $movie.url3|strlen >= 1}-->
                        <source src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_movie/<!--{$movie.url3}-->">
                    <!--{/if}-->
                    <p>動画を再生するには、videoタグをサポートしたブラウザが必要です。</p>
                    </video>
                </div>
                <!--{/if}-->
                
                <!--{if false}-->
                    <!--{assign var=mw value="480"}-->
                    <!--{assign var=mh value="290"}-->
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width=<!--{$mw}--> height=<!--{$mh}--> id="abc10">
                        <param name="flashvars" value="fms_app=&video_file=<!--{$smarty.const.ROOT_URLPATH}-->upload/save_movie/<!--{$movie.url1}-->&image_file=<!--{$smarty.const.ROOT_URLPATH}-->upload/save_movie/<!--{$movie.snapimage}-->&link_url=&autoplay=false&mute=false&vol=&controllbar=true&buffertime=5" />
                        <param name="allowfullscreen" value="true" />
                        <param name="movie" value="fladance.swf" />
                        <embed src="fladance.swf" width=<!--{$mw}--> height=<!--{$mh}--> name="abc10" allowfullscreen="true" flashvars="fms_app=&video_file=<!--{$smarty.const.ROOT_URLPATH}-->upload/save_movie/<!--{$movie.url1}-->&image_file=<!--{$smarty.const.ROOT_URLPATH}-->upload/save_movie/<!--{$movie.snapimage}-->&link_url=&autoplay=false&mute=false&vol=&controllbar=true&buffertime=5" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                    </object>
                <!--{/if}-->

                <p align="left"><!--{$movie.main_comment}--></p>
            </li>
            
        <!--{/foreach}--> 
        </ul>
    </div>
</div>
<!--▲CONTENTS-->
