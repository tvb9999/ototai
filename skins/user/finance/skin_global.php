<?php

class skin_global {
	
	function addCSS($cssUrl="") {
		$BWHTML .= <<<EOF
			<link type="text/css" rel="stylesheet" href="{$cssUrl}.css" />
EOF;
		return $BWHTML;
	}
	
	function importantAjaxCallBack(){
		global $bw,$vsLang;
		$BWHTML .= <<<EOF
EOF;
		return $BWHTML;
	}

	function addJavaScriptFile($file="",$type='file') {
		global $bw;
		
		$BWHTML .= <<<EOF
		    <if="$type=='cur_file'">
				<script type="text/javascript" src='{$bw->vars['cur_scripts']}/{$file}.js'></script>
				<else />
				<if="$type=='external'">
					<script type="text/javascript" src='{$file}'></script>
					<else />
					<if="$type=='file'">
						<script type="text/javascript" src='{$bw->vars['board_url']}/javascripts/{$file}.js'></script>
					</if>
				</if>
			</if>
EOF;
		return $BWHTML;
	}

	function addJavaScript($script="") {
		$BWHTML = "";
		$BWHTML .= <<<EOF
<script language="javascript" type="text/javascript">
		{$script}
</script>
EOF;
		return $BWHTML;
	}

	function addDropDownScript($id="") {
		$BWHTML = "";
		//--starthtml--//

		$BWHTML .= <<<EOF
ddsmoothmenu.init({
	mainmenuid: "{$id}", //Menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menus outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup", //"markup" or ["container_id", "path_to_menu_file"]
})
EOF;

		//--endhtml--//
		return $BWHTML;
	}

	function PermissionDenied($error="") {
		$BWHTML .= <<<EOF
<div class="red">
		{$error}</div>
EOF;
		return $BWHTML;
	}

	function displayFatalError($message="",$line="",$file="", $trace="") {
		$BWHTML .= <<<EOF
		<div class="vs-common">
			<div class="red" align="left" style="padding: 20px">
				Error: {$message}<br />
				Line: {$line}<br />
				File: {$file}<br />
				Trace: <pre>{$trace}</pre><br />
			</div>
		</div>
EOF;
		return $BWHTML;
	}
	
	function global_main_title() {
		global $bw, $vsPrint;	
			
		$BWHTML .= <<<EOF
			<span class="{$bw->input['module']}">{$vsPrint->mainTitle}</span>
EOF;
		return $BWHTML;
	}

