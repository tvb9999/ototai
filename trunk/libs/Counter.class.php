<?php
class VSSCounter {
	private $datetime;

	function __construct() {
		$now = mktime ();
		$this->datetime = VSFDateTime::getTimeStart ( 0, 1, $now );
	}

	function insertCounter($visist = 0, $guest = 0, $member = 0) {
		global $DB;
		$DB->simple_construct ( array ('select' => '*', 'from' => 'counter_log', 'where' => 'time=' . $this->datetime ['daystart'] ) );
		
		$DB->simple_exec ();
		if ($lg = $DB->fetch_row ()) {
			$DB->do_update ( 'counter_log', array ('visits' => $lg ['visits'] + $visist, 'guests' => $lg ['guests'] + $guest, 'members' => $lg ['members'] + $member ), "time=" . $this->datetime ['daystart'] );
		} else {
			$DB->do_insert ( 'counter_log', array ('time' => $this->datetime ['daystart'], 'visits' => $visist, 'guests' => $guest, 'members' => $member ) );
		}
	}

	function getStatistic($timestart = 0, $timestop = 0) {
		
		$timestart = ( int ) $timestart;
		$timestop = ( int ) $timestop;
		return $this->getVisitsFromLogs ( $timestart, $timestop );
	
	}

	function getVisitsFromLogs($timestart = 0, $timestop = 0) {
		global $DB;
		
		$timestart = ( int ) $timestart;
		$timestop = ( int ) $timestop;
		$records = null;
		
		$total = array ();
		
		$total ['visits'] = 0;
		$total ['guests'] = 0;
		$total ['members'] = 0;
		$total ['bots'] = 0;
		$total ['lasttime'] = 0;
		
		$query = array ("select" => "time, visits, guests, members, bots ", "from" => "counter_log ", "where" => "'1=1' " );
		
		//		if (! $timestart) {
		//			if ($timestop) {
		//				$query ['where'] .= " AND time <= $timestop ";
		//			}
		//		} else {
		//			if (! $timestop) {
		//				$query ['where'] .= " AND time = $timestart ";
		//			} else {
		//				if ($timestop == $timestart) {
		//					return $total;
		//				} else {
		//					$query ['where'] .= " AND time > $timestart ";
		//					$query ['where'] .= " AND time <= $timestop ";
		//				}
		//			}
		//		}
		

		if (! $timestop) {
			$timestart = $timestart - 86400;
			$query ['where'] = " time = {$timestart} ";
		} else
			$query ['where'] = " time >= {$timestart} AND time <= {$timestop} ";
		
		$DB->simple_construct ( $query );
		$DB->simple_exec ();
		$record = $DB->fetch_row ();
		if ($record) {
			$lasttime = 0;
			while ( $record ) {
				
				$lasttime = max ( $lasttime, ( int ) $record ['time'] );
				$total ['visits'] += ( int ) $record ['visits'];
				$total ['guests'] += ( int ) $record ['guests'];
				$total ['members'] += ( int ) $record ['members'];
				$total ['bots'] += ( int ) $record ['bots'];
				$record = $DB->fetch_row ();
			}
			$total ['lasttime'] = $lasttime;
		}
		
		return $total;
	}

	function getStatesInformation() {
		global $DB, $vsTemplate, $vsStd, $bw;
		
		$DB->simple_construct ( array ('select' => 'COUNT(*) as totalmember', 'from' => 'user' ) );
		$DB->simple_exec ();
		$stats = $DB->fetch_row ();
		
		$DB->simple_construct ( array ('select' => '*', 'from' => 'user_session', 'where' => 'sessionTime >' . (time () - (30 * 60)) ) );
		
		$DB->simple_exec ();
		$membercount = 0;
		$visitorcount = 0;
		$loginuser = $DB->fetch_row ();
		while ( $loginuser ) {
			if ($loginuser ['userId'])
				$membercount ++;
			else
				$visitorcount ++;
			$loginuser = $DB->fetch_row ();
		}
		$stats ['members'] = $membercount = $membercount ? $membercount : 0;
		$stats ['guests'] = $visitorcount;
		$stats ['visits'] = $membercount + $visitorcount;
		
		return $stats;
	}

	function visitCounter() {
		global $bw, $vsTemplate, $vsSettings;
		
		$temp = $this->getStatesInformation ();
		
		if ($vsSettings->getSystemKey ( 'state_full', 1, 'counter' ))
			$visits = $this->getStatistic ( 0, $this->datetime ['local_daystart'] );
		else
			$visits = $this->getStatistic ( $this->datetime ['local_daystart'] );
		
		$visits ['today'] = $temp ['visits'];
		
		$visits_array = array ();
		// Count Yesterday's Visits
		if ($vsSettings->getSystemKey ( 'counter_yesterday', 0, 'counter' )) {
			$visits_array = $this->getStatistic ( $this->datetime ['local_yesterdaystart'] );
			$visits ['yesterday'] = $visits_array ['visits'];
		}
		
		// Count This Week's Visits
		if ($vsSettings->getSystemKey ( 'counter_week', 0, 'counter' )) {
			$visits_array = $this->getStatistic ( $this->datetime ['weekstart'], $this->datetime ['local_daystart'] );
			$visits ['week'] = $visits_array ['visits'];
		
		//			$visits['week'] += $visits ['visits'];
		}
		// Count Last Week's Visits
		if ($vsSettings->getSystemKey ( 'counter_last_week', 0, 'counter' )) {
			$visits_array = $this->getStatistic ( $this->datetime ['lweekstart'], $this->datetime ['local_weekstart'] );
			$visits ['lastweek'] = $visits_array ['visits'];
		}
		
		// Count This Month's Visits
		if ($vsSettings->getSystemKey ( 'counter_month', 0, 'counter' )) {
			$visits_array = $this->getStatistic ( $this->datetime ['local_monthstart'], $this->datetime ['local_daystart'] );
			$visits ['month'] = $visits_array ['visits'];
		
		//			$visits['month'] += $visits ['visits'];
		}
		
		// Count Last Month's Visits
		if ($vsSettings->getSystemKey ( 'counter_last_month', 0, 'counter' )) {
			$visits_array = $this->getStatistic ( $this->datetime ['lmonthstart'], $this->datetime ['local_monthstart'] );
			$visits ['lastmonth'] = $visits_array ['visits'];
		}
		
		foreach ($visits as $key => $value) {
				$visits[$key] = str_pad((int) $value,8,"0",STR_PAD_LEFT);
				if($vsSettings->getSystemKey ( 'graph_count', 0, 'counter' ))
					$visits[$key] = $this->graphCount($visits[$key]);
		}
		$vsTemplate->global_template->state = $visits;
	}
	
	function graphCount($count){
		global $vsSettings,$bw;
		
	    $len = strlen($count);
	    $graph = null;
	    $style_dir = "/styles/images/graphCount/".$vsSettings->getSystemKey ( 'graph_count_style', '7seg', 'counter' )."/";
	    $ext = $vsSettings->getSystemKey ( 'graph_count_extension', 'gif', 'counter' );
	    for ($i=0;$i<$len;$i++)
	    {
		   $graph .= '<img src="'.$bw->vars['board_url'] . $style_dir . substr($count,$i,1) . '.' . $ext .'" border="0" height="14px">';
	    }
	    return $graph;
	}
}
?>