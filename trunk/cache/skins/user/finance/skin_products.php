<?php
class skin_products{

//===========================================================================
// <rsf:loadDefault:desc::trigger:>
//===========================================================================
function loadDefault($objList="") {global $bw,$vsPrint,$vsLang;


//--starthtml--//
$BWHTML .= <<<EOF
		<div class="spmoi_all_1" style="margin-top:0px;">
<div class="spm_title">{$vsLang->getWords("sp","Sáº£n pháº©m")}</div>
<div class="box_spm" style="width:567px;padding-left:8px;">

EOF;
if($objList['pageList']) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e774bed478d3($objList)}

EOF;
}

$BWHTML .= <<<EOF

</div>

<div class="viciao">

EOF;
if($objList['paging']) {
$BWHTML .= <<<EOF

{$objList['paging']}

EOF;
}

$BWHTML .= <<<EOF

<!--<span class="disabled">&lt;</span><span class="current">1</span><a href="#?page=2">2</a><a href="#?page=3">3</a><a href="#?page=4">4</a><a href="#?page=5">5</a>...<a href="#?page=2">&gt;</a>-->
</div>
</div>
<div class="clear"></div>
<div class="qc3">
<a href="#"><img src="images/qc3.jpg" /></a>
</div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e774bed478d3($objList="")
{
global $bw,$vsPrint,$vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $objList['pageList'] as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<div class="sp_sub">
<div style="padding:5px 0;height:28px"><a href="{$value->getUrl('products')}">{$value->getTitle()}</a></div>
<div><a href="{$value->getUrl('products')}"><img class="preview" src="{$value->getCacheImagePathByFile($value->getImage(),100,63)}" /></a></div>
<div class="sp_sub_text">
<span class="sp_sub_msp">{$value->getCode()}</span><br />

EOF;
if($value->getPrice()) {
$BWHTML .= <<<EOF

<span class="sp_sub_msp">{$value->getPrice(true)}</span> {$vsLang->getWords("products_money","VNÄ�","products")}

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
<!--<div class="btn_chitiet"><a href="{$value->getUrl('products')}">{$vsLang->getWordsGlobal("global_detail","Chi tiáº¿t")}</a></div>-->

EOF;
if($value->getPrice()) {
$BWHTML .= <<<EOF

<div class="btn_product_detail"><a href="{$value->getUrl("products")}">{$vsLang->getWordsGlobal("global_product_detail","Chi tiáº¿t")}</a></div>
<div class="btn_dathang"><a href="javascript:vsf.get('orders/addtocart/{$value->getId()}','addcart');sLoading();">{$vsLang->getWordsGlobal("global_add_cart","Ä�áº·t hÃ ng")}</a></div>

EOF;
}

$BWHTML .= <<<EOF

</div>
</div>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:loadDetail:desc::trigger:>
//===========================================================================
function loadDetail($obj="",$option="") {global $bw,$vsPrint,$vsLang;


//--starthtml--//
$BWHTML .= <<<EOF
		<div class="spmoi_all_pd" style="margin-bottom: 20px;">
<div class="spm_title"><a href="{$bw->base_url}products">{$vsLang->getWords("sp","Sáº£n pháº©m")}</a>&nbsp; &raquo&nbsp; <a href="{$bw->base_url}products/category/{$option['category']->getId()}">{$option['category']->getTitle()}</a></div>
<div class="box_spm" id="box_detail">
<div class="image_block">
<p>
<img src="{$obj->getCacheImagePathByFile($obj->getImage(),189,119)}"/>
</p>
<br clear="all" />
</div>
<div class="product_info_block">
<div class="news_title">
{$obj->getTitle()}</div>
<div class="pro_status">
<span class="sp_sub_msp">{$obj->getCode()}</span></div>

EOF;
if($obj->getPrice()!=0) {
$BWHTML .= <<<EOF

<div class="pro_brand"><span>{$obj->getPrice(true)} </span>VNÄ�</div>
<div class="btn_dathang_ct">
<a class="addcart_btn" href="javascript:vsf.get('orders/addtocart/{$obj->getId()}','addcart');sLoading();">
{$vsLang->getWordsGlobal("global_add_cart","Ä�áº·t hÃ ng")}
</a>
</div>

EOF;
}

else {
$BWHTML .= <<<EOF

<div class="pro_price">{$vsLang->getWords("gb","GiÃ¡")} : <span>Call</span></div>

EOF;
}
$BWHTML .= <<<EOF

</div>
<div id = "addcart"></div>
<div class="clear"></div>
<div class="product_feature">
<table border="0" cellpadding="0" cellspacing="0" width="auto">
<tr>
<td style="height: 5px;"><hr style="margin: 0;" /></td>
</tr>
<tr>
<td class="feature">
{$obj->getContent()}
</td>
</tr>
</table>
</div>
<br clear="all" />
</div>
</div>
<!-- Other pro -->
<div class="sp_ct_all" style="margin-top:0px">
<div class="spm_title"><span>{$vsLang->getWords("spsame","Sáº£n pháº©m cÃ¹ng loáº¡i")}</span> </div>
<div class="box_spm">
<div class="sp_all">
<div class="slide_sub other_product">

<ul>

EOF;
if($option['other']['pageList']) {
$BWHTML .= <<<EOF
  
                            {$this->__foreach_loop__id_4e774bed47b1f($obj,$option)}

EOF;
}

$BWHTML .= <<<EOF

</ul>
<br clear="all" />
</div>
</div>
</div>
</div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e774bed47b1f($obj="",$option="")
{
global $bw,$vsPrint,$vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['other']['pageList'] as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<li>
<div class="sp_sub">
<div style="padding:5px 0;height:28px"><a href="{$value->getUrl("products")}">{$value->getTitle()}</a></div>
<div><a href="{$value->getUrl("products")}"><img src="{$value->getCacheImagePathByFile($value->getImage(),100,100)}" /></a></div>

EOF;
if($value->getPrice()!=0) {
$BWHTML .= <<<EOF

<div class="sp_sub_text">
<div style="text-align:center"><span class="sp_sub_msp">{$obj->getCode()}</span></div>
<div style="text-align:center"><span class="sp_sub_msp">{$value->getPrice(true)}</span> VNÄ�</div>
</div>
<div class="btn_all">
<!--<div class="btn_chitiet"><a href="{$value->getUrl('products')}">{$vsLang->getWordsGlobal("global_detail","Chi tiáº¿t")}</a></div>-->
<div class="btn_product_detail"><a href="{$value->getUrl("products")}">{$vsLang->getWordsGlobal("global_product_detail","Chi tiáº¿t")}</a></div>
<div class="btn_dathang"><a href="javascript:vsf.get('orders/addtocart/{$value->getId()}','addcart');sLoading();">{$vsLang->getWordsGlobal("global_add_cart","Ä�áº·t hÃ ng")}</a></div>
</div>

EOF;
}

else {
$BWHTML .= <<<EOF

<div class="pro_block_price">{$vsLang->getWords("gb","GiÃ¡")} : Call</div>

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


}?>