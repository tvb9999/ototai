<?php
class skin_orders {

	function mainHtml($projectlist = "",$message = "") {
		global $vsLang, $bw;
		$BWHTML .= <<<EOF
					<div class="product_block" style="margin-bottom:0;">
                    	<div class="pro_block_header">
                        	<h2>{$vsLang->getWords("order_title","giỏ hàng")}</h2>
                        </div>
                        <div class="pro_block_wrapper">
							{$message}
							{$projectlist}
                        </div>
                        <div class="pro_block_bottom"></div>
                    </div>
                <br clear="all" />
EOF;
		return $BWHTML;
	}

	function cartSummary($itemCart) {
		global $vsLang, $bw;
		
		$cart = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'];
		
		$BWHTML = <<<EOF
					<if="count($cart['item'])">
							{$itemCart['cart']}
							<div class="cart_confirm">
                            	<div class="cart_cf_header">{$vsLang->getWordsGlobal("order_title_information","Thông tin nhận hàng")}</div>
                                <div id="contact-area" style="padding-top:20px;">
                                	<form id="Formorder" name="Formorder" action="{$bw->base_url}orders/info/" method="post">
	                                    <label for="Name" class="w_label"><b>Họ tên:</b></label>
	                                    <input type="text" name="fullname" id="fullname" class="w_input"> *
	                                    <div class="clear"></div>
	                                    <label for="add" class="w_label"><b>Địa chỉ:</b></label>
	                                    <input type="text" name="address" id="address" class="w_input"> *
	                                    <div class="clear"></div>
	                                    <label for="phone" class="w_label"><b>Điện thoại:</b></label>
	                                    <input type="text" name="phone" id="phone" class="w_input"> *
	                                    <div class="clear"></div>
	                                    <label for="Email" class="w_label"><b>Địa chỉ email:</b></label>
	                                    <input type="text" name="email" id="email" class="w_input"> *
	                                    <div class="clear"></div>
	                                    <label for="Email" class="w_label" style="width: 140px;margin-right:25px;"><b>Ghi chú:</b></label>                                     
										<textarea id="note" rows="2" cols="20" name="note" class="w_input" ></textarea>
	                                    <div class="clear"></div>
	                                    <input type="submit" name="submit" value="Hoàn tất đơn hàng" class="submit-button" style="width:150px; margin:10px 10px 10px 165px; padding:5px;">
                                    </form>                            
                                </div>                                               		
								<script type="text/javascript">
								 	function checkMail(mail){
										var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
										if (!filter.test(mail))
											return false;
										return true;
									}
									$("#Formorder").submit(function(){
										if(!$('#fullname').val()) {
											jAlert('Vui lòng nhập họ tên!','{$bw->vars['global_websitename']} Dialog');
											$('#orderName').focus();
											return false;
										}
										if(!$('#address').val()) {
											jAlert('Vui lòng nhập địa chỉ!','{$bw->vars['global_websitename']} Dialog');
											$('#address').focus();
											return false;
										}
										if(!$('#phone').val()) {
											jAlert('Vui lòng nhập số điện thoại!','{$bw->vars['global_websitename']} Dialog');
											$('#phone').focus();
											return false;
										}
										if(!$('#email').val()){
											jAlert('Vui Lòng Nhập Email!','{$bw->vars['global_websitename']} Dialog');
											$('#email').focus();
											return false;
										}
										if(!checkMail($('#email').val())){
											jAlert('Vui Lòng Nhập Đúng Email!','{$bw->vars['global_websitename']} Dialog');
											$('#email').focus();
											return false;
										}
										return true;
									});
								</script>
							</div>
					<else />
						<a href="{$bw->base_url}home" class="empty" id="kx">Giỏ Hàng Không Có Sản Phẩm Nào</a>
					</if>
EOF;

	return $BWHTML;
	}

