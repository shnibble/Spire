<?php
	// get page name
	$pageName = explode(".", basename($_SERVER['PHP_SELF']))[0];
	session_start();
	
	// verify session
	if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
		header("Location: /error.php?id=" . 108);
	}
	
	// get lockout
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
	
	// get lockout events
	function getLockoutEvents($lockout, $stmt, $user_id) {
		$_lockout_begin = $lockout['begin']->format('Y-m-d D H:i:s');
		$_lockout_end = $lockout['end']->format('Y-m-d D H:i:s');
		$stmt->prepare("SELECT t1.`id`, t1.`title`, t1.`description`, t1.`start`, t1.`duration`, t1.`type`, t1.`buff_instructions`, t1.`meetup_instructions`, t2.`name` as typeName, t3.`type` as signupStatus, t3.`character_id`, t4.`name` as characterName, t3.`character_role`, t5.`name` as roleName, t3.`note`, 
						t1.`leader_id`, t6.`username` leader_name, 
						t1.`looter_id`, t7.`username` looter_name, 
						(SELECT COUNT(*) FROM event_signups WHERE `event_id` = t1.`id` AND `type` = 1) as totalAttending, 
						(SELECT COUNT(*) FROM event_signups WHERE `event_id` = t1.`id` AND `type` = 2) as totalNotAttending
						FROM events t1
							INNER JOIN event_types t2
								ON t2.`id` = t1.`type`
							LEFT JOIN event_signups t3
								ON t3.`event_id` = t1.`id` AND t3.`user_id` = ?
							LEFT JOIN characters t4
								ON t4.`id` = t3.`character_id`
							LEFT JOIN roles t5
								ON t5.`id` = t3.`character_role`
							LEFT JOIN users t6 
								ON t6.`id` = t1.`leader_id`
							LEFT JOIN users t7 
								ON t7.`id` = t1.`looter_id`
						WHERE t1.`enabled` = TRUE AND t1.`start` >= ? AND t1.`start` <= ? ORDER BY t1.`start`");
		$stmt->bind_param("iss", $user_id, $_lockout_begin, $_lockout_end);
		$stmt->execute();
		return $stmt->get_result();
	}
	
	// get lockout occasions
	function getLockoutOccasions($lockout, $stmt) {
		$_lockout_begin = $lockout['begin']->format('Y-m-d D H:i:s');
		$_lockout_end = $lockout['end']->format('Y-m-d D H:i:s');
		$stmt->prepare("SELECT t1.`id`, t1.`type`, t1.`date`, t2.`name`
						FROM `occasions` t1
							INNER JOIN `occasion_types` t2
								ON t2.`id` = t1.`type`
						WHERE t1.`enabled` = TRUE AND t1.`date` >= ? AND t1.`date` <= ? ORDER BY t1.`date`");
		$stmt->bind_param("ss", $_lockout_begin, $_lockout_end);
		$stmt->execute();
		return $stmt->get_result();
	}
?>
