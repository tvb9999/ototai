<?php
class Utilities {

	/*
		Lấy thông tin thời tiết từ vnexpress.
		@param $location: địa điểm cần lấy thông tin thời tiết
		@return array.
	*/
	function getWeatherFromVNExpress($location = 'HCM') {
		global $vsStd;
		$vsStd->requireFile ( UTILS_PATH . 'class_xml.php' );
		
		$myXML = new class_xml ();
		$xmlFile = "http://vnexpress.net/ListFile/Weather/{$location}.xml";
		
		$myXML->xml_parse_document ( file_get_contents ( $xmlFile ) );
		$array = current ( $myXML->xml_array );
		
		return $array;
	}

	function getCurrencyFormVietcombank() {
		global $vsStd;
		$vsStd->requireFile ( UTILS_PATH . 'class_xml.php' );
		
		$myXML = new class_xml ();
		$xmlFile = "http://www.vietcombank.com.vn/ExchangeRates/ExrateXML.aspx";
		
		$myXML->xml_parse_document ( file_get_contents ( $xmlFile ) );
		return current ( $myXML->xml_array );
	}

	/*
		Lấy thông tin vàng từ vnexpress.
		@return html.
	*/
	function getGoldFormVNExpress() {
		global $vsLang;
		return <<<EOF
				<div id='goldPortlet'>
					<div> {$vsLang->getWords('addon_goldPortlet_Title','Giá Vàng')}</div>
					<div class='goldCol1' style='float:left;'>
						{$vsLang->getWords('addongoldPortlet_Type','Loại')}
					</div>
					<div class='goldCol2' style='float:left;'>
						{$vsLang->getWords('addongoldPortlet_Buy','Mua')}
					</div>
					<div class='goldCol3' style='float:left;'>
						{$vsLang->getWords('addongoldPortlet_Sell','Bán')}
					</div>
					<div class='clear'></div>
				</div>
				<script type='text/javascript' src='http://vnexpress.net/Service/Gold_Content.js'></script>
				<script type='text/javascript'>
					var goldDiv = '';
					
					goldDiv+= "<div class='goldCol1' style='float:left;'>SBJ</div>";
					goldDiv+= "<div class='goldCol2' style='float:left;'>" + vGoldSbjBuy + "</div>";
					goldDiv+= "<div class='goldCol3' style='float:left;'>" + vGoldSbjSell + "</div>";
					goldDiv+= "<div class='clear'></div>";
					
					goldDiv+= "<div class='goldCol1' style='float:left;'>SJC</div>";
					goldDiv+= "<div class='goldCol2' style='float:left;'>" + vGoldSjcBuy + "</div>";
					goldDiv+= "<div class='goldCol3' style='float:left;'>" + vGoldSjcSell + "</div>";
					goldDiv+= "<div class='clear'></div>";
					
					$('#goldPortlet').append(goldDiv);
				</script>
EOF;
	}

	function getHTMLFromURL($url) {
		return file_get_contents ( "http://$url" );
	}

	function getExchangeRage($moneyCode, $html, $i = 0) {
		
		$i_firstTableTag = stripos ( $html, '<table class="tbl-exch"' );
		
		$i_moneyCode = stripos ( $html, $moneyCode, $i_firstTableTag );
		
		$i_firstTDTag = stripos ( $html, '<td>', $i_moneyCode + $i );
		$i_secondTDTag = stripos ( $html, '<td>', $i_firstTDTag + $i );
		$i_thirdTDTag = stripos ( $html, '<td>', $i_secondTDTag + $i );
		
		$i_thirdTDCloseTag = stripos ( $html, '</td>', $i_thirdTDTag + $i );
		
		$rate = substr ( $html, $i_thirdTDTag + 4, $i_thirdTDCloseTag - $i_thirdTDTag );
		if ($i == 1)
			$rate = substr ( $html, $i_secondTDTag + 4, $i_thirdTDCloseTag - $i_thirdTDTag );
		$rate = str_replace ( "</td", '', $rate );
		
		return $rate;
	
	}

	function appendOption($moneyCode, $exchangeBuy, $exchangeSell, $exchangeSangpm, $i) {
		global $bw;
		$option = <<<EOF
			<li>
					<img src="{$bw->vars['img_url']}/lang_{$moneyCode}.jpg">
					<p>{$moneyCode}</p>
					<span>{$exchangeSell}</span>
					<div class="clear"></div>
			</li>
EOF;
		return $option;
	
	}

	function getTigia() {
		
		$html = $this->getHTMLFromURL ( 'www.vcb.com.vn' );
		
		if (! $html)
			return 'Đang cập nhật thông tin';
		$array = array ('USD', 'EUR' );
		$i = 0;
		
		foreach ( $array as $value ) {
			$options .= $this->appendOption ( $value, $this->getExchangeRage ( $value, $html ), $this->getExchangeRage ( $value, $html, 1 ), $this->getExchangeRage ( $value, $html, 2 ), $i );
			if ($i ++ == 1)
				$i = 0;
		}
		return $options;
	}

	function getWeather() {
		
		$html = $this->getHTMLFromURL ( 'www.vcb.com.vn' );
		
		if (! $html)
			return 'Đang cập nhật thông tin';
		$array = array ('USD', 'EUR' );
		$i = 0;
		
		foreach ( $array as $value ) {
			$options .= $this->appendOption ( $value, $this->getExchangeRage ( $value, $html ), $this->getExchangeRage ( $value, $html, 1 ), $this->getExchangeRage ( $value, $html, 2 ), $i );
			if ($i ++ == 1)
				$i = 0;
		}
		return $options;
	}

}
?>