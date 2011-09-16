<?php

class class_email {

	function clean_email($email = "") {
		
		$email = trim ( $email );
		
		$email = str_replace ( " ", "", $email );
		
		$email = preg_replace ( "#[\;\#\n\r\*\'\"<>&\%\!\(\)\{\}\[\]\?\\/\s]#", "", $email );
		
		if (preg_match ( "/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?)$/", $email )) {
			return $email;
		} else {
			return FALSE;
		}
	}

	function checkEmail($email) {
		// checks proper syntax
		if (! preg_match ( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email )) {
			return false;
		}
		
		// gets domain name
		list ( $username, $domain ) = split ( '@', $email );
		// checks for if MX records in the DNS
		$mxhosts = array ();
		if (! getmxrr ( $domain, $mxhosts )) {
			// no mx records, ok to check domain
			if (! fsockopen ( $domain, 25, $errno, $errstr, 30 )) {
				return false;
			} else {
				return true;
			}
		} else {
			// mx records found
			foreach ( $mxhosts as $host ) {
				if (fsockopen ( $host, 25, $errno, $errstr, 30 )) {
					return true;
				}
			}
			return false;
		}
	}

	function email_is_valid($email) {
		global $bw;
		if (substr_count ( $email, '@' ) != 1)
			return false;
		if ($email {0} == '@')
			return false;
		if (substr_count ( $email, '.' ) < 1)
			return false;
		if (strpos ( $email, '..' ) !== false)
			return false;
		$length = strlen ( $email );
		for($i = 0; $i < $length; $i ++) {
			$c = $email {$i};
			if ($c >= 'A' && $c <= 'Z')
				continue;
			if ($c >= 'a' && $c <= 'z')
				continue;
			if ($c >= '0' && $c <= '9')
				continue;
			if ($c == '@' || $c == '.' || $c == '_' || $c == '-')
				continue;
			return false;
		}
		$TLD = array ('COM', 'NET', 'ORG', 'MIL', 'EDU', 'GOV', 'BIZ', 'NAME', 'MOBI', 'INFO', 'AERO', 'JOBS', 'MUSEUM' );
		$tld = strtoupper ( substr ( $email, strrpos ( $email, '.' ) + 1 ) );
		if (strlen ( $tld ) != 2 && ! in_array ( $tld, $TLD ))
			return false;
		if ($bw->vars ['checkservermail'])
			return $this->checkEmail ( $email );
		return true;
	}

}

?>