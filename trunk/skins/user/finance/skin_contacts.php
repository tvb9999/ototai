<?php
class skin_contacts {

	function contactInfoFromPageContent($option=''){
		$BWHTML = <<<EOF
			<div class="estate-prev-title">
				{$option['Title']}
			</div>
			<div class='estate-detail'>
				{$option['pageContent']}
			</div>
EOF;
		return $BWHTML;
	}

	function contactForm() {
		global $vsLang, $bw;

		$BWHTML .= <<<EOF

			<form id="FormContact" method="POST" action="{$bw->base_url}contacts/send/" enctype='multipart/form-data'>
				<div class="contact_left">
				<div class="contact_row"><label for="Name" class="w_label"><span>{$vsLang->getWords('contact_full_name','Họ tên')}:</span></label>
				<input type="text" id="contactName" class="w_input" name="contactName" title="{$vsLang->getWords('contact_full_name','Họ tên')}"/> *
				<div class="clear"></div></div>
				<div class="contact_row"><label for="add" class="w_label"><span>{$vsLang->getWords('contact_address','Địa chỉ')}:</span></label>
				<input id="contactAddress" class="w_input" name="contactAddress" title="{$vsLang->getWords('contact_address','Địa chỉ')}"  type="text"/> *
				<div class="clear"></div></div>
				<div class="contact_row"><label for="phone" class="w_label"><span>{$vsLang->getWords('contact_phone','Điện thoại')}:</span></label>
				<input type="text" class="numeric w_input"  id="contactPhone" class="w_input" name="contactPhone" title="{$vsLang->getWords('contact_phone','Điện thoại')}" value=""/> *
				<div class="clear"></div></div>
				<div class="contact_row"><label for="Email" class="w_label"><span>{$vsLang->getWords('contact_email','Email')}:</span></label>
				<input type="text" id="contactEmail" class="w_input" name="contactEmail" title="{$vsLang->getWords('contact_email','Email')}"/> *
				<div class="clear"></div></div>
				<div class="contact_row"><label for="Name" class="w_label"><span>{$vsLang->getWords('contact_title','Tiêu đề')} :</span></label>
				<input type="text" id="contactTitle" class="w_input" name="contactTitle" title="{$vsLang->getWords('contact_title','Tiêu đề')}" /> *                        
				<div class="clear"></div></div>
				</div>
				<div class="contact_right"><div><label for="Name" class="w_label fix_ct"><span>{$vsLang->getWords('contact_message','Nội dung')} :</span></label></div>
				<textarea name="contactContent" id="contactContent" cols="90" rows="12" class="w_310 h_100"></textarea></div>
				<div class="clear"></div>                                     
				<div class="contact_btn"><input type="submit" name="submit" value="{$vsLang->getWords('contact_sends','Gửi')}" class="submit-button" style="width:60px;" />
				<input type="reset" name="reset" value="{$vsLang->getWords('Contacts_reset','Làm lại')}" class="submit-button"></div>                                    
			</form>
              
			<script type='text/javascript'>
				function checkMail(mail){
					var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
					if (!filter.test(mail)) return false;
					return true;
				}
				$('#FormContact').submit(function(){
					if(!$('#contactName').val()) {
						jAlert('{$vsLang->getWords('err_contact_name_blank','Vui lòng nhập họ tên!')}','{$bw->vars['global_websitename']} Dialog');
						return false;
					}				
					if(!$('#contactEmail').val()|| !checkMail($('#contactEmail').val())) {
						jAlert('{$vsLang->getWords('err_contact_email_blank','Nhập vào đúng loại email!')}','{$bw->vars['global_websitename']} Dialog');
						return false;
					}
					if(!$('#contactTitle').val()) {
						jAlert('{$vsLang->getWords('err_contact_title_blank','Nhập vào tiêu đề!')}','{$bw->vars['global_websitename']} Dialog');
						return false;
					}
					if($('#contactContent').val().length < 15 ) {
						jAlert('{$vsLang->getWords('err_contact_message_blank','Thông tin quá ngắn!')}','{$bw->vars['global_websitename']} Dialog');				
						return false;
					}
					return true;
				});
			</script>
EOF;
		return $BWHTML;
	}

	function thankyou($text, $url){
		global $vsLang,$bw;
		
		$BWHTML = <<<EOF
				<script type='text/javascript'>
					setTimeout('delayer()', 3000);
					function delayer(){
	    				window.location = "{$bw->base_url}contacts";
					}
				</script>
				<div class="lienhe_1">
	            	<div class="floadleft"><img src="{$bw->vars['img_url']}/lienhe_left2.jpg"></div>
	                <div class="lienhe_center_2">
						<div class="redirectpage" style="text-align:center;">
							<h1>{$text}</h1>
							<h3 style="font-family:Arial,Helvetica,sans-serif;">{$vsLang->getWords('contact_thankyou_redir','chuyển trang...')}</h3>
					    	<a style="width:500px;height:50px;" href='{$bw->base_url}{$url}'>(Click vào đây nếu không muốn chờ lâu )</a>
					    </div>
				    </div>
	                <div class="floadleft"><img src="{$bw->vars['img_url']}/lienhe_right2.jpg"></div>
           		</div>
EOF;
		return $BWHTML;
	}

