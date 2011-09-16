<?php
class skin_addon {
	
	function portlet_supports($option= array()) {
		global $bw, $vsLang;
	
		$BWHTML .= <<<EOF
			<div class="sitebar_item">
            		<h3>Hỗ trợ online - Liên kết web</h3>
                    <div class="sitebar_item_bg suponl">
                    	<if=" $option['support'] ">
                    	<foreach=" $option['support'] as $partner">
                    		{$partner->show()}
                        </foreach>
                        </if>
                        <div class="clear_left"></div>
                        <select id="weblink">
                        	<option>{$vsLang->getWords('global_weblink','- - - - chọn liên kết - - - -')}</option>
                        	<if=" $option['weblink'] ">
                        	<foreach=" $option['weblink'] as $weblink ">
                        		<option value="{$weblink->getWebsite()}">{$weblink->getTitle()}</option>
                        	</foreach>
                        	</if>
                        </select>
	                </div>
	                <div class="clear_left"></div>
			</div>
EOF;
		return $BWHTML;
	}
	
	function portlet_partners($partners = array()) {
		global $bw, $vsLang;

		$BWHTML .= <<<EOF
			 <div class="sitebar_item">
            	<h3 class="adv">{$vsLang->getWords('global_partner','Quảng cáo')}</h3>
                <div class="sitebar_item_bg quangcao">
                	<if=" $partners ">
                	<foreach=" $partners as $obj ">
                		<a href="{$obj->getWebsite()}" title="{$obj->getTitle()}">
                			{$obj->createImageCache($obj->getFileId(),190)}
                		</a>
                	</foreach>
                    </if>
                </div>
            </div>
EOF;
		return $BWHTML;
	}
	
	function portlet_productCategory($option){
		global $bw, $vsLang, $vsSettings, $vsMenu;
		
		$BWHTML .= <<<EOF
			<div class="sitebar_item">
				<h3>{$vsLang->getWords('global_product_cateogyr','Danh mục sản phẩm')}</h3>
				<ul class="pro_cate">
					<if="$vsMenu->getCategoryGroup('products')->getChildren()">
					<foreach="$vsMenu->getCategoryGroup('products')->getChildren() as $obj">
						<li>
							<a href="{$obj->getUrlCategory()}" title="{$obj->getTitle()}">
								{$obj->getTitle()}
							</a>
						</li>
					</foreach>
					</if>
                </ul>
            </div>
EOF;
		return $BWHTML;
	}
}
?>