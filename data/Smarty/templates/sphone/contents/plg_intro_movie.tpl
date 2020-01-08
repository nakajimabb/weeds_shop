<style>
section#movie_area li {
    display: block;
    clear: both;
    padding: 5px 10px;    
    border-bottom: #ccc solid 1px;
    margin:  0px;
    padding: 15px;
    background-color: #FEFEFE;

/*    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FEFEFE),color-stop(1, #EEEEEE));
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FEFEFE),color-stop(1, #EEEEEE));
*/
}

#movie_area p.movie_comment {
    padding: 5px 10px;
}
    
</style>    

<section id="movie_area">
    <h2 class="title_block">動画紹介</h2>
        <ul>
        <!--{foreach from=$arrMovie item=movie name=loop}--> 
            <li>
            <p align="center"><!--{$movie.title}--></p>
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
            <p class="movie_comment"><!--{$movie.main_comment}--></p>
            </li>
        <!--{/foreach}--> 
        </ul>
</section>
<!--▲コンテンツここまで -->

