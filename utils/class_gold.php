<?php
/**@author Tong Nguyen
 **/

class class_gold {

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

	function getGold($lifetime = 900, $cachedir = './cache/') {
		
//		$this->cache_lifetime = $lifetime;
//		$this->cachedir = $cachedir;
//		$this->filename = $cachedir . "gold.kx";
		
		
		
		$xml = simplexml_load_file("http://www.sjc.com.vn/xml/tygiavang.xml");
//
		foreach($xml->children() as $child)
  		{
//  			if($child->getName()=="ratelist"){
//  				foreach ($child->children()->city as $item){
//  					$option[]['city'] =  $item->
//  					if($item->children()){
//  						echo $item->getName() . ": " . $item['name'] . "<br />";
//  						foreach ($item->children() as $value){
//  							$option[$child->getName()]['attributes']['value']= $child[$item['name']];
//  							echo $value->getName() . ":" . $value['buy'] . ":" . $value['sell'] . ":" . $value['type'] ."<br />";
//  						}
//  					}
//  				}
//  			}else{
//	  			echo $child->getName() . ": " . $child . "<br />";
				$result[$child->getName()] = $child;
//  			}
	  	}
	  	$array = $result;
	  	print("<pre>");
	  	print_r($array ? $array : 'Null');
	  	print("</pre>");
//	  	exit();
	  	foreach($result as $key => $item){
	  		if($key=='title'){
	  			$option['title'] = current($item);
	  		}
	  		if($key=='url'){
	  			$option['url'] = current($item);
	  		}
	  		if($key=='ratelist'){
	  			$option['infor'] =  current($item);
//	  			unset($item[$option['infor']]);
	  			if($item){
		  			foreach ($item as $key1 => $value){
		  				if($key1=='city'){
		  					$option['name'] = current($value);
		  					foreach($value as $val){
		  						
		  					}
		  				}
		  			}
	  			}
	  		}
	  		
	  	}
	  	$array = $option;
	  	print("<pre>");
	  	print_r($array ? $array : 'Null');
	  	print("</pre>");
	  	exit();
//		$xml=simplexml_load_file("test.xml");
//		foreach($xml->children() as $child)//cái này sẽ lấy ra những tab là con của tab menu_item tức là lấy item_main 
//		{
//		    foreach($child->children() as $chau)//cái này lấy ra các tab con của item_main bao gồm (item_id,name)
//		    {
//		         if($chau->getName()=="ratelist")
//		                echo $chau['name']."";  //giá trị trả ra không phải là 1 chuỗi do đó phải ép kiểu nó về string(."")
//		    }
//		    
//		}
//
		
//		if ($this->readcache ())
//			return $this->exc;
//		$html = $this->getHTMLFromURL ( 'www.sjc.com.vn' );
//		
//		if (! $html)
//			return 'Đang cập nhật thông tin';
//		$array = array ('HCM' => array( 0 => 'Vàng SJC 1 Kg', 1 => 'Vàng SJC 10L'), 'HaNoi', 'DaNang', 'NhaTrang', 'CanTho', 'CaMau' );
//		
//		foreach ( $array as $value ) {
//			
//			$this->exc[$value]['buy'] = $this->getExchangeRage ( $value, $html );
//			$this->exc[$value]['buy_transfer'] = $this->getExchangeRage ( $value, $html,1 );
//			$this->exc[$value]['sell'] = $this->getExchangeRage ( $value, $html,2 );
//		}
//		$html = $this->getHTMLFromURL ( 'www.vcbhcm.com.vn/tygia.htm' );
//		$ect = $this->getExchangeRage2 ( "LNH", $html,2 );
//		
//		$this->exc["LNH"]['buy'] = 0;
//		$this->exc["LNH"]['buy_transfer'] = 0;
//		$this->exc["LNH"]['sell'] = $ect;
//		
//		$this->writecache();
//		return $this->exc;
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

