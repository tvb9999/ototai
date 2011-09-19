<?php
class skin_addon{

//===========================================================================
// <rsf:portlet_supports:desc::trigger:>
//===========================================================================
function portlet_supports($option=array()) {
global $bw, $vsLang;



//--starthtml--//
$BWHTML .= <<<EOF
		<div class="sitebar_item">

            <h3>Hỗ trợ online - Liên kết web</h3>

                    <div class="sitebar_item_bg suponl">

                    
EOF;
if( $option['support'] ) {
$BWHTML .= <<<EOF


                    {$this->__foreach_loop__id_4e7745bba16b1($option)}

                        
EOF;
}

$BWHTML .= <<<EOF


                        <div class="clear_left"></div>

                        <select id="weblink">

                        <option>{$vsLang->getWords('global_weblink','- - - - chọn liên kết - - - -')}</option>

                        
EOF;
if( $option['weblink'] ) {
$BWHTML .= <<<EOF


                        {$this->__foreach_loop__id_4e7745bba1723($option)}

                        
EOF;
}

$BWHTML .= <<<EOF


                        </select>

                </div>

                <div class="clear_left"></div>

</div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7745bba16b1($option=array())
{

global $bw, $vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach(  $option['support'] as $partner )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

                    {$partner->show()}

                        
EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7745bba1723($option=array())
{

global $bw, $vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach(  $option['weblink'] as $weblink  )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

                        <option value="{$weblink->getWebsite()}">{$weblink->getTitle()}</option>

                        
EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:portlet_partners:desc::trigger:>
//===========================================================================
function portlet_partners($partners=array()) {
global $bw, $vsLang;



//--starthtml--//
$BWHTML .= <<<EOF
		<div class="sitebar_item">

            <h3 class="adv">{$vsLang->getWords('global_partner','Quảng cáo')}</h3>

                <div class="sitebar_item_bg quangcao">

                
EOF;
if( $partners ) {
$BWHTML .= <<<EOF


                {$this->__foreach_loop__id_4e7745bba1963($partners)}

                    
EOF;
}

$BWHTML .= <<<EOF


                </div>

            </div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7745bba1963($partners=array())
{

global $bw, $vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach(  $partners as $obj  )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

                <a href="{$obj->getWebsite()}" title="{$obj->getTitle()}">

                {$obj->createImageCache($obj->getFileId(),190)}

                </a>

                
EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:portlet_productCategory:desc::trigger:>
//===========================================================================
function portlet_productCategory($option="") {
global $bw, $vsLang, $vsSettings, $vsMenu;



//--starthtml--//
$BWHTML .= <<<EOF
		<div class="sitebar_item">

<h3>{$vsLang->getWords('global_product_cateogyr','Danh mục sản phẩm')}</h3>

<ul class="pro_cate">


EOF;
if($vsMenu->getCategoryGroup('products')->getChildren()) {
$BWHTML .= <<<EOF


{$this->__foreach_loop__id_4e7745bba1c82($option)}


EOF;
}

$BWHTML .= <<<EOF


                </ul>

            </div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7745bba1c82($option="")
{

global $bw, $vsLang, $vsSettings, $vsMenu;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $vsMenu->getCategoryGroup('products')->getChildren() as $obj )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

<li>

<a href="{$obj->getUrlCategory()}" title="{$obj->getTitle()}">

{$obj->getTitle()}

</a>

</li>


EOF;
$vsf_count++;
	}
	return $BWHTML;
}


}?>