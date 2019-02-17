<?php 
	// get lockout occasions function
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
	
	// get occasions per lockout
	$lockout_occasions = array();
	for ($i = 0; $i < count($lockout) ; $i++) {
		$lockout_occasions[$i] = getLockoutOccasions($lockout[$i], $stmt);
	}
?>
