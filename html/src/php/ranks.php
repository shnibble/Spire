<?php
	// get ranks
	$stmt->prepare("SELECT `id`,`name` FROM `ranks` WHERE 1");
	$stmt->execute();
	$ranks = $stmt->get_result();
?>
