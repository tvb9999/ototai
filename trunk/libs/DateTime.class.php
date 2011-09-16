<?php
class VSFDateTime {

	function __construct() {
	}

	function __destruct() {
	}

	/*-------------------------------------------------------------------------*/
	// Get Date By BabyWolf (Simple)
	/*-------------------------------------------------------------------------*/
	function getDate($date, $method = "SHORT", $standard = false) {
		global $bw, $vsLang;
		
		$daytext = array ('Mon' => $vsLang->getWords ( 'global_d_mon', 'Monday' ), 'Tue' => $vsLang->getWords ( 'global_d_tue', 'Tuesday' ), 'Wed' => $vsLang->getWords ( 'global_d_wed', 'Wednesday' ), 'Thu' => $vsLang->getWords ( 'global_d_thu', 'Thursday' ), 'Fri' => $vsLang->getWords ( 'global_d_fri', 'Friday' ), 'Sat' => $vsLang->getWords ( 'global_d_sat', 'Saturday' ), 'Sun' => $vsLang->getWords ( 'global_d_sun', 'Sunday' ) );
		
		$date += $bw->vars ['TimeZone'] * 3600;
		
		if ($method == "")
			$method = "LONG";
		
		if ($method == "SHORT")
			$method = 'd/m/Y';
		
		if ($method == "LONG")
			$method = 'D d/m/Y h:i:s';
		
		if ($method == "VN_LONG")
			$method = 'g:i d/m/Y';
		
		$result = gmdate ( $method, $date );
		$result = strtr ( $result, $daytext );
		
		if ($standard)
			$result .= " GMT+(" . isset ( $bw->vars ['global_servertimezone'] ) ? $bw->vars ['global_servertimezone'] : '7' . ")";
		
		return $result;
	}

	function GetRemainMonth($expireddate = 0) {
		$cmonth = $this->GetDate ( time (), 'm' );
		$cyear = $this->GetDate ( time (), 'Y' );
		
		$emonth = $this->GetDate ( $expireddate, 'm' );
		$eyear = $this->GetDate ( $expireddate, 'Y' );
		
		$remainmonth = ($eyear - $cyear) * 12 + ($emonth - $cmonth);
		
		return $remainmonth;
	}

	function TimeToInt() {
		return gmmktime ( $this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year );
	}

	//---------------------------------------------
	// Kiem tra ngay thang
	//----------------------------------------------
	function checkMonthLength($mm, $dd) {
		if (($mm == 4 || $mm == 6 || $mm == 9 || $mm == 11) && $dd > 30) {
			return false;
		} else if ($dd > 31) {
			return false;
		}
		return true;
	}

	function checkLeapMonth($mm, $dd, $yyyy) {
		if ($yyyy % 4 > 0 && $dd > 28) {
			return false;
		} else if ($dd > 29) {
			return false;
		}
		return true;
	}

	function isDate($inputStr) {
		if (strpos ( $inputStr, "-" ) != - 1) {
			$inputStr = str_replace ( "-", "/", $inputStr );
		}
		
		$delim1 = strpos ( $inputStr, "/" );
		$delim2 = strrpos ( $inputStr, "/" );
		if ($delim1 != - 1 && $delim1 == $delim2) {
			return false;
		}
		
		if ($delim1 != - 1) {
			$dd = substr ( $inputStr, 0, $delim1 );
			$mm = substr ( $inputStr, $delim1 + 1, $delim2 - $delim1 - 1 );
			$yyyy = substr ( $inputStr, $delim2 + 1, strlen ( $inputStr ) - $delim2 - 1 );
		} else {
			$dd = substr ( $inputStr, 0, 2 );
			$mm = substr ( $inputStr, 2, 4 );
			$yyyy = substr ( $inputStr, 4, strlen ( $inputStr ) );
		}
		if (! intval ( $dd ) || ! intval ( $mm ) || ! intval ( $yyyy )) {
			return false;
		}
		if ($mm < 1 || $mm > 12) {
			return false;
		}
		if ($dd < 1 || $dd > 31) {
			return false;
		}
		if ($yyyy < 100) {
			if ($yyyy >= 30)
				$yyyy += 1900;
			else
				$yyyy += 2000;
		}
		if (! $this->checkMonthLength ( $mm, $dd )) {
			return false;
		}
		if ($mm == 2) {
			if (! $this->checkLeapMonth ( $mm, $dd, $yyyy )) {
				return false;
			}
		}
		if (strlen ( $dd ) == 1)
			$dd = "0" . $dd;
		if (strlen ( $mm ) == 1)
			$mm = "0" . $mm;
		return $dd . "/" . $mm . "/" . $yyyy;
	}