	function ItemList($cart,&$quantity = 0) {
		global $vsLang, $bw, $vsPrint;
		$arrayCart = array();
		$vsPrint->addJavaScriptFile('redsun/jquery.numeric');

		$BWHTML .= <<<EOF
							<form id="addEditForm" name ="addEditForm" method="POST"  action="{$bw->base_url}orders/updatecart/">
            					<table width="598px" border="0" cellspacing="0" cellpadding="0">
	                              	<tr>
		                                <td width="5%" class="cart_header"><input type="checkbox" name="checkbox" id="checkbox" onclick="javascript: vsf.select_switch(this.checked,document.addEditForm);"/></td>
		                                <td width="35%" class="cart_header">{$vsLang->getWordsGlobal("order_product_name","Sản phẩm")}</td>
		                                <td width="10%" class="cart_header">{$vsLang->getWordsGlobal("order_product_pic","Hình")}</td>
		                                <td width="10%" class="cart_header">{$vsLang->getWordsGlobal("order_product_num","Số lượng")}</td>
		                                <td width="20%" class="cart_header">{$vsLang->getWordsGlobal("order_product_price","Giá")}</td>
	                              	</tr>
                            	</table>
								<table width="598px" border="0" cellspacing="0" cellpadding="0">
	                            <foreach="$cart as $value">
									<php>
										 $amount = $amount + $value['total'];
										 $total = number_format($value['total'],0,"",".");
										 $quantity = number_format($amount,0,"",".");								 
									</php>  
									<tr>
		                                <td width="5%" class="cart_td"><strong><input type="checkbox" name="arrayCart[{$value['productId']}][productId]" value="{$value['productId']}"/></strong></td>
		                                <td width="35%" class="cart_td">{$value['itemTitle']}</td>
		                                <td width="10%" class="cart_td"><img src="{$value['itemProductImage']}"/></td>
		                                <td width="10%" class="cart_td"><input type="text" id="itemQuantity" class="numeric span" name="arrayCart[{$value['productId']}][itemQuantity]" value="{$value['itemQuantity']}"/></td>                                
		                                <td width="20%" class="cart_td" style="color:#b10505;font-weight:bold;">{$value['itemNumberPrice']} {$vsLang->getWords("order_money","vnđ")}</td>
	                              	</tr>
	                              </foreach>
	                              <tr>
	                              	<td colspan="4" style="height:40px; text-align:right; padding-right:20px; font-weight:bold;">Tổng cộng:</td>
	                              	<td colspan="3" style="height:40px; text-align:right; padding-right:35px; font-weight:bold; color:#b10505;">{$quantity} {$vsLang->getWords("order_money","vnđ")}</td>
	                              </tr>
	                            </table>
                            <div style="padding-left:10px;margin-top:5px;">
                            	<a href="{$bw->base_url}products/" class="command">{$vsLang->getWordsGlobal("order_continue","Tiếp tục mua hàng")}</a> 
                            	<a href="#" onclick="if(checkSelect($('#addEditForm'))) $('#addEditForm').submit();" class="command">{$vsLang->getWordsGlobal("order_update_cart","Cập nhật giỏ hàng")}</a> 
                            	<a href="#" onclick="if(checkSelect($('#addEditForm'))) { $('#addEditForm').attr('action','{$bw->base_url}orders/deletecart/');$('#addEditForm').submit();}" class="command">{$vsLang->getWordsGlobal("order_delete_item","Xoá Sản phẩm")}</a> 
                            	<a href="{$bw->base_url}orders/deleteallcart" class="command">{$vsLang->getWordsGlobal("order_delete_cart","Xoá giỏ hàng")}</a> 
                            </div>    
                		</form>
                 
		                <script>
	                		$(document).ready(function(){
								$("input.numeric").numeric();
							});
		                	function checkSelect(obj){
								if(!obj)
								 	return false;
								countItem=0;
								obj.find("input[type='checkbox']").each(function(){
									if(this.checked){
										countItem ++;
									}});
								if(countItem==0) {
									jAlert('{$vsLang->getWords('err_none_select_items','Vui lòng chọn ít nhất một sản phẩm!')}','{$bw->vars['global_websitename']} Dialog');
									return false;
								}
								return true;
							}
		                </script>
EOF;
			
				return $BWHTML;
	}

	function orderLoading($message){
		global $vsLang,$bw,$vsPrint;
		$BWHTML .= <<<EOF
		
			<script>
					$.loadings.width = 300
					sLoading('$message')
					if($.browser.msie) window.location.href=window.location.href;
					else history.go(0);			
			</script>
EOF;
		return $BWHTML;
	}

	function orderCheckLogin($message){
		global $vsLang,$bw,$vsPrint;
		
		$BWHTML .= <<<EOF
			<script>
					$.loadings.width = 300
					sLoading('$message')
					if($.browser.msie) window.location.href = "{$bw->base_url}users/login-form/";
					else window.location = "{$bw->base_url}users/login-form/"		
			</script>
EOF;
		return $BWHTML;
	}
	
	function loadMessage(){
		global $bw;
		$BWHTML .= <<<EOF
		
		<div class="product_block">
			<div class="pro_block_header">
				<h2>Thanh toán</h2>
			</div>
			<div class="pro_block_wrapper">
				<div class="replay_cart">
					Cảm ơn quý khách đã đặt hàng! Chúng tôi sẽ kiểm tra đơn hàng và liên hệ với quý khách trong thời gian sớm nhất!
					<br clear="all" />
					<a href="{$bw->base_url}home/" class="back_cart">Trở Về</a>
				</div>
				<br clear="all" />
			</div>
			<div class="pro_block_bottom"></div>
		</div>    
		<br clear="all" />
		
EOF;
	return $BWHTML;
	}

}
?>