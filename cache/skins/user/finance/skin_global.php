<?php
class skin_global{

//===========================================================================
// <rsf:addCSS:desc::trigger:>
//===========================================================================
function addCSS($cssUrl="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<link type="text/css" rel="stylesheet" href="{$cssUrl}.css" />
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:importantAjaxCallBack:desc::trigger:>
//===========================================================================
function importantAjaxCallBack() {global $bw,$vsLang;

//--starthtml--//
$BWHTML .= <<<EOF
		
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:addJavaScriptFile:desc::trigger:>
//===========================================================================
function addJavaScriptFile($file="",$type='file') {global $bw;


//--starthtml--//
$BWHTML .= <<<EOF
		
EOF;
if($type=='cur_file') {
$BWHTML .= <<<EOF

<script type="text/javascript" src='{$bw->vars['cur_scripts']}/{$file}.js'></script>

EOF;
}

else {
$BWHTML .= <<<EOF


EOF;
if($type=='external') {
$BWHTML .= <<<EOF

<script type="text/javascript" src='{$file}'></script>

EOF;
}

else {
$BWHTML .= <<<EOF


EOF;
if($type=='file') {
$BWHTML .= <<<EOF

<script type="text/javascript" src='{$bw->vars['board_url']}/javascripts/{$file}.js'></script>

EOF;
}

$BWHTML .= <<<EOF


EOF;
}
$BWHTML .= <<<EOF


EOF;
}
$BWHTML .= <<<EOF

EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:addJavaScript:desc::trigger:>
//===========================================================================
function addJavaScript($script="") {$BWHTML = "";

//--starthtml--//
$BWHTML .= <<<EOF
		<script language="javascript" type="text/javascript">
{$script}
</script>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:addDropDownScript:desc::trigger:>
//===========================================================================
function addDropDownScript($id="") {$BWHTML = "";
//--starthtml--//


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
//===========================================================================
// <rsf:PermissionDenied:desc::trigger:>
//===========================================================================
function PermissionDenied($error="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<div class="red">
{$error}</div>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:displayFatalError:desc::trigger:>
//===========================================================================
function displayFatalError($message="",$line="",$file="",$trace="") {
//--starthtml--//
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
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:global_main_title:desc::trigger:>
//===========================================================================
function global_main_title() {global $bw, $vsPrint;


//--starthtml--//
$BWHTML .= <<<EOF
		<span class="{$bw->input['module']}">{$vsPrint->mainTitle}</span>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:vs_global:desc::trigger:>
//===========================================================================
function vs_global() {global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
$this->bw = $bw;
$this->vsTemplate = $vsTemplate;

//--starthtml--//
$BWHTML .= <<<EOF
		<center>
<div class="header">
<div class="logo">
<img src="{$bw->vars['img_url']}/logo.jpg" />
</div>
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
<span class="order_adder">
EOF;
if($_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['count'] != 0) {
$BWHTML .= <<<EOF

{$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['count']}

EOF;
}

else {
$BWHTML .= <<<EOF

0 

EOF;
}
$BWHTML .= <<<EOF
</span>{$vsLang->getWordsGlobal("global_num_pro_cart2","sản phẩm")}<br />


EOF;
if($_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total'] != 0) {
$BWHTML .= <<<EOF

{$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total']}

EOF;
}

else {
$BWHTML .= <<<EOF

0 

EOF;
}
$BWHTML .= <<<EOF
 {$vsLang->getWords("products_money","VNĐ","products")}<br />
<a style="font-size:11px" href="{$bw->vars['board_url']}/orders" >&raquo {$vsLang->getWordsGlobal("global_num_pro_viewcart","Xem giỏ hàng")}</a>
</div>
</div>
<div class="menuall">
<ul>

EOF;
if($vsTemplate->global_template->menu) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e77370cc0149()}

EOF;
}

$BWHTML .= <<<EOF

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

EOF;
if(!$_SESSION [APPLICATION_TYPE] ['obj']['userId']) {
$BWHTML .= <<<EOF

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

EOF;
}

else {
$BWHTML .= <<<EOF


<div>Xin chào : <a href="{$bw->base_url}users/user-infor-form/">{$_SESSION [APPLICATION_TYPE] ['obj']['userName']}</a></div>
<div><a href="{$bw->base_url}users/user-infor-form/">Quản lý thông tin thành viên</a></div>
<div><a href="{$bw->base_url}users/logout-process/">Đăng xuất</a></div>

EOF;
}
$BWHTML .= <<<EOF

</div>
<div class="left_dmsp">
<div class="bg_title2">{$vsLang->getWordsGlobal("global_category","danh mục sản phẩm")}</div>
<div class="dmspmenu" id="dmspmenu2">
<ul>

EOF;
if($vsTemplate->global_template->category) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e77370cc0548()}

EOF;
}

$BWHTML .= <<<EOF

</ul>
</div>
</div>
<div class="left_htr">
<div class="bg_title2">{$vsLang->getWordsGlobal("global_support_online","hỗ trợ trực tuyến")}</div>
<div class="box_htr" style="padding-bottom:13px">
<div class="yahoo">

EOF;
if($vsTemplate->global_template->support) {
$BWHTML .= <<<EOF

<div class="yh_text">{$vsLang->getWordsGlobal("global_support_yahoo","Yahoo")}:</div>
{$this->__foreach_loop__id_4e77370cc06c9()}

EOF;
}

$BWHTML .= <<<EOF

</div>
<div class="clear"></div>
<div class="yahoo" style="margin-top:0px">

EOF;
if($vsTemplate->global_template->support) {
$BWHTML .= <<<EOF

<div class="yh_text" >{$vsLang->getWordsGlobal("global_support_skype","Skype")}:</div>
{$this->__foreach_loop__id_4e77370cc079d()}

EOF;
}

$BWHTML .= <<<EOF

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

EOF;
if($vsTemplate->global_template->partner) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e77370cc0860()}

EOF;
}

$BWHTML .= <<<EOF

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

EOF;
if($vsTemplate->global_template->hotNews) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e77370cc0921()}

EOF;
}

$BWHTML .= <<<EOF

</ul>
</div>
</div>
<div class="left_htr">
<div class="bg_title2">{$vsLang->getWordsGlobal("global_bestselling_pro","sản phẩm bán chạy")}</div>
<div class="box_htr">
<div class="slide_banchay">
<ul id="marquee" class="">

EOF;
if($vsTemplate->global_template->bestpro) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e77370cc09b6()}

EOF;
}

$BWHTML .= <<<EOF

</ul>
</div>
<div id="addcart"></div>
</div>
</div>
<div class="left_htr">
<!--<div class="bg_title2">{$vsLang->getWordsGlobal("global_partner","đối tác")}</div>-->
<div class="box_htr">

EOF;
if($vsTemplate->global_template->partner) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e77370cc10da()}

