<?php
	// server timezone
	$SERVER_TIME 	 = $config['server_timezone'];
	$SERVER_TIMEZONE = new DateTimeZone($SERVER_TIME);
	date_default_timezone_set($SERVER_TIME);
	
	// user timezone
	$LOCAL_TIME 	 = $user['timezone_name'];
	$LOCAL_TIMEZONE  = new DateTimeZone($LOCAL_TIME);
?>
