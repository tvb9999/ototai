<?php
/*
 +-----------------------------------------------------------------------------
 |   VS FRAMEWORK 3.0.0
 |	Author: BabyWolf
 |	Homepage: http://vietsol.net
 |	If you use this code, please don't delete these comment line!
 |	Start Date: 21/09/2004
 |	Finish Date: 22/09/2004
 |	Version 2.0.0 Start Date: 07/02/2007
 |	Version 3.0.0 Start Date: 03/29/2009
 +-----------------------------------------------------------------------------
 */

class VSFFunction {
	
	var $time_formats = array ();
	var $time_options = array ();
	var $offset = "";
	var $offset_set = 0;
	var $num_format = "";
	var $allow_unicode = 1;
	var $get_magic_quotes = 0;
	var $today_array = array ();

	// Set up some standards to save CPU later
	

	function __construct() {
		global $bw;
		
		$this->time_options = array ('JOINED' => isset ( $bw->vars ['clock_joined'] ) ? $bw->vars ['clock_joined'] : "", 'SHORT' => isset ( $bw->vars ['clock_short'] ) ? $bw->vars ['clock_short'] : "", 'LONG' => isset ( $bw->vars ['clock_long'] ) ? $bw->vars ['clock_long'] : "" );
		
		if (! isset ( $bw->vars ['number_format'] ))
			$bw->vars ['number_format'] = "";
		$this->num_format = $bw->vars ['number_format'] == 'space' ? ' ' : $bw->vars ['number_format'];
		
		$this->get_magic_quotes = get_magic_quotes_gpc ();
	
	}

	/**
	 * Load a file to system
	 *
	 * @param string $filePath
	 * @param bool[optional] $requireOnce
	 * @param bool[optional] $bypass
	 */
	function requireFile($filePath = "", $requireOnce = true, $bypass = false) {
		global $vsLang;
		if (! file_exists ( $filePath )) {
			if ($bypass)
				return false;
			return false;
			throw new Exception ( sprintf ( 'The file <b>%s</b> does not exist!', $filePath ) );
		}
		$this->array_file_exist [$filePath] = true;
		if ($requireOnce) {
			try {
				require_once ($filePath);
			} catch ( Exception $e ) {
				print "error require_once: " . $e;
			}
		} else {
			try {
				require ($filePath);
			} catch ( Exception $e ) {
				print "error require: " . $e;
			}
		}
		return true;
	}

	/*-------------------------------------------------------------------------*/
	//
	// MY DECONSTRUCTOR
	//
	/*-------------------------------------------------------------------------*/
	
	function my_deconstructor() {
		global $bw, $vsStd, $DB;
		//--------------------------------
		// Any shutdown queries
		//--------------------------------
		$DB->return_die = 0;
		if (count ( $DB->obj ['shutdown_queries'] )) {
			foreach ( $DB->obj ['shutdown_queries'] as $q ) {
				$DB->query ( $q );
			}
		}
		$DB->return_die = 1;
		$DB->obj ['shutdown_queries'] = array ();
		$DB->close_db ();
	}

	/*-------------------------------------------------------------------------*/
	// txt_htmlspecialchars
	// ------------------
	// Custom version of htmlspecialchars to take into account mb chars
	/*-------------------------------------------------------------------------*/
	
	function txt_htmlspecialchars($t = "") {
		// Use forward look up to only convert & not &#123;
		$t = preg_replace ( "/&(?!#[0-9]+;)/s", '&amp;', $t );
		$t = str_replace ( "<", "&lt;", $t );
		$t = str_replace ( ">", "&gt;", $t );
		$t = str_replace ( '"', "&quot;", $t );
		$t = str_replace ( "'", '&#039;', $t );
		
		return $t; // A nice cup of?
	}

	/*-------------------------------------------------------------------------*/
	// Sets a cookie, abstract layer allows us to do some checking, etc
	/*-------------------------------------------------------------------------*/
	
	function my_setcookie($name, $value = "", $sticky = 1) {
		global $bw;
		if ($bw->no_print_header)
			return;
		if ($sticky == 1)
			$expires = time () + 60 * 60 * 24 * 365;
		
		$bw->vars ['cookie_domain'] = $bw->vars ['cookie_domain'] == "" ? "" : $bw->vars ['cookie_domain'];
		$bw->vars ['cookie_path'] = $bw->vars ['cookie_path'] == "" ? "/" : $bw->vars ['cookie_path'];
		$name = $bw->vars ['cookie_id'] . $name;
		
		@setcookie ( $name, $value, $expires, $bw->vars ['cookie_path'], $bw->vars ['cookie_domain'] );
	}

	/*-------------------------------------------------------------------------*/
	// Cookies, cookies everywhere and not a byte to eat.
	/*-------------------------------------------------------------------------*/
	
