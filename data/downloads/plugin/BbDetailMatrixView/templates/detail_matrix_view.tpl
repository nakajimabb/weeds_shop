<!--{*
* 商品詳細マトリクス表示プラグイン
* Copyright © 2012/05/17 BOKUBLOCK.INC MUKAI YUTO
* http://www.bokublock.jp / ec-cube@bokublock.jp
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*}-->
<!--★BbDetailMatrixView★-->
<div id="DetailMatrixView">
    <table>
        <thead>
          <tr>
              <!--{if $tpl_plugin.free_field1 != 1}-->
              <th>商品コード</th>
              <!--{/if}-->
              <!--{if $tpl_classcat_find1}-->
                  <th>規格1</th>
                  <!--{if $tpl_classcat_find2}-->
                      <th>規格2</th>
                  <!--{/if}-->
              <!--{/if}-->
              <!--{if $tpl_plugin.free_field2 != 1}-->
              <th>通常価格</th>
              <!--{/if}-->
              <th>販売価格</th>
              <!--{if $tpl_plugin.free_field3 != 1}-->
              <th>ポイント</th>
              <!--{/if}-->
              <!--{if $tpl_plugin.free_field4 != 1}-->
              <th>数量</th>
              <!--{/if}-->
              <th></th>
          </tr>
        </thead>
        <!--{foreach name=cnt from=$arrMatrixProducts item=matrixProduct}-->
            <tr>
                <!--★商品コード★-->
                <!--{if $tpl_plugin.free_field1 != 1}-->
                <td><!--{$matrixProduct.product_code}--></td>
                <!--{/if}-->
                <!--{if $tpl_classcat_find1}-->
                    <!--★規格1★-->
                    <td><!--{$matrixProduct.classcategory_name1}--></td>
                    <!--{if $tpl_classcat_find2}-->
                        <!--★規格2★-->
                        <td><!--{$matrixProduct.classcategory_name2}--></td>
                    <!--{/if}-->
                <!--{/if}-->
                <!--★通常価格★-->
                <!--{if $tpl_plugin.free_field2 != 1}-->
                <td>&yen;<!--{$matrixProduct.price01|number_format}--></td>
                <!--{/if}-->
                <!--★販売価格★-->
                <td>&yen;<!--{$matrixProduct.price02|number_format}--></td>
                <!--★ポイント★-->
                <!--{if $tpl_plugin.free_field3 != 1}-->
                <td><!--{$matrixProduct.price02|sfPrePoint:$matrixProduct.point_rate|number_format}--></td>
                <!--{/if}-->
                <!--★数量★-->
                <!--{if $tpl_plugin.free_field4 != 1}-->
                <td>
                    <!--{if $matrixProduct.stock > 0 OR $matrixProduct.stock_unlimited == 1}-->
                        <!--{if $tpl_classcat_find1}-->
                            <input type="text" class="box60" id="quantity_<!--{$matrixProduct.product_class_id}-->" name="quantity_<!--{$matrixProduct.product_class_id}-->" value="1">
                        <!--{else}-->
                            <input type="text" class="box60" name="quantity" value="<!--{$arrForm.quantity.value|default:1|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr.quantity|sfGetErrorColor}-->" >
                            <!--{if $arrErr.quantity != ""}-->
                                <br /><span class="attention"><!--{$arrErr.quantity}--></span>
                            <!--{/if}-->
                        <!--{/if}-->
                    <!--{else}-->
                        ×
                    <!--{/if}-->
                </td>
                <!--{else}-->
                    <!--{if $matrixProduct.stock > 0 OR $matrixProduct.stock_unlimited == 1}-->
                        <!--{if $tpl_classcat_find1}-->
                            <input type="hidden" id="quantity_<!--{$matrixProduct.product_class_id}-->" name="quantity_<!--{$matrixProduct.product_class_id}-->" value="1">
                        <!--{else}-->
                            <input type="hidden" name="quantity" value="<!--{$arrForm.quantity.value|default:1|h}-->" >
                        <!--{/if}-->
                    <!--{/if}-->
                
                <!--{/if}-->
                <!--★カゴに入れる★-->
                <td>
                    <!--{if $matrixProduct.stock > 0 OR $matrixProduct.stock_unlimited == 1}-->
                        <!--{if $tpl_classcat_find1}-->
                            <a href="javascript:void(0)" onclick="matrixProductCartIn('<!--{$matrixProduct.product_class_id}-->','<!--{$matrixProduct.classcategory_id1}-->','<!--{$matrixProduct.classcategory_id2}-->'); return false;" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_cartin_on.jpg','cart');" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_cartin.jpg','cart');"><img src="<!--{$TPL_URLPATH}-->img/button/btn_cartin.jpg" alt="カゴに入れる" name="cart" id="cart" /></a>
                        <!--{else}-->
                            <a href="javascript:void(document.form1.submit())" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_cartin_on.jpg','cart');" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_cartin.jpg','cart');"><img src="<!--{$TPL_URLPATH}-->img/button/btn_cartin.jpg" alt="カゴに入れる" name="cart" id="cart" /></a>
                        <!--{/if}-->
                    <!--{else}-->
                        品切れ
                    <!--{/if}-->
                </td>
            </tr>
        <!--{/foreach}-->
    </table>
        <input type="hidden" name="mode" value="cart" />
        <input type="hidden" name="product_id" value="<!--{$tpl_product_id}-->" />
        <input type="hidden" name="product_class_id" value="<!--{$tpl_product_class_id}-->" id="product_class_id" />
        <input type="hidden" name="favorite_product_id" value="" />
    <!--{if $tpl_classcat_find1}-->
        <input type="hidden" id="quantity" name="quantity" value="" />
        <input type="hidden" id="classcategory_id1" name="classcategory_id1" value="" />
        <!--{if $tpl_classcat_find2}-->
            <input type="hidden" id="classcategory_id2" name="classcategory_id2" value="" />
        <!--{/if}-->
    <!--{/if}-->
    <script type="text/javascript">
        function matrixProductCartIn(product_class_id,classcategory_id1,classcategory_id2){
            document.getElementById("product_class_id").value = product_class_id;
            <!--{if $tpl_classcat_find1}-->
                document.getElementById("classcategory_id1").value = classcategory_id1;
                <!--{if $tpl_classcat_find2}-->
                    document.getElementById("classcategory_id2").value = classcategory_id2;
                <!--{/if}-->
            <!--{/if}-->
            document.getElementById("quantity").value = document.getElementById("quantity_" + product_class_id).value;
            document.form1.submit();
        }
    </script>
</div>
<!--/★BbDetailMatrixView★-->