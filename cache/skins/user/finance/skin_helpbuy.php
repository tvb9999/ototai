<?php
class skin_helpbuy{

//===========================================================================
// <rsf:loadDefault:desc::trigger:>
//===========================================================================
function loadDefault($currentItem="") {global $bw, $vsLang;

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
EOF;
//--endhtml--//
return $BWHTML;
}


}?>