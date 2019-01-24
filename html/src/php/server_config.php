<?php
	// get server configs
	$stmt->prepare("SELECT t1.`updated`, t1.`user_id`, t1.`server_reset_day`, t1.`server_reset_time`, t1.`guild_discord_link`, t1.`server_discord_link`, t1.`server_website_link`, t1.`server_timezone_id`, t2.`name` as server_timezone
					FROM `configuration` t1 
						INNER JOIN `timezones` t2
						ON t2.`id` = t1.`server_timezone_id`
					ORDER BY t1.`updated` DESC LIMIT 1");
	$stmt->execute();
	$result = $stmt->get_result();
	$config = mysqli_fetch_array($result);
?>
