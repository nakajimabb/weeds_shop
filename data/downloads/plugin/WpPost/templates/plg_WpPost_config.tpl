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
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<script type="text/javascript">
</script>

<h2><!--{$tpl_subtitle}--></h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit">
<p>WordpressのPostを表示する際の詳細な設定が行えます。<br/>
    <br/>
</p>
<div style="background: #FFC;padding: 10px;margin-bottom: 1em;">
<p style="font-weight: bold;margin-bottom: 10px;">WordPress側の必須作業</strong></p>
WordPressインストールディレクトリの<strong>wp-config.php</strong>書き換え
<ul style="margin-bottom: 10px;">
    <li style="list-style-type: disc; list-style-position: inside;">DB_NAME → WPDB_NAME</li>
    <li style="list-style-type: disc; list-style-position: inside;">DB_USER → WPDB_USER</li>
    <li style="list-style-type: disc; list-style-position: inside;">DB_PASSWORD → WPDB_PASSWORD</li>
    <li style="list-style-type: disc; list-style-position: inside;">DB_HOST → WPDB_HOST</li>
</ul>
WordPressインストールディレクトリの<strong>wp-includes/load.php</strong>書き換え
<ul style="margin-bottom: 10px;">
    <li style="list-style-type: disc; list-style-position: inside;">$wpdb = new wpdb( WPDB_USER, WPDB_PASSWORD, WPDB_NAME, WPDB_HOST );</li>
