<?php
class skin_pages{
	
	function loadDefault($option ,$document){
		global $bw, $vsLang, $vsPrint;
		
		$BWHTML .= <<<EOF
			<div class="product_block">
                    	<div class="pro_block_header">
                        	<h2>{$vsLang->getWordsGlobal("global_price_list","Bảng giá")}</h2>
                        </div>
                        <div class="pro_block_wrapper">
                        	<if="$option['pageList']">
								<foreach="$option['pageList'] as $key=>$value">
									<div class="price_list">
										<a href="{$value->getUrl($bw->input[0])}">{$value->getTitle()}</a>
										<div class="intro">
											{$value->getIntro()}
										</div>																								
									</div>
								</foreach>
                            </if>
                            <br clear="all" />
                        </div>
                        <div class="pro_block_bottom"></div>
                    </div>    
                <br clear="all" />
EOF;
	}
	
	function loadDetail($obj, $option=array()){
		global $vsTemplate;

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
				<if="$option['list']">
					<foreach="$option['list'] as $key=>$value">
							<a href="{$value->getUrl($bw->input[0])}">{$value->getTitle()}</a>
					</foreach>
				</if>
			</div>
EOF;
	}
}
?>