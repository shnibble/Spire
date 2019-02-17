<?php
	// get lockout function
	function getLockout($_count, $_reset_day, $_reset_time) {		
		$result = array("begin"=>"", "end"=>"");
		$today = new DateTime();
		$reset_day = intval($_reset_day);
		$reset_time = $_reset_time;
		$rt = explode(":", $reset_time);
		
		// if today is reset day and current time is on or after reset time
		// then use today as lockout
		if ($today->format('N') == $reset_day && $today->format('G:i:s') >= $reset_time) {
			$result['begin'] = new DateTime();
			$result['begin']->setTime($rt[0], $rt[1], $rt[2]);
		
		// otherwise cycle backwards through days until the reset day is found
		} else {
			$result['begin'] = new DateTime();
			$result['begin']->sub(new DateInterval('P1D'));
			while ($result['begin']->format('N') != $reset_day) {
				$result['begin']->sub(new DateInterval('P1D'));
			}
			$result['begin']->setTime($rt[0], $rt[1], $rt[2]);
		}
		
		
		// change based on _count to match desired lockout
		switch ($_count) {
			case -1:
				$result['begin']->sub(new DateInterval('P7D'));
				break;
			case 1:
				$result['begin']->add(new DateInterval('P7D'));
				break;
			case 2:
				$result['begin']->add(new DateInterval('P14D'));
				break;
			case 3:
				$result['begin']->add(new DateInterval('P21D'));
				break;
			default:
				break;
		}
		
		// set end time
		$result['end'] = clone $result['begin'];
		$result['end']->add(new DateInterval('P6DT23H59M59S'));
		
		return $result;
	}
	
	// set number of lockouts to retrieve
	$max_lockouts = 4;
	
	// get lockouts
	$lockout = array();
	for ($i = 0; $i < $max_lockouts; $i++) {
		$lockout[$i] = getLockout($i, $config['server_reset_day'], $config['server_reset_time']);
	}
?>