</ul>
<p>※DB_NAME、DB_USER、DB_PASSWORDはEC-CUBEで既に使われていますので、上記変更をお願いします。WordPressインストール後の変更で構いません</p>
<p>※コメントの承認はWordPress管理画面の設定&nbsp;&gt;&nbsp;ディスカッション設定の「<strong>コメント表示条件</strong>」で設定してください。<p>
<p>※コメントの管理はWordPress管理画面からお願いします。<p>
</div>
<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
        <td colspan="2" width="90" bgcolor="#f3f3f3">▼WPPost詳細設定</td>
    </tr>
    <tr>
    	<td colspan="2" bgcolor="#f3f3f3">共通設定</td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">WordPressインストールディレクトリ</td>
        <td>
            Document Rootを基準としたWordPressのインストール場所<br />
            Document Root直下にwordpressというディレクトリでインストールした場合 /wordpress<br />
            <!--{assign var=key value="wp_install_dir"}-->
            <input type="text" class="box60" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">CSS</td>
        <td>
        <!--{assign var=key value="css_data"}-->
        <span class="red"><!--{$arrErr[$key]}--></span><br />
        <textarea name="<!--{$key}-->" cols="60" rows="8" class="area60" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" ><!--{$arrForm[$key]|h}--></textarea><br />
        <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
        </td>
    </tr>
    <tr>
    	<td colspan="2" bgcolor="#f3f3f3">一覧用設定</td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">表示件数<br /><span class="red">※</span>新着順</td>
        <td>
            <!--{assign var=key value="postnum"}-->
                <input type="text" class="box60" name="<!--{$key}-->" value="<!--{$arrForm[$key]|default:5|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">表示形式<br /><span class="red">※</span>指定した形式で表示されます</td>
        <td>
            <!--{assign var=key value="format"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <input type="radio" name="<!--{$key}-->" value="1" <!--{if $arrForm.format == "1"}-->checked<!--{/if}--> >ポストのみ</input><br/>
            <input type="radio" name="<!--{$key}-->" value="2" <!--{if $arrForm.format == "2"}-->checked<!--{/if}--> >固定ページのみ</input>
            <input type="radio" name="<!--{$key}-->" value="3" <!--{if $arrForm.format == "3"}-->checked<!--{/if}--> >ポスト&amp;固定ページ</input>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">Category ID<br /><span class="red">※</span>指定したカテゴリのポストが表示されます</td>
        <td>
            複数カテゴリを指定する場合,（カンマ）区切りで入力
        <!--{assign var=key value="category"}-->
        <input type="text" class="box60" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
        </td>
    </tr>

    <tr>
    	<td colspan="2" bgcolor="#f3f3f3">コメント用設定</td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">コメントの表示<br /><span class="red">※</span></td>
        <td>
            <!--{assign var=key value="show_comment"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <input type="radio" name="<!--{$key}-->" value="0" <!--{if $arrForm.show_comment == "0"}-->checked<!--{/if}--> >しない</input>
            <input type="radio" name="<!--{$key}-->" value="1" <!--{if $arrForm.show_comment == "1"}-->checked<!--{/if}--> >する</input>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">コメントの表示順<br /><span class="red">※</span></td>
        <td>
            <!--{assign var=key value="comment_turn"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <input type="radio" name="<!--{$key}-->" value="0" <!--{if $arrForm.comment_turn == "0"}-->checked<!--{/if}--> >新着順</input>
            <input type="radio" name="<!--{$key}-->" value="1" <!--{if $arrForm.comment_turn == "1"}-->checked<!--{/if}--> >古いものから</input>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">コメントするのにログインが必要か<br /><span class="red">※</span></td>
        <td>
            <!--{assign var=key value="comment_login"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <input type="radio" name="<!--{$key}-->" value="0" <!--{if $arrForm.comment_login == "0"}-->checked<!--{/if}--> >不要</input>
            <input type="radio" name="<!--{$key}-->" value="1" <!--{if $arrForm.comment_login == "1"}-->checked<!--{/if}--> >必要</input>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">ログイン方法<br /><span class="red">※にログイン必要の場合必須</span></td>
        <td>
            <span class="red"><!--{if ($arrForm.comment_login_ec != "1") && ($arrForm.comment_login_fb != "1") && ($arrForm.comment_login_tw != "1")}-->ログイン方法が選択されていません<!--{/if}--></span>
            <input type="checkbox" name="comment_login_ec" value="1" <!--{if $arrForm.comment_login_ec == "1"}-->checked<!--{/if}--> >EC-CUBE会員</input>
            <input type="checkbox" name="comment_login_fb" value="1" <!--{if $arrForm.comment_login_fb == "1"}-->checked<!--{/if}--> >Facebook認証</input>
            <input type="checkbox" name="comment_login_tw" value="1" <!--{if $arrForm.comment_login_tw == "1"}-->checked<!--{/if}--> >Twitter認証</input>
            <div>Facebook認証、Twitter認証にはそれぞれアプリーケーションの登録が必要です。</div>
            <div>Facebookアプリーケーション登録の<a href="http://gizmo.co.jp/wppost/fbapp" target="_blank">詳細はこちらから</a></div>
            <div>Twitterアプリーケーション登録の<a href="http://gizmo.co.jp/wppost/twapp" target="_blank">詳細はこちらから</a></div>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">Facebook App IDとApp Secre<br /><span class="red">※ログイン方法でFacebookを選択した場合必須</span></td>
        <td>
            <!--{assign var=key value="fb_appid"}-->
            <span class="red"><!--{if ($arrForm.comment_login_fb == "1") && ($arrForm.fb_appid == "0")}-->Facebook App IDを設定をしてください<!--{/if}--></span>
            <label for="<!--{$key}-->">Facebook App ID</label><input type="text" class="box60" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
            <!--{assign var=key value="fb_secret"}-->
            <span class="red"><!--{if ($arrForm.comment_login_fb == "1") && ($arrForm.fb_secret == "0")}-->Facebook App Secretを設定をしてください<!--{/if}--></span>
            <label for="<!--{$key}-->">Facebook App Secret</label><input type="text" class="box60" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">Twitter Consumer keyとConsumer Secret<br /><span class="red">※ログイン方法でTwitterを選択した場合必須</span></td>
        <td>
            <!--{assign var=key value="tw_consumer_key"}-->
            <span class="red"><!--{if ($arrForm.comment_login_tw == "1") && ($arrForm.tw_consumer_key == "")}-->Twitter Consumer keyを設定をしてください<!--{/if}--></span>
            <label for="<!--{$key}-->">Twitter Consumer key</label><input type="text" class="box60" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
            <!--{assign var=key value="tw_consumer_secret"}-->
            <span class="red"><!--{if ($arrForm.comment_login_tw == "1") && ($arrForm.tw_consumer_secret == "")}-->Twitter Consumer secretを設定をしてください<!--{/if}--></span>
            <label for="<!--{$key}-->">Twitter Consumer secret</label><input type="text" class="box60" name="<!--{$key}-->" id="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">コメントの表示を入れ子にするか<br /><span class="red">※</span></td>
        <td>
            <!--{assign var=key value="comment_format"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <input type="radio" name="<!--{$key}-->" value="0" <!--{if $arrForm.comment_format == "0"}-->checked<!--{/if}--> >しない</input>
            <input type="radio" name="<!--{$key}-->" value="1" <!--{if $arrForm.comment_format == "1"}-->checked<!--{/if}--> >する</input>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">ページ表示時のコメント表示数<br /><span class="red">※</span>0で全て表示</td>
        <td>
            <!--{assign var=key value="comment_num"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <input type="text" class="box60" name="<!--{$key}-->" value="<!--{$arrForm[$key]|default:0|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
        </td>
    </tr>
</table>

<div class="btn-area">
    <ul>
        <li>
            <a class="btn-action" href="javascript:;" onclick="document.form1.submit();return false;"><span class="btn-next">この内容で登録する</span></a>
        </li>
    </ul>
</div>

</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
