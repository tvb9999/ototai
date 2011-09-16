<?php

class VSFTextCode {
	var $convmap = array ();
	public $errors = array ();
	
	private $entities = false;
	
	public $method = 'internal';
	
	public $charsetPath = '';

	function __construct() {
	}

	function textcode() {
		$this->convmap = array (0xe1, 0xe1, 0x0, 0xe1, 0xc1, 0xc1, 0x0, 0xc1, 0xe0, 0xe0, 0x0, 0xe0, 0xc0, 0xc0, 0x0, 0xc0, 0x1ea3, 0x1ea3, 0x0, 0x1ea3, 0x1ea2, 0x1ea2, 0x0, 0x1ea2, 0xe3, 0xe3, 0x0, 0xe3, 0xc3, 0xc3, 0x0, 0xc3, 0x1ea1, 0x1ea1, 0x0, 0x1ea1, 0x1ea0, 0x1ea0, 0x0, 0x1ea0, 0x103, 0x103, 0x0, 0x103, 0x102, 0x102, 0x0, 0x102, 0x1eaf, 0x1eaf, 0x0, 0x1eaf, 0x1eae, 0x1eae, 0x0, 0x1eae, 0x1eb1, 0x1eb1, 0x0, 0x1eb1, 0x1eb0, 0x1eb0, 0x0, 0x1eb0, 0x1eb3, 0x1eb3, 0x0, 0x1eb3, 0x1eb2, 0x1eb2, 0x0, 0x1eb2, 0x1eb5, 0x1eb5, 0x0, 0x1eb5, 0x1eb4, 0x1eb4, 0x0, 0x1eb4, 0x1eb7, 0x1eb7, 0x0, 0x1eb7, 0x1eb6, 0x1eb6, 0x0, 0x1eb6, 0xe2, 0xe2, 0x0, 0xe2, 0xc2, 0xc2, 0x0, 0xc2, 0x1ea5, 0x1ea5, 0x0, 0x1ea5, 0x1ea4, 0x1ea4, 0x0, 0x1ea4, 0x1ea7, 0x1ea7, 0x0, 0x1ea7, 0x1ea6, 0x1ea6, 0x0, 0x1ea6, 0x1ea9, 0x1ea9, 0x0, 0x1ea9, 0x1ea8, 0x1ea8, 0x0, 0x1ea8, 0x1eab, 0x1eab, 0x0, 0x1eab, 0x1eaa, 0x1eaa, 0x0, 0x1eaa, 0x1ead, 0x1ead, 0x0, 0x1ead, 0x1eac, 0x1eac, 0x0, 0x1eac, 0xe9, 0xe9, 0x0, 0xe9, 0xc9, 0xc9, 0x0, 0xc9, 0xe8, 0xe8, 0x0, 0xe8, 0xc8, 0xc8, 0x0, 0xc8, 0x1ebb, 0x1ebb, 0x0, 0x1ebb, 0x1eba, 0x1eba, 0x0, 0x1eba, 0x1ebd, 0x1ebd, 0x0, 0x1ebd, 0x1ebc, 0x1ebc, 0x0, 0x1ebc, 0x1eb9, 0x1eb9, 0x0, 0x1eb9, 0x1eb8, 0x1eb8, 0x0, 0x1eb8, 0xea, 0xea, 0x0, 0xea, 0xca, 0xca, 0x0, 0xca, 0x1ebf, 0x1ebf, 0x0, 0x1ebf, 0x1ebe, 0x1ebe, 0x0, 0x1ebe, 0x1ec1, 0x1ec1, 0x0, 0x1ec1, 0x1ec0, 0x1ec0, 0x0, 0x1ec0, 0x1ec3, 0x1ec3, 0x0, 0x1ec3, 0x1ec2, 0x1ec2, 0x0, 0x1ec2, 0x1ec5, 0x1ec5, 0x0, 0x1ec5, 0x1ec4, 0x1ec4, 0x0, 0x1ec4, 0x1ec7, 0x1ec7, 0x0, 0x1ec7, 0x1ec6, 0x1ec6, 0x0, 0x1ec6, 0xed, 0xed, 0x0, 0xed, 0xcd, 0xcd, 0x0, 0xcd, 0xec, 0xec, 0x0, 0xec, 0xcc, 0xcc, 0x0, 0xcc, 0x1ec9, 0x1ec9, 0x0, 0x1ec9, 0x1ec8, 0x1ec8, 0x0, 0x1ec8, 0x128, 0x128, 0x0, 0x128, 0x129, 0x129, 0x0, 0x129, 0x1ecb, 0x1ecb, 0x0, 0x1ecb, 0x1eca, 0x1eca, 0x0, 0x1eca, 0xf3, 0xf3, 0x0, 0xf3, 0xd3, 0xd3, 0x0, 0xd3, 0xf2, 0xf2, 0x0, 0xf2, 0xd2, 0xd2, 0x0, 0xd2, 0x1ecf, 0x1ecf, 0x0, 0x1ecf, 0x1ece, 0x1ece, 0x0, 0x1ece, 0xf5, 0xf5, 0x0, 0xf5, 0xd5, 0xd5, 0x0, 0xd5, 0x1ecd, 0x1ecd, 0x0, 0x1ecd, 0x1ecc, 0x1ecc, 0x0, 0x1ecc, 0x1a1, 0x1a1, 0x0, 0x1a1, 0x1a0, 0x1a0, 0x0, 0x1a0, 0x1edb, 0x1edb, 0x0, 0x1edb, 0x1eda, 0x1eda, 0x0, 0x1eda, 0x1edd, 0x1edd, 0x0, 0x1edd, 0x1edc, 0x1edc, 0x0, 0x1edc, 0x1edf, 0x1edf, 0x0, 0x1edf, 0x1ede, 0x1ede, 0x0, 0x1ede, 0x1ee1, 0x1ee1, 0x0, 0x1ee1, 0x1ee0, 0x1ee0, 0x0, 0x1ee0, 0x1ee3, 0x1ee3, 0x0, 0x1ee3, 0x1ee2, 0x1ee2, 0x0, 0x1ee2, 0xf4, 0xf4, 0x0, 0xf4, 0xd4, 0xd4, 0x0, 0xd4, 0x1ed1, 0x1ed1, 0x0, 0x1ed1, 0x1ed0, 0x1ed0, 0x0, 0x1ed0, 0x1ed3, 0x1ed3, 0x0, 0x1ed3, 0x1ed2, 0x1ed2, 0x0, 0x1ed2, 0x1ed5, 0x1ed5, 0x0, 0x1ed5, 0x1ed4, 0x1ed4, 0x0, 0x1ed4, 0x1ed7, 0x1ed7, 0x0, 0x1ed7, 0x1ed6, 0x1ed6, 0x0, 0x1ed6, 0x1ed9, 0x1ed9, 0x0, 0x1ed9, 0x1ed8, 0x1ed8, 0x0, 0x1ed8, 0xfa, 0xfa, 0x0, 0xfa, 0xda, 0xda, 0x0, 0xda, 0xf9, 0xf9, 0x0, 0xf9, 0xd9, 0xd9, 0x0, 0xd9, 0x1ee7, 0x1ee7, 0x0, 0x1ee7, 0x1ee6, 0x1ee6, 0x0, 0x1ee6, 0x169, 0x169, 0x0, 0x169, 0x168, 0x168, 0x0, 0x168, 0x1ee5, 0x1ee5, 0x0, 0x1ee5, 0x1ee4, 0x1ee4, 0x0, 0x1ee4, 0x1b0, 0x1b0, 0x0, 0x1b0, 0x1af, 0x1af, 0x0, 0x1af, 0x1ee9, 0x1ee9, 0x0, 0x1ee9, 0x1ee8, 0x1ee8, 0x0, 0x1ee8, 0x1eeb, 0x1eeb, 0x0, 0x1eeb, 0x1eea, 0x1eea, 0x0, 0x1eea, 0x1eed, 0x1eed, 0x0, 0x1eed, 0x1eec, 0x1eec, 0x0, 0x1eec, 0x1eef, 0x1eef, 0x0, 0x1eef, 0x1eee, 0x1eee, 0x0, 0x1eee, 0x1ef1, 0x1ef1, 0x0, 0x1ef1, 0x1ef0, 0x1ef0, 0x0, 0x1ef0, 0xfd, 0xfd, 0x0, 0xfd, 0xdd, 0xdd, 0x0, 0xdd, 0x1ef3, 0x1ef3, 0x0, 0x1ef3, 0x1ef2, 0x1ef2, 0x0, 0x1ef2, 0x1ef7, 0x1ef7, 0x0, 0x1ef7, 0x1ef6, 0x1ef6, 0x0, 0x1ef6, 0x1ef9, 0x1ef9, 0x0, 0x1ef9, 0x1ef8, 0x1ef8, 0x0, 0x1ef8, 0x1ef5, 0x1ef5, 0x0, 0x1ef5, 0x1ef4, 0x1ef4, 0x0, 0x1ef4, 0x111, 0x111, 0x0, 0x111, 0x110, 0x110, 0x0, 0x110 );
	}