EOF;
}

$BWHTML .= <<<EOF

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

EOF;
if($vsTemplate->global_template->menu) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e77370cc1269()}

EOF;
}

$BWHTML .= <<<EOF

</ul>
</div>
<form>
<div class="weblink" id="linkwebsite">

EOF;
if($vsTemplate->global_template->link) {
$BWHTML .= <<<EOF

<select name="linkwebsite">
<option>{$vsLang->getWordsGlobal("global_link","Liên kết")}</option>
{$this->__foreach_loop__id_4e77370cc1344()}
</select>

EOF;
}

$BWHTML .= <<<EOF

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
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc00e9($value='')
{
;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $this->vsTemplate->global_template->help as $item )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
                                    <li><a class="{$value->getClassActive()}" href="{$item->getUrl('helpbuy')}">{$item->getTitle()}</a></li>
                                    
EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc0149()
{
global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->menu as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

EOF;
if($value->top) {
$BWHTML .= <<<EOF


EOF;
if($value->getUrl() == 'helpbuy/') {
$BWHTML .= <<<EOF

<li><a href="#">{$value->getTitle()}</a>
<ul>

EOF;
if($this->vsTemplate->global_template->help) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e77370cc00e9($value)}
                                    
EOF;
}

$BWHTML .= <<<EOF

                                    </ul>
</li>

EOF;
}

else {
$BWHTML .= <<<EOF

<li><a class="{$value->getClassActive()}" href="{$this->bw->base_url}{$value->getUrl()}">{$value->getTitle()}</a></li>

EOF;
}
$BWHTML .= <<<EOF


EOF;
}

$BWHTML .= <<<EOF


EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc04f2($item='')
{
;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $item->getChildren() as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<li><a href="{$this->bw->base_url}products/category/{$value->getId()}/">{$value->getTitle()}</a></li>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc0548()
{
global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->category as $item )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

EOF;
if($item->getChildren()) {
$BWHTML .= <<<EOF

<li class="silverheader"><a href="{$bw->base_url}products/category/{$item->getId()}/">{$item->getTitle()}</a>
<ul class="submenu">
{$this->__foreach_loop__id_4e77370cc04f2($item)}
</ul>
</li>

EOF;
}

else {
$BWHTML .= <<<EOF

<li class="silverheader"><a href="{$bw->base_url}products/category/{$item->getId()}/">{$item->getTitle()}</a></li>

EOF;
}
$BWHTML .= <<<EOF


EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc06c9()
{
global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->support as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

EOF;
if($value->getType() == 1) {
$BWHTML .= <<<EOF
 
<div class="yh_img">
{$value->showYahoo()}
</div>

EOF;
}

$BWHTML .= <<<EOF


EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc079d()
{
global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->support as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

EOF;
if($value->getType() == 2) {
$BWHTML .= <<<EOF
 
<div class="yh_img">
{$value->showSkype()}
</div>

EOF;
}

$BWHTML .= <<<EOF


EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc0860()
{
global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->partner as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

EOF;
if($value->getPosition()==2) {
$BWHTML .= <<<EOF

<div class="qc4">
<a href="{$value->getWebsite()}" target="_blank">{$vsFile->arrayFiles[$value->getFileId()]->show(182,0)}</a><br />
</div>

EOF;
}

$BWHTML .= <<<EOF


EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc0921()
{
global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->hotNews as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<li><a href="{$value->getUrl('news')}">{$value->getTitle()}  </a></li> <!--({$value->getPostDate('SHORT')}) -->

EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc09b6()
{
global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->bestpro as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<li class="sp_banchay">
<div style="padding:5px 0;"><a href="{$value->getUrl('products')}" class="sp_banchay_title">{$value->getTitle()}</a></div>
<div><a href="{$value->getUrl('products')}"><img src="{$value->getCacheImagePathByFile($value->getImage(),169,169)}" /></a></div>
<div class="sp_sub_text">
{$vsLang->getWords("products_code","Mã hàng","products")}: <span class="sp_sub_msp">X1020</span><br />

EOF;
if($value->getPrice()) {
$BWHTML .= <<<EOF

{$vsLang->getWords("products_price","Giá","products")}: <span class="sp_sub_msp">{$value->getPrice(true)}</span> {$vsLang->getWords("products_money","VNĐ","products")}

EOF;
}

else {
$BWHTML .= <<<EOF

{$vsLang->getWords("products_call","Call","products")}

EOF;
}
$BWHTML .= <<<EOF
 
</div>
<div class="btn_all" style="padding-left:25px;">

EOF;
if($value->getPrice()) {
$BWHTML .= <<<EOF

<div class="btn_product_detail"><a href="{$value->getUrl('products')}">{$vsLang->getWordsGlobal("global_product_detail","Chi tiết")}</a></div>
<div class="btn_dathang"><a href="javascript:vsf.get('orders/addtocart/{$value->getId()}','addcart');sLoading();">{$vsLang->getWordsGlobal("global_add_cart","Đặt hàng")}</a></div>

EOF;
}

$BWHTML .= <<<EOF

</div>
</li>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc10da()
{
global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->partner as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

EOF;
if($value->getPosition()==3) {
$BWHTML .= <<<EOF

<div class="qc4">
<a href="{$value->getWebsite()}" target="_blank">{$vsFile->arrayFiles[$value->getFileId()]->show(182,0)}</a>
</div>

EOF;
}

$BWHTML .= <<<EOF


EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc1269()
{
global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->menu as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

EOF;
if($value->bottom) {
$BWHTML .= <<<EOF

<li><a href="{$this->bw->base_url}{$value->getUrl()}">{$value->getTitle()}</a></li>

EOF;
}

$BWHTML .= <<<EOF


EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e77370cc1344()
{
global $bw, $vsLang, $vsMenu, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->link as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<option>Weblink</option>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:pop_up_window:desc::trigger:>
//===========================================================================
function pop_up_window($title="",$css="",$text="") {
//--starthtml--//
$BWHTML .= <<<EOF
		
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:Redirect:desc::trigger:>
//===========================================================================
function Redirect($Text="",$Url="",$css="") {global $bw;
$BWHTML = "";
//--starthtml--//
//

//--starthtml--//
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


}?>