	function my_getcookie($name) {
		global $bw;
		
		if (isset ( $_COOKIE [$bw->vars ['cookie_id'] . $name] )) {
			return urldecode ( $_COOKIE [$bw->vars ['cookie_id'] . $name] );
		} else {
			return FALSE;
		}
	
	}

	/*-------------------------------------------------------------------------*/
	// Makes incoming info "safe"
	/*-------------------------------------------------------------------------*/
	
	function parse_incoming() {
		global $HTTP_X_FORWARDED_FOR, $HTTP_PROXY_USER, $HTTP_CLIENT_IP;
		
		$this->get_magic_quotes = get_magic_quotes_gpc ();
		$return = array ();
		if (is_array ( $_GET )) {
			while ( list ( $k, $v ) = each ( $_GET ) ) {
				if (is_array ( $_GET [$k] ))
					while ( list ( $k2, $v2 ) = each ( $_GET [$k] ) )
						$return [$this->clean_key ( $k )] [$this->clean_key ( $k2 )] = $this->clean_value ( $v2 );
				else
					$return [$this->clean_key ( $k )] = $this->clean_value ( $v );
			}
		}
		//----------------------------------------
		// Overwrite GET data with post data
		//----------------------------------------
		if (is_array ( $_POST )) {
			while ( list ( $k, $v ) = each ( $_POST ) ) {
				if (is_array ( $_POST [$k] )) {
					while ( list ( $k2, $v2 ) = each ( $_POST [$k] ) ) {
						$return [$this->clean_key ( $k )] [$this->clean_key ( $k2 )] = $this->clean_value ( $v2 );
					}
				} else {
					$return [$this->clean_key ( $k )] = $this->clean_value ( $v );
				}
			}
		}
		//----------------------------------------
		// Sort out the accessing IP
		// (Thanks to Cosmos and schickb)
		//----------------------------------------
		$addrs = array ();
		foreach ( array_reverse ( explode ( ',', $HTTP_X_FORWARDED_FOR ) ) as $x_f ) {
			$x_f = trim ( $x_f );
			
			if (preg_match ( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $x_f )) {
				$addrs [] = $x_f;
			}
		}
		$addrs [] = $_SERVER ['REMOTE_ADDR'];
		$addrs [] = $HTTP_PROXY_USER;
		$addrs [] = $HTTP_CLIENT_IP;
		$return ['IP_ADDRESS'] = $this->select_var ( $addrs );
		
		// Make sure we take a valid IP address
		

		$return ['IP_ADDRESS'] = preg_replace ( "/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", "\\1.\\2.\\3.\\4", $return ['IP_ADDRESS'] );
		
		$return ['request_method'] = strtolower ( $_SERVER ['REQUEST_METHOD'] );
		if ($return ['ajax'])
			$return = $this->parse_ajax ( $return );
		return $return;
	}

	/*-------------------------------------------------------------------------*/
	// parse Ajax Cleaner - ensures no funny business with form elements
	/*-------------------------------------------------------------------------*/
	
	function parse_ajax($array = array()) {
		$arrTem = explode ( '&amp;', $array ['vs'] );
		$array ['vs'] = $arrTem [0];
		$count = count ( $arrTem );
		if ($count > 1) {
			for($i = 1; $i < $count; $i ++) {
				$exTem = explode ( '=', $arrTem [$i] );
				$array [$exTem [0]] = $exTem [1];
			}
		}
		return $array;
	}

	/*-------------------------------------------------------------------------*/
	// Key Cleaner - ensures no funny business with form elements
	/*-------------------------------------------------------------------------*/
	
	function clean_key($key) {
		
		if ($key == "") {
			return "";
		}
		$key = preg_replace ( "/\.\./", "", $key );
		$key = preg_replace ( "/\_\_(.+?)\_\_/", "", $key );
		$key = preg_replace ( "/^([\w\.\-\_]+)$/", "$1", $key );
		
		return $key;
	}

	/*-------------------------------------------------------------------------*/
	// Clean evil tags
	/*-------------------------------------------------------------------------*/
	
	function clean_evil_tags($t) {
		$t = preg_replace ( "/javascript/i", "j&#097;v&#097;script", $t );
		$t = preg_replace ( "/alert/i", "&#097;lert", $t );
		$t = preg_replace ( "/about:/i", "&#097;bout:", $t );
		$t = preg_replace ( "/onmouseover/i", "&#111;nmouseover", $t );
		$t = preg_replace ( "/onclick/i", "&#111;nclick", $t );
		$t = preg_replace ( "/onload/i", "&#111;nload", $t );
		$t = preg_replace ( "/onsubmit/i", "&#111;nsubmit", $t );
		$t = preg_replace ( "/<body/i", "&lt;body", $t );
		$t = preg_replace ( "/<html/i", "&lt;html", $t );
		$t = preg_replace ( "/document\./i", "&#100;ocument.", $t );
		
		return $t;
	}

