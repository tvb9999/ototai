<?php
/* 
 * Author   	: sanhnx@redsunic.com
 * Param  		: Unknown
 * Return  		: Unknown
 * Description  : Unknown
 * 
 */
class skin_orders {
	
	function mainLayout($current){
		$BWHTML = <<<EOF
			 <div id='page' class="ui-tabs ui-tabs-panel ui-widget-content ui-corner-bottom">	
					<div class='right-cell' style='width:100%' id="mainContainer">
							$current
					</div>
					<div class="clear"></div>
					<div id="orderView">
					</div>
					<div class="clear"></div>
				</div>
EOF;
		return $BWHTML;
	}
	
	function getListObj($option) {
		global $vsLang,$bw,$vsSettings;
		$bw->input[2] = $bw->input[2]?$bw->input[2]:1;
		$BWHTML = <<<EOF
		<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
			    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
			        <span class="ui-icon ui-icon-triangle-1-e"></span>
			        <span class="ui-dialog-title">{$vsLang->getWords('order_list','Danh sách các hóa đơn')}</span>
			    </div>
			    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
		    		<if=" $vsSettings->getSystemKey(orders.'_deletePage_button',1, 'orders')">
			    		<li class="ui-state-default ui-corner-top">
				        	<a id="deletePage" title="{$vsLang->getWords('pages_deletePage','Delete')}" onclick="deletePage();" href="#">
				        	{$vsLang->getWords('pages_deletePage','Delete')}
							</a>
						</li>
					</if>
			    </ul>
				<table cellspacing="1" cellpadding="1" id='productListTable' width="100%">
					<thead>
					    <tr>
					       	<th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" id="checked-obj" /></th>
					        <th>{$vsLang->getWords('order_name','Họ và tên')}</th>
			                <th>{$vsLang->getWords('order_address','Địa chỉ')}</th>
			                <th>{$vsLang->getWords('order_phone','Địa thoại')}</th>
			                <th>{$vsLang->getWords('order_email','Email')}</th>
			                <th>{$vsLang->getWords('order_time','Ngày đặt hàng')}</th>
			                <th width="110">{$vsLang->getWords('order_option','Tùy chọn')}</th>
					    </tr>
					</thead>
					<tbody>
						<if="count($option['pageList'])">
						<foreach="$option['pageList'] as $obj">
							<tr class="$vsf_class">
								<td align="center" width="20">
									<input type="checkbox" onclicktext="checkObject({$obj->getId()});" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" disabled/>
								</td>
								<td>{$obj->getName()}</td>
								<td>{$obj->getAddress()}</td>
								<td>{$obj->getPhone()}</td>
								<td>{$obj->getEmail()}</td>
								<td>{$obj->getPostDate("VN_LONG")}</td>
								<td><a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" onclick="javascript:vsf.get('orders/viewCart/{$obj->getId()}/','orderView');">{$vsLang->getWords('order_bt_view','Xem đơn hàng')}</a></td>
							</tr>
						</foreach>
						</if>
					</tbody>
					<tfoot>
						<tr>
							<th colspan='7'>
								<div style='float:right;'>{$option['paging']}</div>
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		<script type="text/javascript">
				
				
				function checkObject() {
					var checkedString = '';
					$("input[type=checkbox]").each(function(){
						if($(this).hasClass('myCheckbox')){
							if(this.checked) checkedString += $(this).val()+',';
						}
					});
					checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
					$('#checked-obj').val(checkedString);
				}
				
				function checkAll() {
					var checked_status = $("input[name=all]:checked").length;
					var checkedString = '';
					$("input[type=checkbox]").each(function(){
						if($(this).hasClass('myCheckbox')){
						this.checked = checked_status;
						if(checked_status) checkedString += $(this).val()+',';
						}
					});
					$("span[acaica=myCheckbox]").each(function(){
						if(checked_status)
							this.style.backgroundPosition = "0 -50px";
						else this.style.backgroundPosition = "0 0";
					});
					checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
					$('#checked-obj').val(checkedString);
				}
				
				
				
				function deletePage(){
					jConfirm(
						'{$vsLang->getWords("orders_deleteConfirm","Xóa thông tin đơn hàng?")}', 
						'{$bw->vars['global_websitename']} Dialog', 
						function(r){
							if(r){
								if($('#checked-obj').val()=='') {
									vsf.alert("{$vsLang->getWords('hide_obj_confirm_noneitem', "Bạn chưa chọn!")}");
									return false;
								}
								jsonStr = $('#checked-obj').val();
								vsf.get('orders/deleteOrder/'+jsonStr,'mainContainer');
							}
						}
					);
				}
			</script>

EOF;
		return $BWHTML;
	}
	
