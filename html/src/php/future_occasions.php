<?php
	// get future occasions
	$stmt->prepare("SELECT t1.`id`, t1.`type`, t2.`name`, t1.`date` 
					FROM `occasions` t1
						INNER JOIN `occasion_types` t2
						ON t2.`id` = t1.`type`					
					WHERE t1.`enabled` = TRUE AND t1.`date` > NOW() ORDER BY t1.`date` ASC");
	$stmt->execute();
	$future_occasions = $stmt->get_result();
?>
