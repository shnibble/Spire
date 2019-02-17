<?php 
	// get lockout events function
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
	
	// get events per lockout
	$lockout_events = array();
	for ($i = 0; $i < count($lockout) ; $i++) {
		$lockout_events[$i] = getLockoutEvents($lockout[$i], $stmt, $_SESSION['user_id']);
	}
?>
