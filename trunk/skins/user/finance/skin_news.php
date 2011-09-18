<?php
class skin_news {
	function loadDefault($option){
		global $bw, $vsLang, $vsSettings;
		
		$BWHTML .= <<<EOF
			<div class="spmoi_all">
				<div class="spm_title">{$vsLang->getWordsGlobal("global_title_news","tin tức - sự kiện")}</div>
				<div class="box_spm">
					<if="$option['pageList']">
						<div class="gioithieu">
							<foreach="$option['pageList'] as $value">
								<div class="news_item">
									<a href="{$value->getUrl('news')}"><img src="{$value->getCacheImagePathByFile($value->getImage(),169,100)}" /></a>
									<a class="news_title" href="{$value->getUrl('news')}">{$value->getTitle()}</a>
									<div class="tintuc_text">
										{$value->getContent(200)}
									</div>
								</div>
								<div class="clear"></div>
							</foreach>
						</div>
						<div class="viciao">
							<if="$option['paging']">
								<span class="disabled">&lt;</span><span class="current">1</span><a href="#?page=2">2</a><a href="#?page=3">3</a><a href="#?page=4">4</a><a href="#?page=5">5</a>...<a href="#?page=2">&gt;</a>
							</if>
						</div>
					</if>
				</div>
			</div>
			<div class="clear"></div>
			<div class="qc3">
				<a href="#"><img src="images/qc3.jpg" /></a>
			</div>
EOF;
		return $BWHTML;
	}
	
	function loadDetail($obj, $option){
		global $bw, $vsLang,$vsSettings;
		
		$BWHTML .= <<<EOF
		
		<div class="spmoi_all">
			<div class="spm_title">{$vsLang->getWordsGlobal("global_title_news","tin tức - sự kiện")}</div>
			<div class="box_spm" id="box_detail">
				<div class="gioithieu">
					<div class="news_title">{$obj->getTitle()}</div>
					{$obj->getContent()}
					<if="count($option['other'])>0">
						<div class="other_news">
							<div class="other_news_title">{$vsLang->getWordsGlobal('global_other','Tin Khác')}</div>
							<ul>
							<foreach="$option['other'] as $item">
								<li><a href="{$item->getUrl()}">» {$item->getTitle()}</a></li>
							</foreach>
							</ul>
						</div>
					</if>
				</div>
			</div>
			
		</div>
EOF;
		return $BWHTML;
	}
}
?>