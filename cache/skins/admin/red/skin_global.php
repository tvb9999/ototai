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
// <rsf:addJavaScriptFile:desc::trigger:>
//===========================================================================
function addJavaScriptFile($file="") {global $bw;
$BWHTML = "";

//--starthtml--//
$BWHTML .= <<<EOF
		<script type="text/javascript" src='{$bw->vars['board_url']}/javascripts/{$file}.js'></script>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:addJavaScript:desc::trigger:>
//===========================================================================
function addJavaScript($script="") {
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
// <rsf:SelectOption:desc::trigger:>
//===========================================================================
function SelectOption($options=array()) {
//--starthtml--//
$BWHTML .= <<<EOF
		<option value="{$options['value']}" {$options['selected']}>{$options['name']}</option>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:Select:desc::trigger:>
//===========================================================================
function Select($options=array()) {
//--starthtml--//
$BWHTML .= <<<EOF
		<select name="{$options['name']}" id="{$options['name']}"{$options['properties']}>
<!--OPTION LIST-->
</select>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:addDropDownScript:desc::trigger:>
//===========================================================================
function addDropDownScript($id="") {
//--starthtml--//
$BWHTML .= <<<EOF
		
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
		<div class="red" align="left" style="padding: 20px">
Error: {$message}<br />
Line: {$line}<br />
File: {$file}<br />
Trace: <pre>{$trace}</pre><br />
</div>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:global_main_title:desc::trigger:>
//===========================================================================
function global_main_title() {global $vsPrint;

//--starthtml--//
$BWHTML .= <<<EOF
		{$vsPrint->mainTitle}
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:vs_global:desc::trigger:>
//===========================================================================
function vs_global() {global $bw, $vsUser, $vsLang;

$BWHTML = "";

//--starthtml--//
$BWHTML .= <<<EOF
		
EOF;
if( !$vsUser->obj->getId() ) {
$BWHTML .= <<<EOF

{$this->SITE_MAIN_CONTENT}

EOF;
}

else {
$BWHTML .= <<<EOF

<center>
<a target="_blank" href="{$bw->vars ['board_url']}" class="buttom_back_cd" title="{$bw->vars ['global_websitename']}">Trở lại trang chủ</a>
<a target="_blank" href="http://www.redsunic.com" style="right:0px;left:auto;padding-left:25px;" class="buttom_back_cd logo_cd" title="{$vsLang->getWords('global_redsunic_full','Công ty Cổ phần Quốc Tế Mặt Trời Đỏ')}">
{$vsLang->getWordsGlobal('global_version', 'RS FRAMEWORK 4.0')}
</a>
<div id="vsf-wrapper-container">
<div id="vsf-wrapper" align="center">
<!-- BEGIN OF HEADER -->
<div class="vsf-header">
<div class="header_vs_ceedos">
<span>Admin Control Panel</span>
</div>
    <div class="clear"></div>
<!-- BEGIN OF TOP MENU -->
<div class="vsf-topmenu" id="topmenu">
<ul>

EOF;
if( $this->ADMIN_TOP_MENU ) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e773d684c7ca()}

EOF;
}

$BWHTML .= <<<EOF

</ul>
<div class="clear"></div></div>
<!-- END OF TOP MENU -->
    <div class="clear"></div>
</div>
<!-- END OF HEADER -->

<!-- BEGIN OF SITE NAV -->
<div class="site-nav">
{$vsLang->getWords('global_dungtaitrang', 'Bạn đang đứng tại trang')} :: {$vsLang->currentArrayWords['main_title']}
    (<a href="javascript:vsf.get('{$bw->input['vs']}/','maincontent');">{$vsLang->getWords('global_refresh','Refresh')}</a>)
    <div class="vsf-language-selection">
    {$this->LANGUAGE_LIST}
    </div>
</div>
<div class="clear"></div>
<!-- END OF SITE NAV -->
    {$this->ACP_HELP_SYSTEM}
<!-- BEGIN OF MAIN PAGE CONTENT -->
<div id="maincontent">
    {$this->SITE_MAIN_CONTENT}
<div class="clear"></div>
</div>
<!-- END OF MAIN PAGE CONTENT -->
<div class="clear"></div>
<!-- BEGIN OF FOOTER -->
<div id="footer">
    <img src="{$bw->vars['img_url']}/vsfooter-leftimg.jpg" style="float:left;" height="25" width="25" alt="vs" />
    <span>
    <a href="http://www.redsunic.com" title="{$vsLang->getWords('global_redsunic_full','Công ty Cổ phần Quốc Tế Mặt Trời Đỏ')}" style="color:#000;">
    {$vsLang->getWordsGlobal('global_copyrights', 'Copyright RS Framework 4.1 beta by Redsun Commerce')}
    </a>
    </span>
    <img src="{$bw->vars['img_url']}/vsfooter-rightimg.jpg" style="float:right;" height="25" width="245" alt="rs" />
</div>
<!-- END OF FOOTER -->
</div>
</div>
</center>

EOF;
}
$BWHTML .= <<<EOF

EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e773d684c6a8($menu='',$obj='')
{
;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $obj->getChildren() as $Oobj )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
                    <li><a href="{$Oobj->getUrl(0)}" title="{$Oobj->getTitle()}">{$Oobj->getTitle()}</a></li>
                    
EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e773d684c706($menu='')
{
;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $menu->children as $obj )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
                    <li><a href="{$obj->getUrl(0)}" title="{$obj->getTitle()}">{$obj->getTitle()}</a>
                    
EOF;
if($obj->isDropdown&&count($obj->children)) {
$BWHTML .= <<<EOF

                    <ul>
                    {$this->__foreach_loop__id_4e773d684c6a8($menu,$obj)}
                    </ul>
                    
EOF;
}

$BWHTML .= <<<EOF

                    </li>
                    
EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e773d684c7ca()
{
global $bw, $vsUser, $vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $this->ADMIN_TOP_MENU as $menu )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<li class="{$menu->getClassActive()}"><a href="{$menu->getUrl(0)}" title="{$menu->getTitle()}" >{$menu->getTitle()}</a>

EOF;
if($menu->isDropdown&&count($menu->children)) {
$BWHTML .= <<<EOF

                    <ul>
                    {$this->__foreach_loop__id_4e773d684c706($menu)}
                    </ul>

EOF;
}

$BWHTML .= <<<EOF

</li>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:importantAjaxCallBack:desc::trigger:>
//===========================================================================
function importantAjaxCallBack($Text="",$Url="",$css="") {global $bw;
$BWHTML = "";
//--starthtml--//
//

//--starthtml--//
$BWHTML .= <<<EOF
		<script type="text/javascript">
$(document).ready(function(){
Custom.init();
});
</script>
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
//===========================================================================
// <rsf:buildRadioButtonHTML:desc::trigger:>
//===========================================================================
function buildRadioButtonHTML($name="",$checkedYes=true,$checkedNo=false,$readonly="",$disabled="",$sAttr="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<input type="radio" name="{$name}" value="Yes" $checkedYes $readonly $disabled $sAttr style='width:10px; margin-right:10px;'>Yes</input>
<input type="radio"  name="{$name}" value="No" $checkedNo $readonly $disabled $sAttr style='width:10px; margin-right:10px;'>No</input>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:buildTextTypeHTML:desc::trigger:>
//===========================================================================
function buildTextTypeHTML($textType="",$id="",$name="",$value="",$readonly="",$disabled="",$sAttr="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<input type="$textType" id="{$id}" name="{$name}" value="$value" {$readonly} {$disabled} {$sAttr}></input>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:buildCheckBoxHTML:desc::trigger:>
//===========================================================================
function buildCheckBoxHTML($id="",$name="",$checked="",$readonly="",$disabled="",$sAttr="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<input type="checkbox" id="{$id}" name="{$name}" value="1" $readonly $checked $disabled $sAttr></input>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:buildDropdownBoxHTML:desc::trigger:>
//===========================================================================
function buildDropdownBoxHTML($id="",$name="",$currentValue="",$currentDisplay="",$listOption="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<select id="{$id}" name="{$name}">
<option value="{$currentValue}">{$currentDisplay}</option>
{$listOption}
</select>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:buildOptionHTML:desc::trigger:>
//===========================================================================
function buildOptionHTML($value="",$display="",$sAtr="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<option value="{$value}" $sAtr>{$display}</option>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:buildRadioButtonHTML1:desc::trigger:>
//===========================================================================
function buildRadioButtonHTML1($sAttr="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<input type="radio" {$sAttr}>Yes</input>
<input type="radio" {$sAttr}>No</input>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:buildTextTypeHTML1:desc::trigger:>
//===========================================================================
function buildTextTypeHTML1($sAttr="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<input {$sAttr}></input>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:buildCheckBoxHTML1:desc::trigger:>
//===========================================================================
function buildCheckBoxHTML1($sAttr="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<input type="checkbox" {$sAttr}></input>
EOF;
//--endhtml--//
return $BWHTML;
}


}?>