	function generalView($option= array()){
		global $bw, $vsLang;
		
		$BWHTML = <<<EOF
				
				<div class="product_block">
						<div class="new_pro">
							<div class="new_pro_top">
								<span>{$option['page']->getTitle()}</span>
							</div>
						</div><!-- end new_pro -->
                        <div class="pro_block_wrapper">
                            <div class="content">
                            		{$option['page']->getContent()}
                            	</div>
                            <div class="clear"></div>
                    		<div class="new_pro_top">
								<span>{$vsLang->getWords('contactviaemail','Liên hệ qua Email!')}</span>
							</div>
                            <div class="cart_confirm" style="padding:0 20px 0 10px;">
                              	<div id="contact-area" style="padding:10px 0px;">
                                	  	{$this->contactForm()}                          
                              	</div>                            
                    		</div>
                    		<div class="clear"></div>
                    		<div class="new_pro_top">
								<span>{$vsLang->getWords('ggmap','Bản đồ chỉ dẫn!')}</span>
							</div>
                    		<div id="map_canvas" style="width:576px;height:450px;margin-top:10px;"></div>
							<script type="text/javascript"  src="http://maps.google.com/maps/api/js?sensor=true&language=vi"></script> 
				<script  type="text/javascript"> 
					    function init() {
					    	<if="$option['page']">
								var myHtml = "<h3>{$option['page']->getTitle()}</h3><p>{$bw->vars['company_address']}</p>";
							<else />
								var myHtml = "<h3>{$bw->vars['company_name']}</h3><p>{$bw->vars['company_address']}</p>";
							</if>					
					      	var map = new google.maps.Map(
					      					document.getElementById("map_canvas"),
					      					{scaleControl: true}
					      				);
					      	map.setCenter(new google.maps.LatLng({$option['Latitude']},{$option['Longitude']}));
					      	map.setZoom(15);
					      	map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
		
					      	var marker = new google.maps.Marker({
					      						map: map, 
					      						position:map.getCenter()
											});
					       
							var infowindow = new google.maps.InfoWindow({
												'pixelOffset': new google.maps.Size(0,15)
											});
					      	infowindow.setContent(myHtml);
					      	infowindow.open(map, marker);
					    }
				    	$(document).ready(function(){
							init();
						});
					
				</script>
                            <br clear="all" />
                        </div>
                        <div class="pro_block_bottom"></div>
                    </div>    
                <br clear="all" />                
EOF;
			return $BWHTML;
	}

	function loadRequireJavascript(){
		global $vsLang, $bw;
		$BWHTML = <<<EOF
			<script type='text/javascript'>
				function refreshIdentifyCode(){
					fontend.get('contacts/refreshIdentifyCode/','IdentifyCode');
					return true;
				}
					
				function reloadContactInformation(json){
					jAlert(
						'{$vsLang->getWords('contact_Err_IdentifyCode','Số chứng thực không đúng!')}',
						'{$vsLang->getWords('user_signUp_Alert','Cảnh báo')}'
					);
					$('#contactName').val(json['contactName']);
					$('#contactAddress').val(json['contactAddress']);
					$('#contactPhone').val(json['contactPhone']);
					$('#contactEmail').val(json['contactEmail']);		
					$('#contactTitle').val(json['contactTitle']);
					$('#contactMessage').val(json['contactMessage']);
				}
					
				fontend = {
					get:function(act, id) {
						var params = {	
							ajax		:	1,
							vs			: 	act,
							identifyId 	:	document.getElementById('identifyCode').name
						};
						$.get(ajaxfile,params,function(data){
							document.getElementById('identifyCode').name = data;
							document.getElementById('identifyCode').src = '{$bw->base_url}contacts/createIdentifyCodeImage/'+data;
						});
					},
					
				submitForm:function(obj,act,id) {
						var params = {
							vs:act,
							ajax: 1
						};
						
						var count = 0;
						obj
						.find("input[type='radio']:checked, input[checked], input[type='text'], input[type='hidden'], input[type='password'], input[type='submit'], option[selected], textarea")
						.each(function() {
							params[ this.name || this.id || this.parentNode.name || this.parentNode.id ] = this.value;
						});
						$.post(ajaxfile,params,function(data){
							$("#"+id).html(data);
						});
					}
				}
			</script>
EOF;
		return $BWHTML;
	}
}
?>