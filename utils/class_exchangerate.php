<?php
/**@author Tong Nguyen
 **/

class class_exchangeRate {

	public $cache_expires;
	public $cache_lifetime;
	
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

	function getExchangeRage2($moneyCode, $html, $i = 0) {
		
		$i_firstTableTag = stripos ( $html, '<table ' );
		
		$i_moneyCode = stripos ( $html, $moneyCode, $i_firstTableTag );
		
		$i_firstTDTag = stripos ( $html, '<td>', $i_moneyCode + $i );
		$i_secondTDTag = stripos ( $html, '</td>', $i_firstTDTag + $i);
		$i_thirdTDTag = stripos ( $html, '</td>', $i_secondTDTag + $i );
		$i_thirdTDCloseTag = stripos ( $html, '</td>', $i_thirdTDTag + $i );
		
		$rate = substr ( $html, $i_thirdTDTag + 4, $i_thirdTDCloseTag - $i_thirdTDTag );
		
		if ($i == 1)
			$rate = substr ( $html, $i_secondTDTag + 4, $i_thirdTDCloseTag - $i_thirdTDTag );
		$rate = str_replace ( "</td", '', $rate );
		$rate = str_replace ( '<td align="center">', '', $rate );
		$rate = str_replace ( '> ', '', $rate );
		return $rate;
	}
	
	function appendOption($moneyCode, $exchangeBuy, $exchangeSell, $exchangeSangpm, $i) {
		$option = "<p><span class='loaitigia'>$moneyCode</span> <span>$exchangeSell</span></p>";
		//		$option = "<div class='TR TR$i'>
		//							<div class='TD TD1'>$moneyCode</div>
		//							<div class='TD TD2'>$exchangeBuy</div>
		//							<div class='TD TD3'>$exchangeSell</div>
		//							
		//						</div>
		//		";
		return $option;
	
	}

	function getTigia($lifetime = 900, $cachedir = './cache/') {
		
		$this->cache_lifetime = $lifetime;
		$this->cachedir = $cachedir;
		$this->filename = $cachedir . "exchange.i4sn";
		
		if ($this->readcache ())
			return $this->exc;
		$html = $this->getHTMLFromURL ( 'www.vcb.com.vn' );
		
		if (! $html)
			return 'Đang cập nhật thông tin';
		$array = array ('USD', 'AUD', 'EUR', 'GBP', 'JBY' );
		
		foreach ( $array as $value ) {
			
			$this->exc[$value]['buy'] = $this->getExchangeRage ( $value, $html );
			$this->exc[$value]['buy_transfer'] = $this->getExchangeRage ( $value, $html,1 );
			$this->exc[$value]['sell'] = $this->getExchangeRage ( $value, $html,2 );
		}
		$html = $this->getHTMLFromURL ( 'www.vcbhcm.com.vn/tygia.htm' );
		$ect = $this->getExchangeRage2 ( "LNH", $html,2 );
		
		$this->exc["LNH"]['buy'] = 0;
		$this->exc["LNH"]['buy_transfer'] = 0;
		$this->exc["LNH"]['sell'] = $ect;
		
		$this->writecache();
		return $this->exc;
	}

	function getGiaVang($lifetime = 900, $cachedir = './cache/'){
		$this->cache_lifetime = $lifetime;
		$this->cachedir = $cachedir;
		$this->filename = $cachedir . "gold.kx";
		
		if ($this->readcache ())
			return $this->exc;
		$xml = simpleXML_load_file("http://www.sjc.com.vn/xml/tygiavang.xml");
		
		if (! $xml)
			return 'Đang cập nhật thông tin';
			
		$this->exc['title'] = current($xml->title);
		$this->exc['url'] = current($xml->url);
		$ratelist = $xml->ratelist;
		
		$this->exc['ratelist']['updated'] = current($ratelist[0]['updated']);
		$this->exc['ratelist']['unit'] = current($ratelist[0]['unit']);
		
		$city = $xml->ratelist->city[0];
		$this->exc['ratelist']['city'] =  current($city[0]['name']);
		foreach($city as $item) {
				$this->exc['ratelist']['item'][current($item['type'])]['buy']  = current($item['buy']);
				$this->exc['ratelist']['item'][current($item['type'])]['sell'] = current($item['sell']);
//				$this->exc['ratelist']['city']['type'] = current($item['type']);
//				break;
		}
		$this->writecache();
		return $this->exc;
	}
	
	function writecache() {
		$this->cache_expires = time () + $this->cache_lifetime;
		$this->exc['cache_expires'] = $this->cache_expires;
		$fp = fopen ( $this->filename, "w" );
		fwrite ( $fp, serialize ( $this->exc ) );
		fclose ( $fp );
	}
	
	// ------------------- 
	// READ OBJECT FROM CACHE
	// -------------------
	function readcache() {
		$content = @file_get_contents ( $this->filename );
		
		if ($content == false)
			return false;
		$intweather = unserialize ( $content );
		if ($intweather['cache_expires'] < time ())
			return false;
		
		return $this->exc = $intweather;
	}
	
}

