<?php	
	// get occasions per lockout
	$lockout_occasions = array();
	for ($i = 0; $i < count($lockout) ; $i++) {
		$lockout_occasions[$i] = getLockoutOccasions($lockout[$i], $stmt);
	}
?>