	function removeAccent($strAccent = "", $sepchar = "") {
		
		//Charachters must be in ASCII and certain ones aint allowed
		$text = html_entity_decode ( $strAccent );
		$text = preg_replace ( "/(ä|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text );
		$text = str_replace ( "ç", "c", $text );
		$text = preg_replace ( "/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $text );
		$text = preg_replace ( "/(ì|í|î|ị|ỉ|ĩ)/", 'i', $text );
		$text = preg_replace ( "/(ö|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $text );
		$text = preg_replace ( "/(ü|ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $text );
		$text = preg_replace ( "/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $text );
		$text = preg_replace ( "/(đ)/", 'd', $text );
		//CHU HOA
		$text = preg_replace ( "/(Ä|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $text );
		$text = str_replace ( "Ç", "C", $text );
		$text = preg_replace ( "/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $text );
		$text = preg_replace ( "/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $text );
		$text = preg_replace ( "/(Ö|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $text );
		$text = preg_replace ( "/(Ü|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $text );
		$text = preg_replace ( "/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $text );
		$text = preg_replace ( "/(Đ)/", 'D', $text );
		//Special string
		$text = preg_replace ( "/( |!|\"|#|$|%|')/", '-', $text );
		$text = preg_replace ( "/(̀|́|̉|$|>|-39)/", '', $text );
		$text = preg_replace ( "'<[\/\!]*?[^<>]*?>'si", "", $text );
		
		$text = str_replace ( " / ", "-", $text );
		$text = str_replace ( "/", "-", $text );
		$text = str_replace ( " - ", "-", $text );
		$text = str_replace ( "_", "-", $text );
		$text = str_replace ( " ", "-", $text );
		$text = str_replace ( "ß", "ss", $text );
		$text = str_replace ( "&", "", $text );
		$text = str_replace ( "%", "", $text );
		$text = ereg_replace ( "[^A-Za-z0-9-]", "", $text );
		
		$text = str_replace ( "----", "-", $text );
		$text = str_replace ( "---", "-", $text );
		$text = str_replace ( "--", "-", $text );
		
		$text = rtrim ( $text, '-' );
		if ($sepchar) {
			$text = str_replace ( "-", $sepchar, $text );
			$text = str_replace ( $sepchar . $sepchar, $sepchar, $text );
		}
		return $text;
	}

	function uniToDecimal($str) {
		return mb_encode_numericentity ( $str, $this->convmap, "UTF-8" );
	}

	function decimalToUni($str) {
		return mb_decode_numericentity ( $str, $this->convmap, "UTF-8" );
	}

	function cutString($string = "", $num = 20) {
		if (strlen ( $string ) > $num) {
			$result = substr ( $string, 0, $num ); //cut string with limited number
			$position = strrpos ( $result, " " ); //find position of last space
			if ($position)
				$result = substr ( $result, 0, $position ); //cut string again at last space if there are space in the result above    	
			$result .= ' ...';
		} else {
			$result = $string;
		}
		return $result;
	}

	/*-------------------------------------------------------------------------*/
	//
	// Create a random 8 character password 
	//
	/*-------------------------------------------------------------------------*/
	
	function make_password() {
		$pass = "";
		$chars = array ("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "a", "A", "b", "B", "c", "C", "d", "D", "e", "E", "f", "F", "g", "G", "h", "H", "i", "I", "j", "J", "k", "K", "l", "L", "m", "M", "n", "N", "o", "O", "p", "P", "q", "Q", "r", "R", "s", "S", "t", "T", "u", "U", "v", "V", "w", "W", "x", "X", "y", "Y", "z", "Z" );
		
		$count = count ( $chars ) - 1;
		
		srand ( ( double ) microtime () * 1000000 );
		
		for($i = 0; $i < 8; $i ++) {
			$pass .= $chars [rand ( 0, $count )];
		}
		
		return ($pass);
	}

	/*-------------------------------------------------------------------------*/
	// text_tidy:
	// Takes raw text from the DB and makes it all nice and pretty - which also
	// parses un-HTML'd characters. Use this with caution!         
	/*-------------------------------------------------------------------------*/
	
	function text_tidy($txt = "") {
		
		$trans = get_html_translation_table ( HTML_ENTITIES );
		$trans = array_flip ( $trans );
		
		$txt = strtr ( $txt, $trans );
		
		$txt = preg_replace ( "/\s{2}/", "&nbsp; ", $txt );
		$txt = preg_replace ( "/\r/", "\n", $txt );
		$txt = preg_replace ( "/\t/", "&nbsp;&nbsp;", $txt );
		//$txt = preg_replace( "/\\n/"   , "&#92;n"       , $txt );
		

		return $txt;
	
	}

	public function convertEncoding($string, $string_char_set, $destination_char_set = 'UTF-8') {
		$string_char_set = strtolower ( $string_char_set );
		$t = $string;
		
		if (is_numeric ( $t )) {
			return $t;
		}
		
		//-----------------------------------------
		// Did we pass a destination?
		//-----------------------------------------
		

		$destination_char_set = strtolower ( $destination_char_set );
		
		//-----------------------------------------
		// Not the same?
		//-----------------------------------------
		

		if ($destination_char_set == $string_char_set) {
			return $string;
		}
		
		if (! $string_char_set) {
			return $string;
		}
		
		if (! $t or $t == '') {
			return $string;
		}
		
		//-----------------------------------------
		// Do the convert - internally..
		//-----------------------------------------
		

		$method = "convertUsing_" . $this->method;
		$text = $this->$method ( $string, $string_char_set, $destination_char_set );
		
		return $text ? $text : $t;
	}

	/**
	 * Converts a text string from its current charset to a destination charset using mb_convert_encoding
	 *
	 * @access	private
	 * @param	string		Text string
	 * @param	string		Text string char set (original)
	 * @param	string		Desired character set (destination)
	 * @return	string		Converted string
	 */
	private function convertUsing_mb($string, $string_char_set, $destination_char_set = 'UTF-8') {
		if (function_exists ( 'mb_convert_encoding' )) {
			$encodings = array_map ( 'strtolower', mb_list_encodings () );
			
			if (in_array ( strtolower ( $destination_char_set ), $encodings ) and in_array ( strtolower ( $string_char_set ), $encodings )) {
				$text = mb_convert_encoding ( $string, $destination_char_set, $string_char_set );
			} else {
				$this->errors [] = "NO_MB_FUNCTION";
			}
		} else {
			$this->errors [] = "NO_MB_FUNCTION";
		}
		
		return $text ? $text : $string;
	}

	/**
	 * Converts a text string from its current charset to a destination charset using iconv
	 *
	 * @access	private
	 * @param	string		Text string
	 * @param	string		Text string char set (original)
	 * @param	string		Desired character set (destination)
	 * @return	string		Converted string
	 */
	private function convertUsing_iconv($string, $string_char_set, $destination_char_set = 'UTF-8') {
		if (function_exists ( 'iconv' )) {
			$text = iconv ( $string_char_set, $destination_char_set . '//TRANSLIT', $string );
		} else {
			$this->errors [] = "NO_ICONV_FUNCTION";
		}
		
		return $text ? $text : $string;
	}

	/**
	 * Converts a text string from its current charset to a destination charset using recode_string
	 *
	 * @access	private
	 * @param	string		Text string
	 * @param	string		Text string char set (original)
	 * @param	string		Desired character set (destination)
	 * @return	string		Converted string
	 */
	private function convertUsing_recode($string, $string_char_set, $destination_char_set = 'UTF-8') {
		if (function_exists ( 'recode_string' )) {
			$text = recode_string ( $string_char_set . '..' . $destination_char_set, $string );
		} else {
			$this->errors [] = "NO_RECODE_FUNCTION";
		}
		
		return $text ? $text : $string;
	}

	/**
	 * Converts a text string from its current charset to a destination charset using internal conversion class.
	 * The bulk of this function was written by Mikolaj Jedrzejak and used with permission via the License
	 *
	 * @access	private
	 * @param	string		Text string
	 * @param	string		Text string char set (original)
	 * @param	string		Desired character set (destination)
	 * @return	string		Converted string
	 */
	private function convertUsing_internal($string, $string_char_set, $destination_char_set = 'UTF-8') {
		$text = '';
		$original = $string;
		
		if (! $this->charsetPath) {
			$this->charsetPath = IPS_KERNEL_PATH . '/i18n/ConvertTables/';
		}
		
		/**
		 * This divison was made to prevent errors during convertion to/from utf-8 with
		 * "entities" enabled, because we need to use proper destination(to)/source(from)
		 * encoding table to write proper entities.
		 * 
		 * This is the first case. We are converting from 1byte chars...
		 **/
		if ($string_char_set != "utf-8") {
			/**
			 * Now build table with both charsets for encoding change. 
			 **/
			if ($destination_char_set != "utf-8") {
				$charsetTable = $this->_makeConversionTable ( $string_char_set, $destination_char_set );
			} else {
				$charsetTable = $this->_makeConversionTable ( $string_char_set );
			}
			
			if (! count ( $charsetTable )) {
				$this->errors [] = "Character set table not found";
				return $original;
			}
			
			/**
			 * For each char in a string... 
			 **/
			for($i = 0; $i < strlen ( $string ); $i ++) {
				$hexChar = "";
				$unicodeHexChar = "";
				$hexChar = strtoupper ( dechex ( ord ( $string [$i] ) ) );
				
				// This is fix from Mario Klingemann, it prevents
				// droping chars below 16 because of missing leading 0 [zeros]
				if (strlen ( $hexChar ) == 1) {
					$hexChar = "0" . $hexChar;
				}
				
				// This is quick fix of 10 chars in gsm0338
				// Thanks goes to Andrea Carpani who pointed on this problem and solve it ;)
				if (($string_char_set == "gsm0338") && ($hexChar == '1B')) {
					$i ++;
					$hexChar .= strtoupper ( dechex ( ord ( $string [$i] ) ) );
				}
				
				if ($destination_char_set != "utf-8") {
					if (in_array ( $hexChar, $charsetTable [$string_char_set] )) {
						$unicodeHexChar = array_search ( $hexChar, $charsetTable [$string_char_set] );
						$unicodeHexChars = explode ( "+", $unicodeHexChar );
						
						for($unicodeHexCharElement = 0; $unicodeHexCharElement < count ( $unicodeHexChars ); $unicodeHexCharElement ++) {
							if (array_key_exists ( $unicodeHexChars [$unicodeHexCharElement], $charsetTable [$destination_char_set] )) {
								if ($this->entities == true) {
									$text .= $this->_unicodeEntity ( $this->_hexToUtf ( $unicodeHexChars [$unicodeHexCharElement] ) );
								} else {
									$text .= chr ( hexdec ( $charsetTable [$destination_char_set] [$unicodeHexChars [$unicodeHexCharElement]] ) );
								}
							} else {
								$this->errors [] = "NO_CHAR_IN_DESTINATION: {$string[$i]}";
								return $original;
							}
						}
					} else {
						$this->errors [] = "NO_CHAR_IN_SOURCE: {$string[$i]}";
						return $original;
					}
				} else {
					if (in_array ( $hexChar, $charsetTable [$string_char_set] )) {
						$unicodeHexChar = array_search ( $hexChar, $charsetTable [$string_char_set] );
						$unicodeHexChars = explode ( "+", $unicodeHexChar );
						
						/**
						 * Sometimes there are two or more utf-8 chars per one regular char.
						 * Extream, example is polish old Mazovia encoding, where one char contains
						 * two lettes 007a (z) and 0142 (l slash), we need to figure out how to
						 * solve this problem.
						 * The letters are merge with "plus" sign, there can be more than two chars.
						 * In Mazowia we have 007A+0142, but sometimes it can look like this
						 * 0x007A+0x0142+0x2034 (that string means nothing, it just shows the possibility...)
						 **/
						for($unicodeHexCharElement = 0; $unicodeHexCharElement < count ( $unicodeHexChars ); $unicodeHexCharElement ++) {
							if ($this->entities == true) {
								$text .= $this->_unicodeEntity ( $this->_hexToUtf ( $unicodeHexChars [$unicodeHexCharElement] ) );
							} else {
								$text .= $this->_hexToUtf ( $unicodeHexChars [$unicodeHexCharElement] );
							}
						}
					} else {
						$this->errors [] = "NO_CHAR_IN_SOURCE: {$string[$i]}";
						return $original;
					}
				}
			}
		} 

		/**
		 * This is second case. We are encoding from multibyte char string. 
		 **/
		
		else if ($string_char_set == "utf-8") {
			$hexChar = "";
			$unicodeHexChar = "";
			$charsetTable = $this->_makeConversionTable ( $destination_char_set );
			
			if (is_array ( $charsetTable [$destination_char_set] ) and count ( $charsetTable [$destination_char_set] )) {
				foreach ( $charsetTable [$destination_char_set] as $unicodeHexChar => $hexChar ) {
					if ($this->entities == true) {
						$entityOrChar = $this->_unicodeEntity ( $this->_hexToUtf ( $unicodeHexChar ) );
					} else {
						$entityOrChar = chr ( hexdec ( $hexChar ) );
					}
					
					$string = str_replace ( $this->_hexToUtf ( $unicodeHexChar ), $entityOrChar, $string );
				}
			}
			
			$text = $string;
		}
		
		return $text ? $text : $original;
	}

	/**
	 * Convert unicode characters to unicode HTML entities
	 * 
	 * @access	private
	 * @param	string		$unicodeString		Input Unicode string (1 char can take more than 1 byte)
	 * @return	string		This is an input string also with unicode chars, but saved as entities
	 * @see		_hexToUtf()
	 */
	private function _unicodeEntity($unicodeString) {
		$outString = "";
		$stringLength = strlen ( $unicodeString );
		
		for($charPosition = 0; $charPosition < $stringLength; $charPosition ++) {
			$char = $unicodeString [$charPosition];
			$asciiChar = ord ( $char );
			
			if ($asciiChar < 128) //1 7 0bbbbbbb (127)
{
				$outString .= $char;
			} else if ($asciiChar >> 5 == 6) //2 11 110bbbbb 10bbbbbb (2047)
{
				$firstByte = ($asciiChar & 31);
				$charPosition ++;
				$char = $unicodeString [$charPosition];
				$asciiChar = ord ( $char );
				$secondByte = ($asciiChar & 63);
				$asciiChar = ($firstByte * 64) + $secondByte;
				
				$entity = sprintf ( "&#%d;", $asciiChar );
				$outString .= $entity;
			} else if ($asciiChar >> 4 == 14) //3 16 1110bbbb 10bbbbbb 10bbbbbb
{
				$firstByte = ($asciiChar & 31);
				$charPosition ++;
				$char = $unicodeString [$charPosition];
				$asciiChar = ord ( $char );
				$secondByte = ($asciiChar & 63);
				$charPosition ++;
				$char = $unicodeString [$charPosition];
				$asciiChar = ord ( $char );
				$thirdByte = ($asciiChar & 63);
				$asciiChar = ((($firstByte * 64) + $secondByte) * 64) + $thirdByte;
				
				$entity = sprintf ( "&#%d;", $asciiChar );
				$outString .= $entity;
			} else if ($asciiChar >> 3 == 30) //4 21 11110bbb 10bbbbbb 10bbbbbb 10bbbbbb
{
				$firstByte = ($asciiChar & 31);
				$charPosition ++;
				$char = $unicodeString [$charPosition];
				$asciiChar = ord ( $char );
				$secondByte = ($asciiChar & 63);
				$charPosition ++;
				$char = $unicodeString [$charPosition];
				$asciiChar = ord ( $char );
				$thirdByte = ($asciiChar & 63);
				$charPosition ++;
				$char = $unicodeString [$charPosition];
				$asciiChar = ord ( $char );
				$fourthByte = ($asciiChar & 63);
				$asciiChar = ((((($firstByte * 64) + $secondByte) * 64) + $thirdByte) * 64) + $fourthByte;
				
				$entity = sprintf ( "&#%d;", $asciiChar );
				$outString .= $entity;
			}
		}
		
		return $outString;
	}

	/**
	 * Convert unicode characters to unicode HTML entities.
	 * It is very similar to _unicodeEntity function (link below). There is one difference 
	 * in returned format. This time it's a regular char(s), in most cases it will be one or two chars. 
	 * 
	 * @access	private
	 * @param	string		$utfCharInHex	Hexadecimal value of a unicode char.
	 * @return	string		Encoded hexadecimal value as a regular char.
	 * @see		_unicodeEntity()
	 */
	private function _hexToUtf($utfCharInHex) {
		$outputChar = "";
		$utfCharInDec = hexdec ( $utfCharInHex );
		
		if ($utfCharInDec < 128) {
			$outputChar .= chr ( $utfCharInDec );
		} else if ($utfCharInDec < 2048) {
			$outputChar .= chr ( ($utfCharInDec >> 6) + 192 ) . chr ( ($utfCharInDec & 63) + 128 );
		} else if ($utfCharInDec < 65536) {
			$outputChar .= chr ( ($utfCharInDec >> 12) + 224 ) . chr ( (($utfCharInDec >> 6) & 63) + 128 ) . chr ( ($utfCharInDec & 63) + 128 );
		} else if ($utfCharInDec < 2097152) {
			$outputChar .= chr ( $utfCharInDec >> 18 + 240 ) . chr ( (($utfCharInDec >> 12) & 63) + 128 ) . chr ( ($utfCharInDec >> 6) & 63 + 128 ) . chr ( $utfCharInDec & 63 + 128 );
		}
		
		return $outputChar;
	}

	/**
	 * Create the conversion tables to use.
	 * 
	 * This function creates table with two SBCS (Single Byte Character Set). Every conversion is through this table.
	 * 
	 * - The file with encoding tables have to be save in "Format A" of unicode.org charset table format.
	 * - BOTH charsets MUST be SBCS
	 * - The files with encoding tables have to be complete (None of chars can be missing, unles you are sure you are not going to use it)
	 * 
	 * "Format A" encoding file, if you have to build it by yourself should aplly these rules:
	 * - you can comment everything with #
	 * - first column contains 1 byte chars in hex starting from 0x..
	 * - second column contains unicode equivalent in hex starting from 0x....
	 * - then every next column is optional, but in "Format A" it should contain unicode char name or/and your own comment
	 * - the columns can be splited by "spaces", "tabs", "," or any combination of these
	 * - below is an example
	 * 
	 * <code>
	 * #
	 * #	The entries are in ANSI X3.4 order.
	 * #
	 * 0x00	0x0000	#	NULL end extra comment, if needed
	 * 0x01	0x0001	#	START OF HEADING
	 * # Oh, one more thing, you can make comments inside of a rows if you like.
	 * 0x02	0x0002	#	START OF TEXT
	 * 0x03	0x0003	#	END OF TEXT
	 * next line, and so on...
	 * </code>
	 * 
	 * You can get full tables with encodings from http://www.unicode.org
	 * 
	 * @access	private
	 * @param	string		$firstEncoding		Name of first encoding and first encoding filename (thay have to be the same)
	 * @param	string		$secondEncoding		Name of second encoding and second encoding filename (thay have to be the same). Optional for building a joined table.
	 * @return	array 		Table necessary to change one encoding to another.
	 */
	private function _makeConversionTable($firstEncoding, $secondEncoding = "") {
		$convertTable = array ();
		
		for($i = 0; $i < func_num_args (); $i ++) {
			/**
			 * Because func_*** can't be used inside of another function call
			 * we have to save it as a separate value.
			 **/
			$fileName = func_get_arg ( $i );
			
			if (! is_file ( $this->charsetPath . $fileName )) {
				$this->errors [] = "FILE_NOT_FOUND: {$fileName}";
				return;
			}
			
			$fileWithEncTabe = fopen ( $this->charsetPath . $fileName, "r" ) or die (); //This die(); is just to make sure...
			

			while ( ! feof ( $fileWithEncTabe ) ) {
				/**
				 * We asume that line is not longer
				 * than 1024 which is the default value for fgets function 
				 **/
				if ($oneLine = trim ( fgets ( $fileWithEncTabe, 1024 ) )) {
					/**
					 * We don't need all comment lines. I check only for "#" sign, because
					 * this is a way of making comments by unicode.org in thair encoding files
					 * and that's where the files are from :-)
					 **/
					
					if (substr ( $oneLine, 0, 1 ) != "#") {
						/**
						 * Sometimes inside the charset file the hex walues are separated by
						 * "space" and sometimes by "tab", the below preg_split can also be used
						 * to split files where separator is a ",", "\r", "\n" and "\f"
						 **/
						$hexValue = preg_split ( "/[\s,]+/", $oneLine, 3 ); //We need only first 2 values
						

						/**
						 * Sometimes char is UNDEFINED, or missing so we can't use it for convertion
						 **/
						
						if (substr ( $hexValue [1], 0, 1 ) != "#") {
							$arrayKey = strtoupper ( str_replace ( strtolower ( "0x" ), "", $hexValue [1] ) );
							$arrayValue = strtoupper ( str_replace ( strtolower ( "0x" ), "", $hexValue [0] ) );
							
							$convertTable [func_get_arg ( $i )] [$arrayKey] = $arrayValue;
						}
					}
				}
			}
		}
		
		return $convertTable;
	}

}