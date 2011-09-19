<?php
class skin_pages{

//===========================================================================
// <rsf:loadDefault:desc::trigger:>
//===========================================================================
function loadDefault($option="",$document="") {global $bw, $vsLang, $vsPrint;


//--starthtml--//
$BWHTML .= <<<EOF
		<div class="product_block">
                    <div class="pro_block_header">
                        <h2>{$vsLang->getWordsGlobal("global_price_list","Bảng giá")}</h2>
                        </div>
                        <div class="pro_block_wrapper">
                        
EOF;
if($option['pageList']) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e757872de5e3($option,$document)}
                            
EOF;
}

$BWHTML .= <<<EOF

                            <br clear="all" />
                        </div>
                        <div class="pro_block_bottom"></div>
                    </div>    
                <br clear="all" />
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e757872de5e3($option="",$document="")
{
global $bw, $vsLang, $vsPrint;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['pageList'] as $key=>$value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<div class="price_list">
<a href="{$value->getUrl($bw->input[0])}">{$value->getTitle()}</a>
<div class="intro">
{$value->getIntro()}
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
function loadDetail($obj="",$option=array()) {global $vsTemplate;


//--starthtml--//
$BWHTML .= <<<EOF
		<div class="about_home">
<div class="about_home_tt">
{$obj->getTitle()}
</div>
<div class="clear"></div>
<div class="about_home_itr">
<div class="about_home_itr_text" style="width:665px">
<p>{$obj->getContent()}</p>
</div>
</div>

EOF;
if($option['list']) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e757872de708($obj,$option)}

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
function __foreach_loop__id_4e757872de708($obj="",$option=array())
{
global $vsTemplate;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['list'] as $key=>$value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<a href="{$value->getUrl($bw->input[0])}">{$value->getTitle()}</a>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}


}?>