	function getTimeStart($offset = 0, $issunday = true, $now = "") {
		$offset = ( float ) $offset;
		$now = ( int ) $now;
		
		if (empty ( $now ))
			$now = mktime ();
			
		/* ------------------------------------------------------------------------------------------------ */
		// Determine GMT Time (UTC+00:00)
		// Determine this minute, this hour, this day, this month, this year
		// Don't use strftime()
		$minute = ( int ) gmstrftime ( "%M", $now );
		$hour = ( int ) gmstrftime ( "%H", $now );
		$day = ( int ) gmstrftime ( "%d", $now );
		$month = ( int ) gmstrftime ( "%m", $now );
		$year = ( int ) gmstrftime ( "%Y", $now );
		
		// Determine Starting GMT Time and Local Time of Today
		$daystart = gmmktime ( 23, 59, 59, $month, $day, $year );
		$local_daystart = VSFDateTime::localTimeStart ( $daystart, $offset, "day" );
		
		// Determine Starting GMT Time and Local Time of Yesterday
		// $yesterdaystart		=	strtotime( "-1 day", $daystart ) ;
		

		$tomorrowday = $daystart + 86400;
		
		$yesterdaystart = $daystart - 86400;
		$local_yesterdaystart = $local_daystart - 86400;
		
		// Determine Starting GMT Time and Local Time of This Week
		// If Sunday is starting day of week then Sunday = 0 ... Saturday = 6
		// If Monday is starting day of week then Monday = 0 ... Sunday = 6
		$weekday = ( int ) strftime ( "%w", $now );
		
		if (! $issunday) {
			if ($weekday)
				$weekday --;
			else
				$weekday = 6;
		}
		
		$weekstart = $daystart - $weekday * 86400;
		$local_weekstart = VSFDateTime::localTimeStart ( $weekstart, $offset, "week" );
		
		// Starting Starting GMT Time and Local Time of Last Week
		$lweekstart = $weekstart - 7 * 86400;
		$local_lweekstart = $local_weekstart - 7 * 86400;
		
		// Determine Starting GMT Time and Local Time of This Month
		$monthstart = gmmktime ( 0, 0, 0, $month, 1, $year );
		$local_monthstart = VSFDateTime::localTimeStart ( $monthstart, $offset, "month" );
		
		// Determine Starting GMT Time and Local Time of Last Month
		// $days_lmonth: Number days of last month (28/29, 30 or 31)
		$days_lmonth = ( int ) strftime ( "%d", $monthstart - 86400 );
		$lmonthstart = $monthstart - $days_lmonth * 86400;
		$local_lmonthstart = $local_monthstart - $days_lmonth * 86400;
		
		$datetime = array ();
		
		$datetime ['tomorrow'] = $tomorrowday;
		$datetime ["daystart"] = $daystart;
		$datetime ["local_daystart"] = $local_daystart;
		$datetime ["yesterdaystart"] = $yesterdaystart;
		$datetime ["local_yesterdaystart"] = $local_yesterdaystart;
		$datetime ["weekstart"] = $weekstart;
		$datetime ["local_weekstart"] = $local_weekstart;
		$datetime ["lweekstart"] = $lweekstart;
		$datetime ["local_lweekstart"] = $local_lweekstart;
		$datetime ["monthstart"] = $monthstart;
		$datetime ["local_monthstart"] = $local_monthstart;
		$datetime ["lmonthstart"] = $lmonthstart;
		$datetime ["local_lmonthstart"] = $local_lmonthstart;
		
		return $datetime;
	}

	/*
	 ** Determine Local Starting Time
	 ** Return Unix Time
	 ** Example: If Global Time (GMT+00:00) = 1248912000 (2009/07/31 - 00:00:00)
	 **			then Local Time (GMT+07:00) = 1248912000 - 7*3600 = 1248886800,
	 **			Local Time (GMT-05:00) = 1248912000 + 5*3600 = 1248930000
	 */
	/* ------------------------------------------------------------------------------------------------ */
	function localTimeStart($timestart, $offset = 0, $type = "day", $now = "") {
		$timestart = ( int ) $timestart;
		$offset = ( float ) $offset;
		$now = ( int ) $now;
		
		if (empty ( $now )) {
			$now = mktime ();
		}
		
		$type = strtolower ( trim ( $type ) );
		if ($type != "day" && $type != "week" && $type != "month")
			$type = "day";
		
		$nexttimestart = strtotime ( "+1 " . $type, $timestart );
		$lasttimestart = strtotime ( "-1 " . $type, $timestart );
		
		if ($offset > 0) {
			if (($nexttimestart - $now) < $offset * 60 * 60) {
				$timestart = $nexttimestart - $offset * 60 * 60;
			} else
				$timestart -= $offset * 60 * 60;
		}
		
		if ($offset < 0) {
			$offset = - $offset;
			if (($now - $timestart) < $offset * 60 * 60) {
				$timestart = $lasttimestart + $offset * 60 * 60;
			} else
				$timestart += $offset * 60 * 60;
		}
		
		return $timestart;
	}
	/* ------------------------------------------------------------------------------------------------ */

}