	function printOrderItem($obj,$option,&$amount=0){
		global $vsLang,$bw;
		$BWHTML = <<<EOF
		<table cellpadding="1" cellspacing="1" border="0">
	        	<tr align="left">
	        		<th >{$vsLang->getWords('order_name','Họ và tên')}:</th>
					<td>{$obj->getName()}</td>
				</tr>
				<tr align="left">
					<th >{$vsLang->getWords('order_email','Email')}:</th>
					<td>{$obj->getEmail()}</td>
				</tr>
				<tr align="left">
					 <th>{$vsLang->getWords('order_address','Địa chỉ')}:</th>
					<td>{$obj->getAddress()}</td>
				</tr>
				<tr align="left">
					<th>{$vsLang->getWords('order_phone','Điện thoại')}:</th>
					 <td>{$obj->getPhone()}</td>
				</tr>
			</table>
			<div style="padding:5px 0px; color:blue; font-size:18px;"> 
		    	<strong>{$vsLang->getWords('orderitem_list','Danh sách sản phẩm trong giỏ')}</strong>
		    </div>
				<table cellspacing="5" cellpadding="5" border="0">
				<thead>
					    <tr>
					       	<th>{$vsLang->getWords('order_no','STT')}</th> 
							<th>{$vsLang->getWords('order_product_name','Tên sản phẩm')}</th> 
							<!--<th>{$vsLang->getWords('order_product_code','Mã')}</th>--> 
							<th>{$vsLang->getWords('order_product_amount','Số lượng')}</th> 
							<th>{$vsLang->getWords('order_product_price','Đơn giá')}</th> 
							<th>{$vsLang->getWords('order_product_money','Thành tiền')}</th>
					    </tr>
					</thead>
					<if="count($option['cart'])">
						 <foreach="$option['cart'] as $value">
							<php>
								
								
								$total = number_format($value->getPrice(false)*$value->getQuantity(),0,"",".");
								$amount1 = $amount1 + $value->getPrice(false)*$value->getQuantity();
								$amount = number_format($amount1,0,"",".");
							</php>
							<tr class="$vsf_class">
								<td>{$vsf_count}</td>
								<td>{$value->getTitle()}</td>
								<td>{$value->getQuantity()}</td>
								<td>{$value->getPrice()} {$vsLang->getWords('vnd',' VNĐ')}</td>
								<td>{$total} {$vsLang->getWords('vnd',' VNĐ')}</td>
							</tr>
						</foreach>
						</if>
						<if="count($option['gift'])">
						 <foreach="$option['gift'] as $value">
						 <php>
						 	$value['productId'] = unserialize($value['productId']);
						 	$value['reProductId'] = unserialize($value['reProductId']);
						 	$total = number_format($value['price']*$value['quantity'],0,"",".");
							$this->amount1 += $value['price']*$value['quantity'];
							$amount = number_format($this->amount1,0,"",".");
							$value['price'] = number_format($value['price'],0,"",".");
						 </php>
								<tr class="$vsf_class">
									<td>{$vsf_count}</td>
									<td><b>{$value['giftTitle']}</b>
										<if="count($option['gProduct'][$value['relId']])">
				                				<foreach="$value['productId'] as $key=>$gPro">
					                				<php>
						                				$option['gProduct'][$value['relId']][$gPro] = $option['rProduct'][$value['relId']][$gPro][$value['reProductId'][$key]];
					                				</php>
				                        		</foreach>
				                				<foreach="$option['gProduct'][$value['relId']] as $gpro">
				                					<div> - {$gpro->productTitle}</div>
				                				</foreach>
			                        	</if>
									</td>
									<td>{$value['quantity']}</td>
									<td>{$value['price']} {$vsLang->getWords('vnd',' VNĐ')}</td>
									<td>{$total} {$vsLang->getWords('vnd',' VNĐ')}</td>
								</tr>
						</foreach>
						</if>
					<tr><th colspan="4" style="text-align:right;"><strong>Tổng cộng</strong></th><td><strong style="color:red">$amount {$vsLang->getWords('vnd',' VNĐ')}</strong></td></tr>
				</table >
				<div style="cursor: pointer; width:120px;" onclick="window.print();"><img src="{$bw->vars['board_url']}/styles/images/print.jpg"> In đơn hàng</div>
EOF;
		return $BWHTML;
	}
	
