<?php
class skin_home{

//===========================================================================
// <rsf:loadDefault:desc::trigger:>
//===========================================================================
function loadDefault($result="") {global $bw, $vsLang, $vsTemplate, $vsFile;


//--starthtml--//
$BWHTML .= <<<EOF
		<div class="spmoi_all">
<div class="spm_title">{$vsLang->getWordsGlobal("global_pro_new_title","Sản phẩm khuyến mãi")}</div>
<div class="box_spm">
<div class="sp_all">
<div class="slide_sub">
<!--
<div class="prev">
<a href=""><img src="{$bw->vars['img_url']}/prev.jpg" /></a>
</div>
<div class="next">
<a href=""><img src="{$bw->vars['img_url']}/next.jpg" /></a>
</div>-->
<ul>

EOF;
if($result['sanphammoi']) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e774ed3e23a9($result)}

EOF;
}

$BWHTML .= <<<EOF

</ul>
<div id="addcart"></div>
<div class="clear"></div>
<div class="xtc">
<a href="{$bw->base_url}products/new/">{$vsLang->getWordsGlobal("global_view_all","Xem tất cả")} &raquo</a>
</div>
</div>
</div>
</div>
</div>
<div class="spmoi_all_1">
<div class="spm_title">{$vsLang->getWordsGlobal("global_title_product","sản phẩm")}</div>
<div class="box_spm">
<div class="slide_spm" style="position: relative; display: inline-block;">
<ul>

EOF;
if($result['sanpham']) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e774ed3e248d($result)}

EOF;
}

$BWHTML .= <<<EOF

</ul>
</div>
</div>
</div>
<div class="clear"></div>
<div class="qc3">

EOF;
if($vsTemplate->global_template->partner) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e774ed3e254a($result)}

EOF;
}

$BWHTML .= <<<EOF

</div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e774ed3e23a9($result="")
{
global $bw, $vsLang, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $result['sanphammoi'] as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<li>
<div class="sp_sub">
<a  class="name_pro" href="{$value->getUrl('products')}">{$value->getTitle()}</a> 
<a href="{$value->getUrl('products')}"><img src="{$value->getCacheImagePathByFile($value->getImage(),100,62)}" /></a>
<div class="sp_sub_text">
<span class="sp_sub_msp">{$value->getCode()}</span><br />

EOF;
if($value->getPrice()) {
$BWHTML .= <<<EOF

<span class="sp_sub_msp">{$value->getPrice(true)}</span> {$vsLang->getWords("products_money","VNĐ","products")}

EOF;
}

else {
$BWHTML .= <<<EOF

{$vsLang->getWords("products_call","Call","products")}

EOF;
}
$BWHTML .= <<<EOF
 
</div>
<div class="btn_all">

EOF;
if($value->getPrice()) {
$BWHTML .= <<<EOF

<div class="btn_product_detail"><a href="{$value->getUrl('products')}">{$vsLang->getWordsGlobal("global_product_detail","Chi tiết")}</a></div>
<div class="btn_dathang"><a href="javascript:vsf.get('orders/addtocart/{$value->getId()}','addcart');sLoading();">{$vsLang->getWordsGlobal("global_add_cart","Đặt hàng")}</a></div>

EOF;
}

$BWHTML .= <<<EOF

</div>
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
function __foreach_loop__id_4e774ed3e248d($result="")
{
global $bw, $vsLang, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $result['sanpham'] as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<li class="sp_sub">
<p class="title"><a href="{$value->getUrl('products')}" class="sp_banchay_title">{$value->getTitle()}</a></p>
<a href="{$value->getUrl('products')}"><img src="{$value->getCacheImagePathByFile($value->getImage(),100,62)}" /></a>
<div class="sp_sub_text">
<span class="sp_sub_msp">{$value->getCode()}</span><br />

EOF;
if($value->getPrice()) {
$BWHTML .= <<<EOF

<span class="sp_sub_msp">{$value->getPrice(true)}</span> {$vsLang->getWords("products_money","VNĐ","products")}

EOF;
}

else {
$BWHTML .= <<<EOF

{$vsLang->getWords("products_call","Call","products")}

EOF;
}
$BWHTML .= <<<EOF
 
</div>
<div class="btn_all">

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
function __foreach_loop__id_4e774ed3e254a($result="")
{
global $bw, $vsLang, $vsTemplate, $vsFile;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsTemplate->global_template->partner as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

EOF;
if($value->getPosition()==5) {
$BWHTML .= <<<EOF

<a href="{$value->getWebsite()}" target="_blank">{$vsFile->arrayFiles[$value->getFileId()]->show(577,0,1,1,1,1)}</a>

EOF;
}

$BWHTML .= <<<EOF


EOF;
$vsf_count++;
	}
	return $BWHTML;
}


}?>