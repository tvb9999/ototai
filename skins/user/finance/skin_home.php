<?php
class skin_home{

	function loadDefault($result) {
		global $bw, $vsLang, $vsTemplate, $vsFile;
		
		$BWHTML .= <<<EOF
			<div class="spmoi_all">
				<div class="spm_title">{$vsLang->getWordsGlobal("global_pro_new_title","Sản phẩm khuyến mãi")}</div>
				<div class="box_spm">
					<div class="sp_all">
						<div class="slide_sub">
							<!--
							<div class="prev">
								<a href=""><img src="{$bw->vars['img_url']}/prev.jpg" /></a>
							</div>
							<div class="next">
								<a href=""><img src="{$bw->vars['img_url']}/next.jpg" /></a>
							</div>-->
							<ul>
							<if="$result['sanphammoi']">
								<foreach="$result['sanphammoi'] as $value">
									<li>
										<div class="sp_sub">
											<a  class="name_pro" href="{$value->getUrl('products')}">{$value->getTitle()}</a> 
											<a href="{$value->getUrl('products')}"><img src="{$value->getCacheImagePathByFile($value->getImage(),110,80)}" /></a>
											<div class="sp_sub_text">
												<br />
												<if="$value->getPrice()">											
													<span class="sp_sub_msp">{$value->getPrice(true)}</span> {$vsLang->getWords("products_money","VNĐ","products")}
												<else />
													{$vsLang->getWords("products_call","Call","products")}
												</if> 
											</div>
											<div class="btn_all">
												<if="$value->getPrice()">
												<div class="btn_product_detail"><a href="{$value->getUrl('products')}">{$vsLang->getWordsGlobal("global_product_detail","Chi tiết")}</a></div>
													<div class="btn_dathang"><a href="javascript:vsf.get('orders/addtocart/{$value->getId()}','addcart');sLoading();">{$vsLang->getWordsGlobal("global_add_cart","Đặt hàng")}</a></div>
												</if>
											</div>
										</div>
									</li>
								</foreach>
							</if>		
							</ul>
							<div id="addcart"></div>
							<div class="clear"></div>
							<div class="xtc">
								<a href="{$bw->base_url}products/new/">{$vsLang->getWordsGlobal("global_view_all","Xem tất cả")} &raquo</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="spmoi_all_1">
				<div class="spm_title">{$vsLang->getWordsGlobal("global_title_product","sản phẩm")}</div>
				<div class="box_spm">
					<div class="slide_spm" style="position: relative; display: inline-block;">
					<ul>
					<if="$result['sanpham']">
						<foreach="$result['sanpham'] as $value">
							<li class="sp_sub">
								<p class="title"><a href="{$value->getUrl('products')}" class="sp_banchay_title">{$value->getTitle()}</a></p>
								<a href="{$value->getUrl('products')}"><img src="{$value->getCacheImagePathByFile($value->getImage(),110,80)}" /></a>
								<div class="sp_sub_text">
									<br />
									<if="$value->getPrice()">											
										<span class="sp_sub_msp">{$value->getPrice(true)}</span> {$vsLang->getWords("products_money","VNĐ","products")}
									<else />
										{$vsLang->getWords("products_call","Call","products")}
									</if> 
								</div>
								<div class="btn_all">
									<if="$value->getPrice()">
										<div class="btn_product_detail"><a href="{$value->getUrl('products')}">{$vsLang->getWordsGlobal("global_product_detail","Chi tiết")}</a></div>
										<div class="btn_dathang"><a href="javascript:vsf.get('orders/addtocart/{$value->getId()}','addcart');sLoading();">{$vsLang->getWordsGlobal("global_add_cart","Đặt hàng")}</a></div>
									</if>
								</div>
							</li>
						</foreach>
					</if>
					</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<div class="qc3">
				<if="$vsTemplate->global_template->partner">
					<foreach="$vsTemplate->global_template->partner as $value">
						<if="$value->getPosition()==5">
							<a href="{$value->getWebsite()}" target="_blank">{$vsFile->arrayFiles[$value->getFileId()]->show(577,0,1,1,1,1)}</a>
						</if>
					</foreach>
				</if>
			</div>
EOF;
		return $BWHTML;
	}
}
?>