	function viewCart(&$option,&$amount){
		global $bw,$vsLang;
		$BWHTML = <<<EOF
		
		<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
			    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
			        <span class="ui-icon ui-icon-triangle-1-e"></span>
			        <span class="ui-dialog-title">{$vsLang->getWords('order_listcart','Chi tiết hóa đơn')}</span>
			    </div>
			     <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
				    	<li class="ui-state-default ui-corner-top" id="success-objlist-bt"><a href="#" title="{$vsLang->getWords('add_obj_ht_bt',"Hoàn tất")}">{$vsLang->getWords('add_obj_ht_bt',"Hoàn tất")}</a></li>
				    	<li class="ui-state-default ui-corner-top" id="pending-objlist-bt"><a href="#" title="{$vsLang->getWords('add_obj_dc_bt',"Đang chờ")}">{$vsLang->getWords('add_obj_dc_bt',"Đang chờ")}</a></li>
				  </ul>
			    <if="count($option['cart']) or count($option['gift'])">
				<table cellspacing="1" cellpadding="1" id='productListTable' width="100%">
					<thead>
					    <tr>
					       	<th width="15"><input type="checkbox" onclick="checkAlls()" onclicktext="checkAlls()" name="all" id="checked-cart" /></th> 
							<th>{$vsLang->getWords('order_status','Tình trạng')}</th> 
					       	<th>{$vsLang->getWords('order_title','Tên')}</th> 
							<th>{$vsLang->getWords('order_product_type','Loại')}</th>
							<th>{$vsLang->getWords('order_product_amount','Số lượng')}</th> 
							<th>{$vsLang->getWords('order_product_price','Đơn giá')}</th> 
							<th>{$vsLang->getWords('order_product_money','Thành tiền')}</th>
					    </tr>
					</thead>
					<tbody>
						<if="count($option['cart'])">
						 <foreach="$option['cart'] as $value">
							<php>
								
								
								$total = number_format($value->getPrice(false)*$value->getQuantity(),0,"",".");
								$this->amount1 += $value->getPrice(false)*$value->getQuantity();
								$amount = number_format($this->amount1,0,"",".");
							</php>
								<tr class="$vsf_class">
									<td align="center">
										<input type="checkbox"  name="obj_{$value->getId()}" value="{$value->getId()}" class="myCheckboxCart" />
									</td>
									<if="$value->getStatus()">
										<td>Hoàn tất</td>
									<else />
										<td>Đang chờ</td>
									</if>
									<td>{$value->getTitle()}</td>
									<td>{$value->getInfo()}</td>
									<td>{$value->getQuantity()}</td>
									<td>{$value->getPrice()} {$vsLang->getWords('vnd',' VNĐ')}</td>
									<td>{$total} {$vsLang->getWords('vnd',' VNĐ')}</td>
								</tr>
						</foreach>
						</if>
						<if="count($option['gift'])">
						 <foreach="$option['gift'] as $value">
						 <php>
						 	$value['productId'] = unserialize($value['productId']);
						 	$value['reProductId'] = unserialize($value['reProductId']);
						 	$total = number_format($value['price']*$value['quantity'],0,"",".");
							$this->amount1 += $value['price']*$value['quantity'];
							$amount = number_format($this->amount1,0,"",".");
							$value['price'] = number_format($value['price'],0,"",".");
						 </php>
								<tr class="$vsf_class">
									<td align="center">
										<input type="checkbox"  name="obj_{$value['relId']}" value="{$value['relId']}" class="myCheckboxGift" />
									</td>
									<if="$value['status']">
										<td>Hoàn tất</td>
									<else />
										<td>Đang chờ</td>
									</if>
									<td><b>{$value['giftTitle']}</b>
										<if="count($option['gProduct'][$value['relId']])">
				                				<foreach="$value['productId'] as $key=>$gPro">
					                				<php>
						                				if($gPro)  $option['gProduct'][$value['relId']][$gPro] = $option['rProduct'][$value['relId']][$gPro][$value['reProductId'][$key]];
					                				</php>
				                        		</foreach>
				                				<foreach="$option['gProduct'][$value['relId']] as $gpro">
				                					<div> - {$gpro->productTitle} {$gpro->optionTitle}</div>
				                				</foreach>
			                        	</if>
									</td>
									<td></td>
									<td>{$value['quantity']}</td>
									<td>{$value['price']} {$vsLang->getWords('vnd',' VNĐ')}</td>
									<td>{$total} {$vsLang->getWords('vnd',' VNĐ')}</td>
								</tr>
						</foreach>
						</if>
						<tr>
						<th  colspan="6" align="right">{$vsLang->getWords('order_product_total','Tổng cộng')}</th>
						<th>$amount {$vsLang->getWords('vnd',' VNĐ')}
						</th>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th colspan='9'>
								<div style='float:right;'><a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" onclick="orderPrint({$bw->input[2]});">In đơn hàng</a></div>
							</th>
						</tr>
					</tfoot>
				</table>
				<else />
				<b>Không có sản phẩm nào.</b>
				</if>
				
			</div>
			
	<script type="text/javascript">
	
			function checkObjects() {
					var checkedString = '',checkedStringG='';
					$("input[type=checkbox]").each(function(){
						if($(this).hasClass('myCheckboxCart')){
							if(this.checked) checkedString += $(this).val()+',';
						}
						if($(this).hasClass('myCheckboxGift')){
							if(this.checked) checkedStringG += $(this).val()+',';
						}
					});
					checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
					checkedStringG = checkedStringG.substr(0,checkedStringG.lastIndexOf(','));
					$('#checked-cart').val(checkedString+"/"+checkedStringG);
				}
				
				function checkAlls() {
					var checked_status = $("input[name=all]:checked").length;
					var checkedString = '',checkedStringG = '';
					$("input[type=checkbox]").each(function(){
						if($(this).hasClass('myCheckboxCart')){
							this.checked = checked_status;
							if(checked_status) checkedString += $(this).val()+',';
						}
						if($(this).hasClass('myCheckboxGift')){
							this.checked = checked_status;
							if(checked_status) checkedStringG += $(this).val()+',';
						}
					});
					$("span[acaica=myCheckboxCart]").each(function(){
						if(checked_status)
							this.style.backgroundPosition = "0 -50px";
						else this.style.backgroundPosition = "0 0";
					});
					$("span[acaica=myCheckboxGift]").each(function(){
						if(checked_status)
							this.style.backgroundPosition = "0 -50px";
						else this.style.backgroundPosition = "0 0";
					});
					checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
					checkedStringG = checkedStringG.substr(0,checkedStringG.lastIndexOf(','));
					$('#checked-cart').val(checkedString+"/"+checkedStringG);
				}
				
				$("#success-objlist-bt").click(function(){
						checkObjects();
						if($('#checked-cart').val()=='') {
							jAlert(
								"{$vsLang->getWords('visible_obj_confirm_noitem', "Bạn chưa chọn mục nào!")}",
								"{$bw->vars['global_websitename']} Dialog"
							)
							return false;
						}
						vsf.get('{$bw->input[0]}/success-status/{$bw->input[2]}/'+$('#checked-cart').val(), 'orderView');
				})
				$("#pending-objlist-bt").click(function(){
						checkObjects();
						if($('#checked-cart').val()=='') {
							jAlert(
								"{$vsLang->getWords('visible_obj_confirm_noitem', "Bạn chưa chọn mục nào!")}",
								"{$bw->vars['global_websitename']} Dialog"
							)
							return false;
						}
						vsf.get('{$bw->input[0]}/pending-status/{$bw->input[2]}/'+$('#checked-cart').val(), 'orderView');
				})
		
		function orderPrint(itemId)
		{
			 mywindow = window.open ("{$bw->base_url}orders/printOrder/"+itemId+"/&ajax=1",  "mywindow","width=500,height=500,resizable=0,location=1,status=0");
			 mywindow.moveTo(50,50);
		}
	</script>
EOF;
	}
	
