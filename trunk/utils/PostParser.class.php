<?php
class PostParser {
	
	var $pp_do_html = 1;
	var $pp_nl2br = 0;
	var $pp_wordwrap = 0;

	function __set_state($array = array()) {
		$menu = new PostParser ();
		foreach ( $array as $key => $value ) {
			$menu->$key = $value;
		}
		return $menu;
	}

	//---------------------------------------------------------------
	// My strip-tags. Converts HTML entities back before strippin' em
	//---------------------------------------------------------------
	

	function my_strip_tags($t = "") {
		$t = str_replace ( "&#036;", "$", $t );
		$t = str_replace ( "&#124;", "|", $t );
		$t = str_replace ( "&amp;", "&", $t );
		$t = str_replace ( "&gt;", ">", $t );
		$t = str_replace ( "&lt;", "<", $t );
		$t = str_replace ( "&quot;", '"', $t );
		$t = str_replace ( "&#33;", "!", $t );
		
		return $t;
	}

	//--------------------------------------------------------------
	// Word wrap, wraps 'da word innit
	//--------------------------------------------------------------
	

	function my_wordwrap($t = "", $chrs = 0, $replace = "<br />") {
		if ($t == "")
			return $t;
		
		if ($chrs < 1)
			return $t;
		
		$t = preg_replace ( "#([^\s<>'\"/\.\\-\?&\n\r\%]{" . $chrs . "})#i", " \\1" . $replace, $t );
		return $t;
	}

	//--------------------------------------------------------------
	// Post DB parse tags
	//--------------------------------------------------------------
	

	function post_db_parse($t = "") {
		global $bw, $DB;
		if ($this->pp_do_html)
			$t = $this->post_db_parse_html ( $t );
		else
			$t = $this->my_strip_tags ( $t );
		if ($this->pp_wordwrap > 0)
			$t = $this->my_wordwrap ( $t, $this->pp_wordwrap );
		if (strstr ( $t, '[/' ))
			$t = $this->post_db_parse_bbcode ( $t );
		return $t;
	}

	//---------------------------------------------------------------
	// Post DB parse BBCode
	//---------------------------------------------------------------
	

	function post_db_parse_bbcode($t = "") {
		global $bw, $DB, $std;
		if (is_array ( $bw->cache ['bbcode'] ) and count ( $bw->cache ['bbcode'] )) {
			foreach ( $bw->cache ['bbcode'] as $i => $row ) {
				if (substr_count ( $row ['bbcode_replace'], '{content}' ) > 1) {
					if ($row ['bbcode_useoption']) {
						preg_match ( "#\[" . $row ['bbcode_tag'] . "=(?:&quot;|&\#39;)?(.+?)(?:&quot;|&\#39;)?\](.+?)\[/" . $row ['bbcode_tag'] . "\]#si", $t, $match );
						$row ['bbcode_replace'] = str_replace ( '{option}', $match [1], $row ['bbcode_replace'] );
						$row ['bbcode_replace'] = str_replace ( '{content}', $match [2], $row ['bbcode_replace'] );
						$t = preg_replace ( "#\[" . $row ['bbcode_tag'] . "=(?:.+?)\](?:.+?)\[/" . $row ['bbcode_tag'] . "\]#si", $row ['bbcode_replace'], $t );
					} else {
						preg_match ( "#\[" . $row ['bbcode_tag'] . "\](.+?)\[/" . $row ['bbcode_tag'] . "\]#si", $t, $match );
						$row ['bbcode_replace'] = str_replace ( '{content}', $match [1], $row ['bbcode_replace'] );
						$t = preg_replace ( "#\[" . $row ['bbcode_tag'] . "\](?:.+?)\[/" . $row ['bbcode_tag'] . "\]#si", $row ['bbcode_replace'], $t );
					}
				} else {
					$replace = explode ( '{content}', $row ['bbcode_replace'] );
					
					if ($row ['bbcode_useoption']) {
						$t = preg_replace ( "#\[" . $row ['bbcode_tag'] . "=(?:&quot;|&\#39;)?(.+?)(?:&quot;|&\#39;)?\]#si", str_replace ( '{option}', "\\1", $replace [0] ), $t );
					} else {
						$t = str_replace ( '[' . $row ['bbcode_tag'] . ']', $replace [0], $t );
					}
					
					$t = str_replace ( '[/' . $row ['bbcode_tag'] . ']', $replace [1], $t );
				}
			}
		}
		return $t;
	}

	//--------------------------------------------------------------
	// parse_html
	// Converts the doHTML tag
	//--------------------------------------------------------------
	

	function post_db_parse_html($t = "") {
		if ($t == "")
			return $t;
		
		if ($this->pp_nl2br != 1) {
			$t = str_replace ( "<br>", "\n", $t );
			$t = str_replace ( "<br />", "\n", $t );
		}
		
		$t = str_replace ( "&#39;", "'", $t );
		$t = str_replace ( "&#33;", "!", $t );
		$t = str_replace ( "&#036;", "$", $t );
		$t = str_replace ( "&#124;", "|", $t );
		$t = str_replace ( "&amp;", "&", $t );
		$t = str_replace ( "&gt;", ">", $t );
		$t = str_replace ( "&lt;", "<", $t );
		$t = str_replace ( "&quot;", '"', $t );
		//-------------------------------------
		// Take a crack at parsing some of the nasties
		// NOTE: THIS IS NOT DESIGNED AS A FOOLPROOF METHOD
		// AND SHOULD NOT BE RELIED UPON!
		//-------------------------------------
		

		//		$t = preg_replace( "/javascript/i" , "j&#097;v&#097;script", $t );
		$t = preg_replace ( "/alert/i", "&#097;lert", $t );
		$t = preg_replace ( "/about:/i", "&#097;bout:", $t );
		$t = preg_replace ( "/onmouseover/i", "&#111;nmouseover", $t );
		$t = preg_replace ( "/onclick/i", "&#111;nclick", $t );
		$t = preg_replace ( "/onload/i", "&#111;nload", $t );
		$t = preg_replace ( "/onsubmit/i", "&#111;nsubmit", $t );
		return $t;
	}

	function smilie_length_sort($a, $b) {
		if (strlen ( $a ['typed'] ) == strlen ( $b ['typed'] )) {
			return 0;
		}
		return (strlen ( $a ['typed'] ) > strlen ( $b ['typed'] )) ? - 1 : 1;
	}

	function word_length_sort($a, $b) {
		if (strlen ( $a ['type'] ) == strlen ( $b ['type'] )) {
			return 0;
		}
		return (strlen ( $a ['type'] ) > strlen ( $b ['type'] )) ? - 1 : 1;
	}

	function strip_only_tags($str, $tags, $stripContent = false) {
		$content = '';
		if (! is_array ( $tags )) {
			$tags = (strpos ( $str, '>' ) !== false ? explode ( '>', str_replace ( '<', '', $tags ) ) : array ($tags ));
			if (end ( $tags ) == '')
				array_pop ( $tags );
		}
		foreach ( $tags as $tag ) {
			if ($stripContent)
				$content = '(.+</' . $tag . '(>|\s[^>]*>)|)';
			$str = preg_replace ( '#</?' . $tag . '(>|\s[^>]*>)' . $content . '#is', '', $str );
		}
		return $str;
	}
}
?>