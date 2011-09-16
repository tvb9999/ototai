<?php

switch ($_GET ['typecheck']) {
	case 'yahoo' :
		checkyahoo ();
		break;
	case 'skype' :
		checkskype ();
		break;
}

function checkskype() {
	print CheckSkyOnline ( $_GET ['nick'] );
}

function checkyahoo() {
	print CheckYahooOnline ( $_GET ['nick'] );
}

function CheckYahooOnline($yahooid) {
	$pageurl = "http://mail.opi.yahoo.com/online?u=$yahooid&m=a&t=1";
	if (function_exists ( 'curl_init' )) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_URL, $pageurl );
		$status = curl_exec ( $ch );
		if ($status == "01") {
			$online = true;
		} else {
			$online = false;
		}
		curl_close ( $ch );
	} else {
		$y = $read = "";
		$file = fopen ( $pageurl, "r" );
		$read = fread ( $file, 200 );
		$read = ereg_replace ( $yahooid, "", $read );
		if ($y = strstr ( $read, "00" )) {
			$online = false;
		} elseif ($y = strstr ( $read, "01" )) {
			$online = true;
		}
		fclose ( $file );
	}
	return $online;
}

function CheckSkyOnline($skyid) {
	$status = trim ( @file_get_contents ( "http://mystatus.skype.com/" . urlencode ( $skyid ) . ".num" ) );
	if ($status > 1)
		return 1;
	return 0;
}
?>