<?php
/**@author Tong Nguyen
 **/

class weather {
	
	// ------------------- 
	// ATTRIBUTES DECLARATION
	// -------------------
	

	// HANDLING ATTRIBUTES
	public $locationcode; // Yahoo Code for Location
	public $allurl; // generated url with location
	public $parser; // Instance of Class XML Parser
	public $unit; // F or C / Fahrenheit or Celsius
	

	// CACHING ATTRIBUTES
	public $cache_expires;
	public $cache_lifetime;
	public $source; // cache or live
	

	public $forecast = array ();
	

	// ------------------- 
	// CONSTRUCTOR METHOD
	// -------------------
	function parsecached($location, $lifetime = 3600, $unit = 'c', $cachedir = './uploads/weather/') {
		
		
		if(!is_dir($cachedir))
			mkdir($cachedir, 0775, true);
		
		$this->cache_lifetime = $lifetime;
		$this->locationcode = $location;
		$this->unit = $unit;
		$this->cachedir = $cachedir;
		$this->filename = $cachedir . $location;
		$this->forecast = array();
		if ($this->readcache ())
			return $this->forecast;
		$this->parse ();
		$this->writecache ();
		return $this->forecast;
	}
	
	// ------------------- 
	// FUNCTION PARSE
	// -------------------
	function parse() {
		$this->allurl = "http://weather.yahooapis.com/forecastrss";
		$this->allurl .= "?p=" . $this->locationcode;
		$this->allurl .= "&u=" . $this->unit;
		
		// Create Instance of XML Parser Class
		// and parse the XML File
		$this->parser = new xmlParser ();
		$this->parser->parse ( $this->allurl );
		$content = &$this->parser->output [0] ['child'] [0] ['child'];
		foreach ( $content as $item ) {
			switch ($item ['name']) {
				case 'TITLE' :
				case 'LINK' :
				case 'DESCRIPTION' :
				case 'LANGUAGE' :
				case 'LASTBUILDDATE' :
					$this->forecast [$item ['name']] = $item ['content'];
					break;
				case 'YWEATHER:LOCATION' :
				case 'YWEATHER:UNITS' :
				case 'YWEATHER:ASTRONOMY' :
					foreach ( $item ['attrs'] as $attr => $value )
						$this->forecast [$attr] = $value;
					break;
				case 'IMAGE' :
					break;
				case 'ITEM' :
					foreach ( $item ['child'] as $detail ) {
						switch ($detail ['name']) {
							case 'GEO:LAT' :
							case 'GEO:LONG' :
							case 'PUBDATE' :
								$this->forecast [$detail ['name']] = $detail ['content'];
								break;
							case 'YWEATHER:CONDITION' :
								$this->forecast ['CURRENT'] = $detail ['attrs'];
								break;
							case 'YWEATHER:FORECAST' :
								array_push ( $this->forecast, $detail ['attrs'] );
								break;
						}
					}
					break;
			}
		}
		$this->source = 'live';
	
		// FOR DEBUGGING PURPOSES
	//print "<hr><pre>";
	//print_r($this->forecast);
	//print "</pre></p>";
	}
	
	// ------------------- 
	// WRITE OBJECT TO CACHE
	// -------------------
	function writecache() {
		unset ( $this->parser );
		$this->cache_expires = time () + $this->cache_lifetime;
		$fp = fopen ( $this->filename, "w" );
		fwrite ( $fp, serialize ( $this ) );
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
		if ($intweather->cache_expires < time ())
			return false;
		
		$this->source = 'cache';
		return $this->forecast = $intweather->forecast;
	}

}

?>