	function sendMail(){
		global $vsLang;			
			$BWHTML .= <<<EOF
				<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
							<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
					    	<span class="ui-dialog-title">{$vsLang->getWords('order_mailTo','Gửi Đến')}: <font color="red">{orderName}</font></span>
					    	<span class="ui-dialog-title">{$vsLang->getWords('order_email','Email')}: <font color="red">{orderEmail}</font></span>
					    </div>
					<form action="javascript:" method="post" id="formreply" name="form">
						<input type="hidden" name="email" value="{orderEmail}"/>
						<table cellpadding="0" cellspacing="0" class="ui-widget-content" style="border:none;">
							<tr>
								<td style="text-align:center; color: rgb(142, 81, 51); font-weight: bold;">
									{$vsLang->getWords('order_Anwser','Nội Dung')}:
								</td>
							</tr>
							<tr>
								<td>{EDITOR}</td>
							</tr>
							<tr  class="ui-dialog">
								<td align="center" class="ui-dialog-buttonpanel">
									<input type="submit" name="submit" value="{$vsLang->getWords('order_send','Gửi')}">
								</td>
							</tr>
						</table>
				</form>
			</div>
			<script type="text/javascript">
				$('#formreply').submit(function() {
					vs.submitForm($('#formreply'),'orders/sendMail/{orderId}','orderlist');
					$('#orderitem').html('');
					return false;
				});	
			</script>
EOF;
			return $BWHTML;
	}
	
}
?>