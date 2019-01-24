<?php
	// set number of lockouts to retrieve
	$max_lockouts = 4;
	
	// get lockouts
	$lockout = array();
	for ($i = 0; $i < $max_lockouts; $i++) {
		$lockout[$i] = getLockout($i, $config['server_reset_day'], $config['server_reset_time']);
	}
?>
