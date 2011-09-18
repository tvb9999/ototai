<?php
class skin_products {
	
	function loadDefault($objList){
		global $bw,$vsPrint,$vsLang;
		
		$BWHTML .= <<<EOF
			<div class="spmoi_all_1" style="margin-top:0px;">
				<div class="spm_title">{$vsLang->getWords("sp","Sáº£n pháº©m")}</div>
				<div class="box_spm" style="width:567px;padding-left:8px;">
					<if="$objList['pageList']">
						<foreach="$objList['pageList'] as $value">
							<div class="sp_sub">
								<div style="padding:5px 0;height:28px"><a href="{$value->getUrl('products')}">{$value->getTitle()}</a></div>
								<div><a href="{$value->getUrl('products')}"><img class="preview" src="{$value->getCacheImagePathByFile($value->getImage(),100,63)}" /></a></div>
								<div class="sp_sub_text">
									<span class="sp_sub_msp">{$value->getCode()}</span><br />
									<if="$value->getPrice()">											
										<span class="sp_sub_msp">{$value->getPrice(true)}</span> {$vsLang->getWords("products_money","VNÄ�","products")}
									<else />
										{$vsLang->getWords("products_call","Call","products")}
									</if> 
								</div>
								<div class="btn_all">
									<!--<div class="btn_chitiet"><a href="{$value->getUrl('products')}">{$vsLang->getWordsGlobal("global_detail","Chi tiáº¿t")}</a></div>-->
									<if="$value->getPrice()">
										<div class="btn_product_detail"><a href="{$value->getUrl("products")}">{$vsLang->getWordsGlobal("global_product_detail","Chi tiáº¿t")}</a></div>
										<div class="btn_dathang"><a href="javascript:vsf.get('orders/addtocart/{$value->getId()}','addcart');sLoading();">{$vsLang->getWordsGlobal("global_add_cart","Ä�áº·t hÃ ng")}</a></div>
									</if>
								</div>
							</div>
						</foreach>
					</if>
				</div>
				
				<div class="viciao">
					<if="$objList['paging']">
						{$objList['paging']}
					</if>
					<!--<span class="disabled">&lt;</span><span class="current">1</span><a href="#?page=2">2</a><a href="#?page=3">3</a><a href="#?page=4">4</a><a href="#?page=5">5</a>...<a href="#?page=2">&gt;</a>-->
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
		global $bw,$vsPrint,$vsLang;
	
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
						<if="$obj->getPrice()!=0">
							<div class="pro_brand"><span>{$obj->getPrice(true)} </span>VNÄ�</div>
							<div class="btn_dathang_ct">
								<a class="addcart_btn" href="javascript:vsf.get('orders/addtocart/{$obj->getId()}','addcart');sLoading();">
									{$vsLang->getWordsGlobal("global_add_cart","Ä�áº·t hÃ ng")}
								</a>
							</div>
						<else />
							<div class="pro_price">{$vsLang->getWords("gb","GiÃ¡")} : <span>Call</span></div>
						</if>
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
					<if="$option['other']['pageList']">  
                            	<foreach="$option['other']['pageList'] as $value">
									<li>
									<div class="sp_sub">
										<div style="padding:5px 0;height:28px"><a href="{$value->getUrl("products")}">{$value->getTitle()}</a></div>
										<div><a href="{$value->getUrl("products")}"><img src="{$value->getCacheImagePathByFile($value->getImage(),100,100)}" /></a></div>
										<if="$value->getPrice()!=0">
											<div class="sp_sub_text">
												<div style="text-align:center"><span class="sp_sub_msp">{$obj->getCode()}</span></div>
												<div style="text-align:center"><span class="sp_sub_msp">{$value->getPrice(true)}</span> VNÄ�</div>
											</div>
											<div class="btn_all">
												<!--<div class="btn_chitiet"><a href="{$value->getUrl('products')}">{$vsLang->getWordsGlobal("global_detail","Chi tiáº¿t")}</a></div>-->
												<div class="btn_product_detail"><a href="{$value->getUrl("products")}">{$vsLang->getWordsGlobal("global_product_detail","Chi tiáº¿t")}</a></div>
												<div class="btn_dathang"><a href="javascript:vsf.get('orders/addtocart/{$value->getId()}','addcart');sLoading();">{$vsLang->getWordsGlobal("global_add_cart","Ä�áº·t hÃ ng")}</a></div>
											</div>
										<else />
											<div class="pro_block_price">{$vsLang->getWords("gb","GiÃ¡")} : Call</div>
										</if>
									</div>
									</li>
								</foreach>
					</if>
					</ul>
					<br clear="all" />
				</div>
				</div>
				</div>
			</div>
EOF;
		return $BWHTML;
	}

}
?>