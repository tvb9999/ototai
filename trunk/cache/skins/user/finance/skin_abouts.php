<?php
if(!class_exists('skin_pages'))
require_once ('cache/skins/user/finance/skin_pages.php');
class skin_abouts extends skin_pages {

//===========================================================================
// <rsf:loadDefault:desc::trigger:>
//===========================================================================
function loadDefault($currentItem="") {global $bw, $vsLang, $vsTemplate, $vsFile;

//--starthtml--//
$BWHTML .= <<<EOF
		<div class="spmoi_all">

EOF;
if($currentItem) {
$BWHTML .= <<<EOF

<div class="spm_title">{$currentItem->getTitle()}</div>
<div class="box_spm">
<div class="gioithieu">
<div class="content_pages">
{$currentItem->getContent()}
</div>
</div>
</div>

EOF;
}

$BWHTML .= <<<EOF

</div>
<div class="clear"></div>
<div class="qc3">

EOF;
if($vsTemplate->global_template->partner) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e757872de022($currentItem)}

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
function __foreach_loop__id_4e757872de022($currentItem="")
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