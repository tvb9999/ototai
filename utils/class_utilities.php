<?php
class class_ultilities {

	function __construct() {
		global $vsStd, $vsSkin;
		$vsStd->requireFile ( $vsSkin->obj->getFolder () . "/skin_utilities.php" );
		$this->html = new skin_utilities ();
	}

	/* For Capchar Only*/
	function supplyIdentifyId($type = 1) {
		global $DB;
		$temp = (60 * 10);
		$r_date = time () - $temp;
		
		$DB->simple_delete ( 'identify', 'identifyTime<' . $r_date ); // Remove old reg requests from the DB
		$DB->simple_exec ();
		
		$identifyId = md5 ( uniqid ( (microtime ()) ) ); // Set a new Id for this reg request
		mt_srand ( ( double ) microtime () * 1000000 );
		
		$identifyCode = mt_rand ( 100000, 999999 );
		if ($type) {
			$inputs = array ('length' => 6, 'uselower' => 1, 'useupper' => 1, 'usespecial' => 1, 'usenumbers' => 1 );
			$identifyCode = $this->supplyRandomString ( $inputs );
		}
		
		$identifyArray = array ('identifyId' => $identifyId, 'identifyCode' => $identifyCode, 'identifyTime' => time () );
		$DB->do_insert ( 'identify', $identifyArray );
		return $identifyId;
	}

	function supplyRandomString($inputs, $prefix = "") {
		$charset = "";
		$identifyCode = $prefix;
		
		$length = $inputs ['length'];
		srand ( ( double ) microtime () * rand ( 1000000, 9999999 ) );
		
		if ($inputs ['uselower'] == 1)
			$charset .= "abcdefghijkmnopqrstuvwxyz";
		if ($inputs ['useupper'] == 1)
			$charset .= "ABCDEFGHIJKLMNPQRSTUVWXYZ";
		if ($inputs ['usenumbers'] == 1)
			$charset .= "0123456789";
		
		while ( $length > 0 ) {
			$identifyCode .= $charset [rand ( 0, strlen ( $charset ) - 1 )];
			$length --;
		}
		
		return $identifyCode;
	}

	function supplyIdentifyCodeByIndentifyId($identifyId) {
		global $DB;
		$DB->simple_construct ( array ('select' => 'identifyCode', 'from' => 'identify', 'where' => "identifyId='{$identifyId}'" ) );
		$DB->simple_exec ();
		
		if (! $row = $DB->fetch_row ())
			return false;
		return $row ['identifyCode'];
	}

	/* End For Capchar Only*/
	
	/* For Weather and Currency Only*/
	function getWeatherFromVNExpress($location = 'HCM') {
		global $vsStd;
		$vsStd->requireFile ( UTILS_PATH . 'class_xml.php' );
		
		$myXML = new class_xml ();
		$xmlFile = "http://vnexpress.net/ListFile/Weather/{$location}.xml";
		
		$myXML->xml_parse_document ( file_get_contents ( $xmlFile ) );
		$array = current ( $myXML->xml_array );
		
		return $this->html->weatherFromVNExpressHTML ( $array );
	}

	function getWeatherFromGoogle($location = 'Ho Chi Minh', $language = 'vi') {
		global $vsStd;
		$vsStd->requireFile ( UTILS_PATH . 'class_xml.php' );
		
		$myXML = new class_xml ();
		$xmlFile = './docs/google_weather.xml';
		
		if (! file_exists ( $xmlFile )) {
			if (fopen ( $xmlFile, "w" )) {
				$location = str_replace ( ' ', '%20', $location );
				$current = utf8_encode ( file_get_contents ( "http://www.google.com.vn/ig/api?weather={$location}&hl={$language}" ) );
				file_put_contents ( $xmlFile, $current );
			} else
				return false;
		}
		
		$myXML->xml_parse_document ( file_get_contents ( $xmlFile ) );
		$array = current ( $myXML->xml_array );
		
		return $this->html->weatherFromGoogleHTML ( $array ['weather'] ['current_conditions'] );
	}

	function getCurrencyFromGoogleCode($option) {
		$script = 'up_fromcur=' . $option ['from'] . '&amp;up_tocur=' . $option ['to'] . '&amp;up_minimsg1=0&amp;synd=open&amp;';
		$script .= 'w=' . $option ['width'] . '&amp;h=' . $option ['height'] . '&amp;title=' . $option ['title'] . '&amp;lang=all&amp;country=ALL&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;';
		return <<<EOF
				<script src="http://www.gmodules.com/ig/ifr?url=http://www.pixelmedia.nl/gmodules/ucc.xml&amp;{$script}output=js"></script>
EOF;
	}

	function getCurrencyFromThanhNien() {
		$htmlFile = './docs/thanhnien.currency.html';
		
		if (! file_exists ( $htmlFile )) {
			if (fopen ( $htmlFile, "w" )) {
				$current = (file_get_contents ( "http://www.thanhnien.com.vn/_layouts/NgoaiTe.aspx" ));
				file_put_contents ( $htmlFile, $current );
			} else
				return false;
		}
		
		$currencyContent = file_get_contents ( $htmlFile );
		
		$begin = strpos ( $currencyContent, '<div id="PgTable" class="pageview">' );
		$end = strpos ( $currencyContent, '<div id="PgChart" class="pageview" style="display:none;">' );
		$currencyContent = substr ( $currencyContent, $begin, $end - $begin );
		
		return $this->html->currencyFromThanhNienHTML ( $currencyContent );
	}

	function getCurrencyFromVNExpress() {
		return $this->html->currencyFromVNExpressHTML ();
	}

	function getCurrencyFormVietcombank() {
		global $vsStd;
		$vsStd->requireFile ( UTILS_PATH . 'class_xml.php' );
		
		$myXML = new class_xml ();
		$xmlFile = "http://www.vietcombank.com.vn/ExchangeRates/ExrateXML.aspx";
		
		$myXML->xml_parse_document ( file_get_contents ( $xmlFile ) );
		$array = current ( $myXML->xml_array );
		
		$option ['time'] = $array ['DateTime'] ['VALUE'];
		
		foreach ( $array ['Exrate'] as $element )
			$option ['content'] .= $this->html->currencyFromVietcombankHTML ( $element );
		
		return $this->html->currencyFromVietcombankContainerHTML ( $option );
	}

	function getGoldFormVNExpress() {
		return $this->html->goldFromVNExpressHTML ();
	}

	function getGoldFormThanhNien() {
		$htmlFile = './docs/thanhnien.gold.html';
		
		if (! file_exists ( $htmlFile )) {
			if (fopen ( $htmlFile, "w" )) {
				$current = (file_get_contents ( "http://www.thanhnien.com.vn/_layouts/giavang.aspx" ));
				file_put_contents ( $htmlFile, $current );
			} else
				return false;
		}
		
		$currencyContent = file_get_contents ( $htmlFile );
		return $this->html->goldFromThanhNienHTML ( $currencyContent );
	
	}
	/* For Weather and Currency Only*/

}
?>