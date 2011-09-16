<?php
class skin_helpbuy{
	
	function loadDefault($currentItem){
		global $bw, $vsLang;
		
		$BWHTML .= <<<EOF
		<div class="spmoi_all">
			<if="$currentItem">
				<div class="spm_title">{$currentItem->getTitle()}</div>
				<div class="box_spm">
					<div class="gioithieu">
							<div class="content_pages">
								{$currentItem->getContent()}
							</div>
					</div>
				</div>
			</if>
			</div>
EOF;
		return $BWHTML;
	}
	
}
	