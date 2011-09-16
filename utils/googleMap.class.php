<?php

class googleMap {
	
	//Set up our variables
	private $longitude = "";
	private $latitude = "";
	private $precision = "";
	//ABQIAAAAzEEPImDN04U85R0Qd6TgMhSHxt0utBO3tDPKcW3oV5N6_58hOhSbvWaDxgQhwM8kfYPZYjAGLOCYTg   	** cái này của khanglinh.com.vn
	//ABQIAAAAzEEPImDN04U85R0Qd6TgMhRI3HQxB2JBrDkWfHT3PiuXl_HzNBTwnnmQ1jW4IsAw_06YmWsXyEn99w   	** cái này của khanglinh.vietsol.net
	//ABQIAAAAzEEPImDN04U85R0Qd6TgMhQisnggLsfKGU-tN0QiuAhLRQWNwBQMQrZHZ4zMDnaicXSIrozmLfTQnA	** cái này của khanglinh.com:8080
	private $key = "ABQIAAAAzEEPImDN04U85R0Qd6TgMhRI3HQxB2JBrDkWfHT3PiuXl_HzNBTwnnmQ1jW4IsAw_06YmWsXyEn99w";
	private $address = "";
	public $result = "";
	
	/**
	 * @return the $this->key
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return the $this->address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param $this->key the $this->key to set
	 */
	public function setKey($key) {
		$this->key = $key;
	}

	/**
	 * @param $this->address the $this->address to set
	 */
	public function setAddress($address) {
		$this->address = urlencode ( $address);
	}

	function __construct($array = array()) {
		isset($array ['longitude'])?$this->longitude 	= $array ['longitude']:'';
		isset($array ['latitude'])?$this->latitude 		= $array ['latitude']:'';
		isset($array ['latitude'])?$this->latitude 		= $array ['latitude']:'';
		isset($array ['key'])?$this->key 				= $array ['key']:'';
		isset($array ['address'])?$this->address 		= $array ['address']:'';
	}
	/**
	 * @return the $longitude
	 */
	public function getLongitude() {
		return $this->longitude;
	}
	
	/**
	 * @return the $latitude
	 */
	public function getLatitude() {
		return $this->latitude;
	}
	
	/**
	 * @return the $precision
	 */
	public function getPrecision() {
		return $this->precision;
	}
	
	/**
	 * @param $longitude the $longitude to set
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}
	
	/**
	 * @param $latitude the $latitude to set
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
	}
	
	/**
	 * @param $precision the $precision to set
	 */
	public function setPrecision($precision) {
		$this->precision = $precision;
	}
	
	public function getCoordinate() {
//		$address = urlencode("columbia");
		$address = $this->address;
		$url = "http://maps.google.com/maps/geo?q=" . $address. "&output=csv&key=" . $this->key;
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_USERAGENT, $_SERVER ["HTTP_USER_AGENT"] );
//		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$data = curl_exec ( $ch );
		curl_close ( $ch );
		$this->result['status']=true;
		//Check our Response code to ensure success
		if (substr ( $data, 0, 3 ) == "200") {
			$data = explode ( ",", $data );
			$this->precision = $data [1];
			$this->latitude = $data [2];
			$this->longitude = $data [3];
		} else {
			$this->result['message']= "Error in geocoding! Http error " . substr ( $data, 0, 3 );
			$this->result['status']=false;
		}
	}

}
?>