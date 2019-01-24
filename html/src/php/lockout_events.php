<?php	
	// get events per lockout
	$lockout_events = array();
	for ($i = 0; $i < count($lockout) ; $i++) {
		$lockout_events[$i] = getLockoutEvents($lockout[$i], $stmt, $_SESSION['user_id']);
	}
?>