	/*-------------------------------------------------------------------------*/
	// Clean value
	/*-------------------------------------------------------------------------*/
	
	function clean_value($val) {
		global $bw;
		
		if ($val == "")
			return "";
		$val = str_replace ( "&#032;", " ", $val );
		if (isset ( $bw->vars ['strip_space_chr'] ) && $bw->vars ['strip_space_chr'])
			$val = str_replace ( chr ( 0xCA ), "", $val ); //Remove sneaky spaces
		

		$val = str_replace ( "&", "&amp;", $val );
		$val = str_replace ( "<!--", "&#60;&#33;--", $val );
		$val = str_replace ( "-->", "--&#62;", $val );
		$val = preg_replace ( "/<script/i", "&#60;script", $val );
		$val = str_replace ( ">", "&gt;", $val );
		$val = str_replace ( "<", "&lt;", $val );
		$val = str_replace ( "\"", "&quot;", $val );
		$val = preg_replace ( "/\n/", "<br />", $val ); // Convert literal newlines
		$val = preg_replace ( "/\\\$/", "&#036;", $val );
		$val = preg_replace ( "/\r/", "", $val ); // Remove literal carriage returns
		$val = str_replace ( "!", "&#33;", $val );
		$val = str_replace ( "'", "&#39;", $val ); // IMPORTANT: It helps to increase sql query safety.
		

		// Ensure unicode chars are OK
		if ($this->allow_unicode)
			$val = preg_replace ( "/&amp;#([0-9]+);/s", "&#\\1;", $val );
		
		// Strip slashes if not already done so.
		if ($this->get_magic_quotes)
			$val = stripslashes ( $val );
		
		// Swop user inputted backslashes
		

		$val = preg_replace ( "/\\\(?!&amp;#|\?#)/", "&#092;", $val );
		return $val;
	}

	function remove_tags($text = "") {
		// Removes < BOARD TAGS > from posted forms
		

		$text = preg_replace ( "/(<|&lt;)% (BOARD HEADER|CSS|JAVASCRIPT|TITLE|BOARD|STATS|GENERATOR|COPYRIGHT|NAVIGATION) %(>|&gt;)/i", "&#60;% \\2 %&#62;", $text );
		
		//$text = str_replace( "<%", "&#60;%", $text );
		

		return $text;
	}

	/*-------------------------------------------------------------------------*/
	// Variable chooser
	/*-------------------------------------------------------------------------*/
	
	function select_var($array) {
		if (! is_array ( $array ))
			return - 1;
		ksort ( $array );
		$chosen = - 1; // Ensure that we return zero if nothing else is available
		foreach ( $array as $k => $v ) {
			if (isset ( $v )) {
				$chosen = $v;
				break;
			}
		}
		return $chosen;
	}

	//This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
	function let_to_num($v) {
		$l = substr ( $v, - 1 );
		$ret = substr ( $v, 0, - 1 );
		switch (strtoupper ( $l )) {
			case 'P' :
				$ret *= 1024;
			case 'T' :
				$ret *= 1024;
			case 'G' :
				$ret *= 1024;
			case 'M' :
				$ret *= 1024;
			case 'K' :
				$ret *= 1024;
				break;
		}
		return $ret;
	}

	function rs_unique_filename($dir, $filename, $ext) {
		
		// edge case: if file is named '.ext', treat as an empty name
		$ext = ".".$ext;
		if ($filename === $ext)
			$filename = '';
		$filename = $filename . $ext;
		// Increment the file number until we have a unique file to save in $dir. Use callback if supplied.
		

		$number = '';
		
		// change '.ext' to lower case
		if ($ext && strtolower ( $ext ) != $ext) {
			$ext2 = strtolower ( $ext );
			$filename2 = preg_replace ( '|' . preg_quote ( $ext ) . '$|', $ext2, $filename );
			
			// check for both lower and upper case extension or image sub-sizes may be overwritten
			while ( file_exists ( $dir . "/$filename" ) || file_exists ( $dir . "/$filename2" ) ) {
				$new_number = $number + 1;
				$filename = str_replace ( "$number$ext", "$new_number$ext", $filename );
				$filename2 = str_replace ( "$number$ext2", "$new_number$ext2", $filename2 );
				$number = $new_number;
			}
			return $filename2;
		}
		
		while ( file_exists ( $dir . "/$filename" ) ) {
			if ('' == "$number$ext")
				$filename = $filename . ++ $number . $ext;
			else
				$filename = str_replace ( "$number$ext", ++ $number . $ext, $filename );
		}
		$filename = str_replace ( $ext, '', $filename );
		return $filename;
	}
}

