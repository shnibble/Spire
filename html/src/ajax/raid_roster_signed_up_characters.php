<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['event_id']) || !isset($_POST['raid_roster_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get characters
	if (!$error) {
		$stmt->prepare("SELECT t1.`id`, t1.`name`, t1.`class`, t2.`name` as class_name, t3.`rank` 
						FROM `characters` t1 
							INNER JOIN `classes` t2 
								ON t2.`id` = t1.`class` 
							INNER JOIN `users` t3 
								ON t3.`id` = t1.`user_id` 
						WHERE t1.`enabled` = TRUE 
						AND t1.`id` IN (SELECT `character_id` FROM `event_signups` WHERE `event_id` = ? AND `type` = 1) 
						AND t1.`id` NOT IN (SELECT `character_id` FROM `raid_roster_slots` WHERE `character_id` IS NOT NULL AND `raid_roster_id` = ?)
						ORDER BY t1.`class`, t1.`name`");
		if (!$stmt->bind_param("ii", $_POST['event_id'], $_POST['raid_roster_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$result = $stmt->get_result();
		}
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		$class = 0;
		$counter = 0;
		$first = true;
		while ($player = mysqli_fetch_array($result)) { 
			$counter++;
			$new_class = $player['class'];
			
			if ($new_class != $class && !$first) { 
				if ($counter % 2 == 0) {
					// even number, no placeholder needed
				} else {
					echo "<div class='player-placeholder'></div>";
				}
				$counter = 0;
				$class = $new_class;
			} 
			
			if ($first) {
				$first = false;
				$counter = 0;
				$class = $new_class;
			} ?>
			<div id="player_<?php echo $player['id']; ?>" class="player class-<?php echo $player['class']; ?> rank-<?php echo $player['rank']; ?>" data-characterid="<?php echo $player['id']; ?>" data-name="<?php echo $player['name']; ?>" data-class="<?php echo $player['class']; ?>" data-classname="<?php echo $player['class_name']; ?>" data-rank="<?php echo $player['rank']; ?>">
				<span class="player-name"><?php echo $player['name']; ?></span>
				<span class="player-class"><?php echo $player['class_name']; ?></span>
			</div>
		<?php }
		
	}	
?>
