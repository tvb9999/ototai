<?php
class skin_news{

//===========================================================================
// <rsf:loadDefault:desc::trigger:>
//===========================================================================
function loadDefault($option="") {global $bw, $vsLang, $vsSettings;


//--starthtml--//
$BWHTML .= <<<EOF
		<div class="spmoi_all">
<div class="spm_title">{$vsLang->getWordsGlobal("global_title_news","tin tức - sự kiện")}</div>
<div class="box_spm">

EOF;
if($option['pageList']) {
$BWHTML .= <<<EOF

<div class="gioithieu">
{$this->__foreach_loop__id_4e7408a68ade1($option)}
</div>
<div class="viciao">

EOF;
if($option['paging']) {
$BWHTML .= <<<EOF

<span class="disabled">&lt;</span><span class="current">1</span><a href="#?page=2">2</a><a href="#?page=3">3</a><a href="#?page=4">4</a><a href="#?page=5">5</a>...<a href="#?page=2">&gt;</a>

EOF;
}

$BWHTML .= <<<EOF

</div>

EOF;
}

$BWHTML .= <<<EOF

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
function __foreach_loop__id_4e7408a68ade1($option="")
{
global $bw, $vsLang, $vsSettings;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['pageList'] as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<div class="news_item">
<a href="{$value->getUrl('news')}"><img src="{$value->getCacheImagePathByFile($value->getImage(),169,100)}" /></a>
<a class="news_title" href="{$value->getUrl('news')}">{$value->getTitle()}</a>
<div class="tintuc_text">
{$value->getContent(200)}
</div>
</div>
<div class="clear"></div>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:loadDetail:desc::trigger:>
//===========================================================================
function loadDetail($obj="",$option="") {global $bw, $vsLang,$vsSettings;


//--starthtml--//
$BWHTML .= <<<EOF
		<div class="spmoi_all">
<div class="spm_title">{$vsLang->getWordsGlobal("global_title_news","tin tức - sự kiện")}</div>
<div class="box_spm">
<div class="gioithieu">
<div class="news_title">{$obj->getTitle()}</div>
{$obj->getContent()}

EOF;
if(count($option['other'])>0) {
$BWHTML .= <<<EOF

<div class="other_news">
<div class="other_news_title">{$vsLang->getWordsGlobal('global_other','Tin Khác')}</div>
<ul>
{$this->__foreach_loop__id_4e7408a68b013($obj,$option)}
</ul>
</div>

EOF;
}

$BWHTML .= <<<EOF

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
function __foreach_loop__id_4e7408a68b013($obj="",$option="")
{
global $bw, $vsLang,$vsSettings;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['other'] as $item )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<li><a href="{$item->getUrl()}">» {$item->getTitle()}</a></li>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}


}?>