<?php
class class_url {
	private $protocol;
	private $port;
	private $serverName;
	private $URI;
	private $currentURL;
	private $lenURL;

	public function __construct($protocol, $port, $serverName, $URI) {
		$this->protocol = $protocol;
		$this->port = $port;
		$this->serverName = $serverName;
		$this->URI = $URI;
	}

	public function __destruct() {
		unset ( $this->protocol );
		unset ( $this->port );
		unset ( $this->serverName );
		unset ( $this->URI );
		unset ( $this->currentURL );
	}

	/**
	 * @return unknown
	 */
	public function getCurrentURL() {
		return $this->currentURL = $this->protocol . "://" . $this->serverName . $this->port . $this->URI;
	}

	/**
	 * @param unknown_type $currentURL
	 */
	public function setCurrentURL($currentURL) {
		$this->currentURL = $currentURL;
	}

	/**
	 * @return unknown
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * @return unknown
	 */
	public function getProtocol() {
		return $this->protocol;
	}

	/**
	 * @return unknown
	 */
	public function getServerName() {
		return $this->serverName;
	}

	/**
	 * @return unknown
	 */
	public function getURI() {
		return $this->URI;
	}

	/**
	 * @return unknown
	 */
	public function getLenURL() {
		return $this->lenURL = strlen ( $this->currentURL );
	}

	/**
	 * @param unknown_type $port
	 */
	public function setPort($port) {
		$this->port = $port;
	}

	/**
	 * @param unknown_type $protocol
	 */
	public function setProtocol($protocol) {
		$this->protocol = $protocol;
	}

	/**
	 * @param unknown_type $serverName
	 */
	public function setServerName($serverName) {
		$this->serverName = $serverName;
	}

	/**
	 * @param unknown_type $URI
	 */
	public function setURI($URI) {
		$this->URI = $URI;
	}

	/**
	 * @param unknown_type $currentURL
	 */
	public function setLenURL($lenURL) {
		$this->lenURL = $lenURL;
	}

	public function getArrayURI() {
		return explode ( "/", $this->getURI () );
	}

	public function getAllRequestParam() {
		$postionFirstQuestionMark = strpos ( $this->URI, "?" );
		return explode ( "&", substr ( $this->URI, $postionFirstQuestionMark + 1, strlen ( $this->URI ) ) );
	}

	public function getRequestParamByIndex($index) {
		$arr = ($this->getAllRequestParam ());
		$param = $arr [$index];
		return $param;
	}

	public function getRequestParamByName($name) {
		$positionFirstName = strpos ( $this->URI, $name );
		if ($positionFirstName == 0) {
			return false;
		}
		$lenName = strlen ( $name );
		$subStr = substr ( $this->URI, $positionFirstName + $lenName + 1, strlen ( $this->URI ) - ($positionFirstName + $lenName + 1) );
		$lenName = strlen ( $subStr );
		$count = 0;
		while ( $lenName > 0 ) {
			$count ++;
			if (substr ( $subStr, $count, 1 ) == "&")
				break;
			$lenName --;
		}
		$value = substr ( $subStr, 0, $count );
		$param = "{$name}={$value}";
		return $param;
	}

	public function setRequestParamByIndex($position, $value) {
		$param = $this->getRequestParamByIndex ( $position );
		$name = substr ( $param, 0, strpos ( $param, "=" ) );
		$this->URI = stri_replace ( $param, "{$name}={$value}", $this->URI );
	}

	public function setRequestParamByName($name, $value) {
		$param = $this->getRequestParamByName ( $name );
		$this->URI = str_replace ( $param, "{$name}={$value}", $this->URI );
	}

	function addRequestParam($param) {
		$posiionQuestionMark = strpos ( $this->URI, "?" );
		$valueNextPositionQuestionMark = substr ( $this->URI, $posiionQuestionMark, 1 );
		if ($positionQuestionMark)
			if ($valueNextPositionQuestionMark)
				$this->URI .= "&{$param}";
			else
				$this->URI .= "{$param}";
		else
			$this->URI .= "?{$param}";
		return $this->URI;
	}

	public function __toString() {
		return $this->protocol . "://" . $this->serverName . $this->port . $this->URI;
	}
}
$s = empty ( $_SERVER ["HTTPS"] ) ? "" : ($_SERVER ["HTTPS"] == "on") ? "s" : "";
$protocol = substr ( strtolower ( $_SERVER ["SERVER_PROTOCOL"] ), 0, strpos ( strtolower ( $_SERVER ["SERVER_PROTOCOL"] ), "/" ) ) . $s;
$port = ($_SERVER ["SERVER_PORT"] == '80') ? "" : (':' . $_SERVER ["SERVER_PORT"]);
$url = new class_url ( $protocol, $port, $_SERVER ['SERVER_NAME'], $_SERVER ['REQUEST_URI'] );

//print "<br>";
//print_r ("get protocol:". $url->__toString());
//print "</br>";
//
//print "<br>";
//print_r ("get port:". $url->getPort());
//print "</br>";
//
//print "<br>";
//print_r ("get server name:". $url->getServerName());
//print "</br>";
//
//print "<br>";
//print_r ("get URI:". $url->getURI());
//print "</br>";
//
//print "<br>";
//print_r ("get currentURL:". $url->getCurrentURL());
//print "</br>";


//print "<br>";
// $url->addRequestParam( 'key_pages=1999999999999999999999999' ) ;
//print "</br>";


//print "<br>";
//print_r ("get req param key_pages:". $url->getRequestParamByName ( 'key_pages' ) );
//print "</br>";
//
//print "<br>";
//print_r ( "get Current URl:". $url->getCurrentURL());
//print "</br>";


//print "<br>";
// $url->setRequestParamByName ( 'key_pages',1234567890) ;
//print "</br>";


//print "<br>";
//print_r ( "get Current URl:". $url->getCurrentURL());
//print "</br>";
//$a = array ("a" => 3, "b" => 1, "c" => 5, "d" => 7, "e" => 8 );
//echo $a [1];
?>