	function vs_global(){
		global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
		$this->bw = $bw;
		$this->vsTemplate = $vsTemplate;
		$BWHTML .= <<<EOF
			<center>
				<div class="header">
					<!--
					<div class="logo">
						<img src="{$bw->vars['img_url']}/logo.jpg" />
					</div>
					-->
					<div class="eng_vn_all">
						<div class="eng_vn">
							<a href="{$bw->vars['board_url']}/en/"><img src="{$bw->vars['img_url']}/eng.jpg" /></a></div>
						<div class="eng_vn">
							<a href="{$bw->vars['board_url']}/vi/"><img src="{$bw->vars['img_url']}/vn.jpg" /></a></div>
					</div>
				</div>
				<!--
				<div class="banner">
					<embed src="{$bw->vars['img_url']}/banner.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" height="205" width="999">
				</div>
				-->
				<div class="maincontent">
					<div class="giohang">
						<div class="giohang_vnd">
							<span class="order_adder"><if="$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['count'] != 0">
								{$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['count']}
							<else />
								0 
							</if></span>{$vsLang->getWordsGlobal("global_num_pro_cart2","sản phẩm")}<br />
						
							<if="$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total'] != 0">
								{$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total']}
							<else />
								0 
							</if> {$vsLang->getWords("products_money","VNĐ","products")}<br />
							<a style="font-size:11px" href="{$bw->vars['board_url']}/orders" >&raquo {$vsLang->getWordsGlobal("global_num_pro_viewcart","Xem giỏ hàng")}</a>
						</div>
					</div>
					<div class="menuall">
								<ul>
									<if="$vsTemplate->global_template->menu">
										<foreach="$vsTemplate->global_template->menu as $value">
											<if="$value->top">
												<if="$value->getUrl() == 'helpbuy/'">
													<li><a href="#">{$value->getTitle()}</a>
														<ul>
															<if="$this->vsTemplate->global_template->help">
																<foreach="$this->vsTemplate->global_template->help as $item">
					                                    			<li><a class="{$value->getClassActive()}" href="{$item->getUrl('helpbuy')}">{$item->getTitle()}</a></li>
					                                    		</foreach>
					                                    	</if>
					                                    </ul>
													</li>
												<else />
													<li><a class="{$value->getClassActive()}" href="{$this->bw->base_url}{$value->getUrl()}">{$value->getTitle()}</a></li>
												</if>
											</if>
										</foreach>
									</if>
								</ul>
							<form id="formsearch" class="search_box" method="POST" action="{$bw->base_url}products/search/">								
							<div class="searchall">
									<div class="searchall_in">
										<input name="productName" type="text" id="keyword" onclick="if(this.value=='{$vsLang->getWordsGlobal("global_key_search","Nhập từ khóa")}!') this.value=''" onblur="if(this.value=='') this.value='{$vsLang->getWordsGlobal("global_key_search","Nhập từ khóa")}!'" value="{$vsLang->getWordsGlobal("global_key_search","Nhập từ khóa")}!"/>
									</div>
									<div class="seatch_mt">
										<a onclick="$('#formsearch').submit();" href="javascript:void(null)">{$vsLang->getWordsGlobal("global_title_search","Tìm kiếm")}</a>
									</div>
								</div>
							</form>
							<script>
								$("#formsearch").submit(function(){
									if((!$("#keyword").val()) || ($("#keyword").val() == 'Nhập từ khóa!')){
										jAlert('Vui lòng nhập sản phẩm tìm kiếm','{$bw->vars['global_websitename']} Dialog');
										return false;
									}
									return true;
								});
							</script>
						</div>
					<div class="content">
						<div class="contleft">
							<div class="left_dn">
								<if="!$_SESSION [APPLICATION_TYPE] ['obj']['userId']">
									<div class="bg_title1">{$vsLang->getWordsGlobal("global_login","Đăng nhập")}</div>
									<form method="post" action="{$bw->base_url}users/login-process/" id="form-login-home">
										<div class="box_dn">
											<div class="dnmk">
												<input name="userName" id="userNameHome" onblur="if(this.value=='') this.value='{$vsLang->getWordsGlobal("global_username","Tên đăng nhập")}'" onclick="if(this.value=='{$vsLang->getWordsGlobal("global_username","Tên đăng nhập")}') this.value=''" type="text" value="{$vsLang->getWordsGlobal("global_username","Tên đăng nhập")}" /></div>
											<div class="dnmk">
												<input name="userPassword" id="userPasswordHome" onblur="if(this.value=='') this.value='{$vsLang->getWordsGlobal("global_password","Mật khẩu")}'" onclick="if(this.value=='{$vsLang->getWordsGlobal("global_password","Mật khẩu")}') this.value=''" type="password" value="{$vsLang->getWordsGlobal("global_password","Mật khẩu")}" /></div>
											<div class="btn_dn">
												<a href="javascript:void(null);" onclick="$('#form-login-home').submit();">&nbsp;</a></div>
											<div class="text_dnmk">
												<a href="{$bw->base_url}users/resgister/">{$vsLang->getWordsGlobal("global_register","Đăng ký")}</a> | <a href="{$bw->base_url}users/forgot-password-form/">{$vsLang->getWordsGlobal("global_forgot_password","Quên mật khẩu")}</a></div>
										</div>
									</form>
									<script>
										$('#form-login-home').submit(function(){
											if($("#userNameHome").val() == ""){
												jAlert('{$vsLang->getWords('err_user_name','Vui lòng nhập tên đăng nhập hợp lệ')}','{$bw->vars['global_websitename']} Dialog');					
												$('#userNameHome').focus();	
												return false;				
											}
											if($("#userPasswordHome").val() == ""){
												jAlert('{$vsLang->getWords('err_user_pass','Vui lòng nhập mật khẩu')}','{$bw->vars['global_websitename']} Dialog');					
												$('#userPasswordHome').focus();
												return false;					
											}
										});
									</script>
									<else />
									
									<div>Xin chào : <a href="{$bw->base_url}users/user-infor-form/">{$_SESSION [APPLICATION_TYPE] ['obj']['userName']}</a></div>
									<div><a href="{$bw->base_url}users/user-infor-form/">Quản lý thông tin thành viên</a></div>
									<div><a href="{$bw->base_url}users/logout-process/">Đăng xuất</a></div>
								</if>
							</div>
							<div class="left_dmsp">
								<div class="bg_title2">{$vsLang->getWordsGlobal("global_category","danh mục sản phẩm")}</div>
								<div class="dmspmenu" id="dmspmenu2">
								<ul>
								<if="$vsTemplate->global_template->category">
									<foreach="$vsTemplate->global_template->category as $item">
										<if="$item->getChildren()">
											<li class="silverheader"><a href="{$bw->base_url}products/category/{$item->getId()}/">{$item->getTitle()}</a>
												<ul class="submenu">
													<foreach="$item->getChildren() as $value">
														<li><a href="{$this->bw->base_url}products/category/{$value->getId()}/">{$value->getTitle()}</a></li>
													</foreach>
												</ul>
											</li>
										<else />
											<li class="silverheader"><a href="{$bw->base_url}products/category/{$item->getId()}/">{$item->getTitle()}</a></li>
										</if>
									</foreach>
								</if>
								</ul>
								</div>
							</div>
							<div class="left_htr">
								<div class="bg_title2">{$vsLang->getWordsGlobal("global_support_online","hỗ trợ trực tuyến")}</div>
								<div class="box_htr" style="padding-bottom:13px">
									<div class="yahoo">
										<if="$vsTemplate->global_template->support">
											<div class="yh_text">{$vsLang->getWordsGlobal("global_support_yahoo","Yahoo")}:</div>
											<foreach="$vsTemplate->global_template->support as $value">
												<if="$value->getType() == 1"> 
													<div class="yh_img">
														{$value->showYahoo()}
													</div>
												</if>
											</foreach>
										</if>
									</div>
									<div class="clear"></div>
									<div class="yahoo" style="margin-top:0px">
										<if="$vsTemplate->global_template->support">
											<div class="yh_text" >{$vsLang->getWordsGlobal("global_support_skype","Skype")}:</div>
											<foreach="$vsTemplate->global_template->support as $value">
												<if="$value->getType() == 2"> 
													<div class="yh_img">
														{$value->showSkype()}
													</div>
												</if>
											</foreach>
										</if>
									</div>
									<div class="clear"></div>
									<div class="fone">{$vsLang->getWordsGlobal("global_hotline","Hotline")}:</div>
									<div class="sdt">{$vsLang->getWordsGlobal("global_support_phone","0938 399 987")}</div>
									<div class="sdt">{$vsLang->getWordsGlobal("global_support_phone2","0935 379 998")}</div>
									<div class="email">EMAIL:</div>
									<div class="sdt" style="font-size:11px;padding-left:15px">{$vsLang->getWordsGlobal("global_support_email","uyenthao@yahoo.com")}</div>
								</div>
							</div>
							<div class="left_htr">
								<div class="bg_title2">{$vsLang->getWordsGlobal("global_counter","Thống kê")}</div>
								<div class="box_htr">
									<div class="counter">
										<div>{$vsLang->getWordsGlobal("global_visit","Lượt truy cập")}: <span class="text_blue">{$this->state['visits']}</span></div>
										<div>{$vsLang->getWordsGlobal("global_online","Số người online")}: <span class="text_blue">{$this->state['today']}</span></div>
									</div>
								</div>
							</div>
							<div class="left_htr">
								<!--<div class="bg_title2">{$vsLang->getWordsGlobal("global_advertisement","Quảng cáo")}</div>-->
								<div class="box_htr">
									<if="$vsTemplate->global_template->partner">
										<foreach="$vsTemplate->global_template->partner as $value">
											<if="$value->getPosition()==2">
												<div class="qc4">
													<a href="{$value->getWebsite()}" target="_blank">{$vsFile->arrayFiles[$value->getFileId()]->show(182,0)}</a><br />
												</div>
											</if>
										</foreach>
									</if>
								</div>
							</div>
						</div>
						<!--contleft-->
						<div class="contcent">
							{$this->SITE_MAIN_CONTENT}
						</div>
						<!--contcent-->
						<div class="contright">
							<div class="left_dn">
								<div class="bg_title1">{$vsLang->getWordsGlobal("global_hot_news","tin nổi bật")}</div>
								<div class="tnb">
									<ul>
										<if="$vsTemplate->global_template->hotNews">
											<foreach="$vsTemplate->global_template->hotNews as $value">
												<li><a href="{$value->getUrl('news')}">{$value->getTitle()}  </a></li> <!--({$value->getPostDate('SHORT')}) -->
											</foreach>
										</if>
									</ul>
								</div>
							</div>
							<div class="left_htr">
								<div class="bg_title2">{$vsLang->getWordsGlobal("global_bestselling_pro","sản phẩm bán chạy")}</div>
								<div class="box_htr">
									<div class="slide_banchay">
									<ul id="marquee" class="">
									<if="$vsTemplate->global_template->bestpro">
										<foreach="$vsTemplate->global_template->bestpro as $value">
											<li class="sp_banchay">
												<div style="padding:5px 0;"><a href="{$value->getUrl('products')}" class="sp_banchay_title">{$value->getTitle()}</a></div>
												<div><a href="{$value->getUrl('products')}"><img src="{$value->getCacheImagePathByFile($value->getImage(),169,169)}" /></a></div>
												<div class="sp_sub_text">
													{$vsLang->getWords("products_code","Mã hàng","products")}: <span class="sp_sub_msp">X1020</span><br />													
													<if="$value->getPrice()">									
														{$vsLang->getWords("products_price","Giá","products")}: <span class="sp_sub_msp">{$value->getPrice(true)}</span> {$vsLang->getWords("products_money","VNĐ","products")}
													<else />
														{$vsLang->getWords("products_call","Call","products")}
													</if> 
												</div>
												<div class="btn_all" style="padding-left:25px;">
													<if="$value->getPrice()">
													<div class="btn_product_detail"><a href="{$value->getUrl('products')}">{$vsLang->getWordsGlobal("global_product_detail","Chi tiết")}</a></div>
														<div class="btn_dathang"><a href="javascript:vsf.get('orders/addtocart/{$value->getId()}','addcart');sLoading();">{$vsLang->getWordsGlobal("global_add_cart","Đặt hàng")}</a></div>
													</if>													
												</div>
											</li>
										</foreach>
									</if>
									</ul>
									</div>
									<div id="addcart"></div>
								</div>
							</div>
							<div class="left_htr">
								<!--<div class="bg_title2">{$vsLang->getWordsGlobal("global_partner","đối tác")}</div>-->
								<div class="box_htr">
									<if="$vsTemplate->global_template->partner">
										<foreach="$vsTemplate->global_template->partner as $value">
											<if="$value->getPosition()==3">
												<div class="qc4">
													<a href="{$value->getWebsite()}" target="_blank">{$vsFile->arrayFiles[$value->getFileId()]->show(182,0)}</a>
												</div>
											</if>
										</foreach>
									</if>
								</div>
							</div>
						</div>
						<!--contright--></div>
					<!--content-->
					<div class="clear"></div>
					<div class="line_footer"></div>
					<div class="footer">
						<div class="footer1">
							<div class="footer_menu">
								<ul>
									<if="$vsTemplate->global_template->menu">
										<foreach="$vsTemplate->global_template->menu as $value">
											<if="$value->bottom">
												<li><a href="{$this->bw->base_url}{$value->getUrl()}">{$value->getTitle()}</a></li>
											</if>
										</foreach>
									</if>
								</ul>
							</div>
							<form>
								<div class="weblink" id="linkwebsite">
									<if="$vsTemplate->global_template->link">
										<select name="linkwebsite">
											<option>{$vsLang->getWordsGlobal("global_link","Liên kết")}</option>
											<foreach="$vsTemplate->global_template->link as $value">
												<option>Weblink</option>
											</foreach>
										</select>
									</if>
								</div>
							</form>
						</div>
						<div class="clear"></div>
						<div class="footer2">
							<div class="text1_footer">
								<p>{$vsLang->getWordsGlobal("global_company_year2","© Copyright 2011")} {$vsLang->getWordsGlobal("global_company_site","eteam.vn")} , {$vsLang->getWordsGlobal("global_company_copyright","All right reserved")}</p>
								<p>Địa chỉ:<span class="add">{$vsLang->getWordsGlobal("global_company_add","31A ngõ 203 Kim Ngưu")}</span>
								Email:<span class="email">{$vsLang->getWordsGlobal("global_company_email2","luckymancvp@eteam.vn")}</span>
								Hotline:<span class="phone">{$vsLang->getWordsGlobal("global_company_hotline2","01683174154")}</span></p>
							</div>
							<div class="text2_footer">
								<a href="http://eteam.vn" class="redsunic"></a>
						</div>
					</div>
				</div>
				<!--maincontent--></center>
				<script>
					
					$(document).ready(function(){
						$(".menuall").find('a:first').addClass("firt");						   
						$(".footer_menu").find('li:first').addClass("end");
						$("#menu ul ul").find('li:last').addClass("end");
						$('.menuall li').mouseenter(function() {
					  		$(this).find('ul').css('visibility','visible')
						}).mouseleave(function() {
							$(this).find('ul').css('visibility','hidden')
						  });
						  $('.dmspmenu li').mouseenter(function() {
					  		$(this).find('ul').css('visibility','visible')
						}).mouseleave(function() {
							$(this).find('ul').css('visibility','hidden')
						  });
					})
					$(function() {
						/*if($(".slide_sub ul").length>0){
							$(".slide_sub").jCarouselLite({
								btnNext: ".slide_sub .next",
								btnPrev: ".slide_sub .prev",
								auto:3000,
								speed:3000
							});
						}*/
						$("#marquee").simplyScroll({
							autoMode: 'loop',
							horizontal: false,
							frameRate: 15,
							speed: 1,
							pauseOnHover: true
						});
					});
					
					$(function() {
					    var sidebar   = $(".giohang");
					    var offset    = sidebar.offset();
					    var topPadding = 15;
					    $(window).scroll(function() {
					        if ($(window).scrollTop() > offset.top) {
					            sidebar.stop().animate({
					                marginTop: $(window).scrollTop() - offset.top + topPadding
					            });
					        } else {
					            sidebar.stop().animate({
					                marginTop: 0
					            });
					        }
					    });
					});
					
					 DD_belatedPNG.fix('.giohang');
					
				</script>
								
EOF;
		return $BWHTML;

	}

	function pop_up_window($title="",$css="",$text="") {
		$IPBHTML = "";
		//--starthtml--//


		$IPBHTML .= <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml"> 
 <head> 
  <meta http-equiv="content-type" content="text/html; utf-8" /> 
  <title>$title</title>
  $css
 </head>
 <body>
 <div style='text-align:left'>
 $text
 </div>
 </body>
</html>
EOF;

 //--endhtml--//
 return $IPBHTML;
	}

	function Redirect($Text="",$Url="",$css="") {
		global $bw;
		$BWHTML = "";
		//--starthtml--//
		//
		$BWHTML .= <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html40/loose.dtd">
<html>
	<head>
	<title>Redirecting...</title>
	<meta http-equiv='refresh' content='2; url=$Url' />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	$css
	<style type="text/css">
		.title
		{
			color:red;
		}
		.text
		{
			padding:10px;
			color:#009F3C;
		}
	</style>
	</head>
  	<body >
		<center>
		<table style="background-color:#6ac3cb" cellpadding="0" cellspacing="0" width="100%" height="100%"> 
			<tr>
				<td width="416px" align="center" valign="middle" style="background:url({$bw->vars ['board_url']}/styles/redirect/direct.jpg) no-repeat center  top;" height="432px">
						<br/><br/><br/><br/>
					<img src="{$bw->vars ['board_url']}/styles/redirect/turtle.gif">
					<br/><br/>
					<p class="text">{$Text}</p>
				    <a href='$Url' title="{$Url}" class="title">( Click here if you do not wish to wait )</a>
				 </td>
			</tr>  
		</table> 
		</center>
	</body>
</html>
EOF;

	//--endhtml--//
	return $BWHTML;
	}

}

?>