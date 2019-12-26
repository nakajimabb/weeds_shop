<!--{*
 * TplAsYouLike
 * Copyright (C) 2012 SUNATMARK CO.,LTD. All Rights Reserved.
 * http://www.sunatmark.co.jp/
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

<!--▼ TplAsYouLike-->
<tr>
  <!--{assign var=key value="plg_tplasyoulike_tayl_template_pc"}-->
  <th>PC用テンプレート</th>
  <td>
    <span class="attention"><!--{$arrErr[$key]}--></span>
    <select name="plg_tplasyoulike_tayl_template_pc" style="<!--{$arrErr.maker_id|sfGetErrorColor}-->"><option value="">選択してください</option><!--{html_options options=$plg_TplAsYouLike_arrTaylTemplateListPc selected=$arrForm.plg_tplasyoulike_tayl_template_pc}--></select>
  </td>
</tr>
<tr>
  <!--{assign var=key value="plg_tplasyoulike_tayl_template_mb"}-->
  <th>モバイル用テンプレート</th>
  <td>
    <span class="attention"><!--{$arrErr[$key]}--></span>
    <select name="plg_tplasyoulike_tayl_template_mb" style="<!--{$arrErr.maker_id|sfGetErrorColor}-->"><option value="">選択してください</option><!--{html_options options=$plg_TplAsYouLike_arrTaylTemplateListMb selected=$arrForm.plg_tplasyoulike_tayl_template_mb}--></select>
  </td>
</tr>
<tr>
  <!--{assign var=key value="plg_tplasyoulike_tayl_template_sp"}-->
  <th>スマートフォン用テンプレート</th>
  <td>
    <span class="attention"><!--{$arrErr[$key]}--></span>
    <select name="plg_tplasyoulike_tayl_template_sp" style="<!--{$arrErr.maker_id|sfGetErrorColor}-->"><option value="">選択してください</option><!--{html_options options=$plg_TplAsYouLike_arrTaylTemplateListSp selected=$arrForm.plg_tplasyoulike_tayl_template_sp}--></select>
  </td>
</tr>
<!--▲ TplAsYouLike-->    
