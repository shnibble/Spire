<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/player.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/badges.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/player_characters.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/player_attendance.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/player_loot.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/player_scores.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	$user_joined = new DateTime($player['joined']);
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style.css"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	</head>
	<body>
		<div id="wrapper">
			<?php require $_SERVER['DOCUMENT_ROOT'] . "/src/php/header.php"; ?>
			<div id="inner-wrapper">
				<?php require $_SERVER['DOCUMENT_ROOT'] . "/src/php/navigation.php"; ?>
				<div id="content">
					<article>
						<div class="header">
							<h3><?php echo $player['username']; ?>'s Account</h3>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table id="profile-account-table">
									<tr>
										<td><b>Username</b>:</td>
										<td>
											<div class="table-cell">
												<span><?php echo $player['username']; ?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Badges</b>:</td>
										<td>
											<div class="table-cell">
												<div class="user-badges-container">
													<?php if ($player['badges']) {
													$_user_badges = explode(",", $player['badges']);
													foreach ($_user_badges as $b) { ?>
													<div class="badge-row">
														<div class="user-badge large" style="background-image: url('/src/img/badge_<?php echo $b; ?>.png');"></div>
														<span class="badge-name"><?php echo htmlspecialchars($badges_array[$b]['name']); ?></span>
														<p class="badge-description"><?php echo htmlspecialchars($badges_array[$b]['description']); ?></p>
													</div>
													<?php }
													} ?>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Joined</b>:</td>
										<td>
											<div class="table-cell">
												<span><?php echo $user_joined->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d'); ?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Rank</b>:</td>
										<td>
											<div class="table-cell">
												<span class="rank-<?php echo $player['rank']; ?>"><?php echo $player['rankName']; ?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Raid Score</b>:</td>
										<td>
											<div class="table-cell">
												<span title="Attendance / LootCost (Score)"><?php echo $player_scores['attendanceScore'] . "/" . $player_scores['lootScore'] . " (" . $player_scores['totalScore'] . ")"; ?><span>
											</div>
										</td>
									</tr>
									<tr>
										<td><b>Security</b>:</td>
										<td>
											<div class="table-cell">
												<h5 class="security-<?php echo $player['security']; ?>"><?php echo $player['securityName']; ?></h5>
												<span><?php echo $player['securityDescription']; ?></span>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</article>
					<article>
						<div class="header">
							<h4><?php echo $player['username']; ?>'s Characters</h4>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<?php while ($c = mysqli_fetch_array($player_characters)) { ?>
								<div class="profile-character <?php if($c['main']) { echo "main"; } ?>">
									<div class="profile-character-section">
										<h4 class="class-<?php echo $c['class']; ?>"><?php echo $c['name']; ?></h4>
										<span><?php echo $c['className']; ?></span>
										<span>(<?php echo $c['roleName']; ?>)</span>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
					</article>
					<article>
						<div class="header">
							<h4><?php echo $player['username']; ?>'s Loot</h4>
							<span>Total Cost: <?php echo $player_scores['lootScore']; ?></span>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table class="profile-loot-table">
									<thead>
										<tr>
											<th>Date</th>
											<th>Character</th>
											<th>Item</th>
											<th>Type</th>
											<th>Cost</th>
										</tr>
									<thead>
									<tbody>
										<?php while ($l = mysqli_fetch_array($player_loot)) { 
											$ld = new DateTime($l['timestamp']);
										?>
										<tr>
											<td><?php echo $ld->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i'); ?></td>
											<td><?php echo $l['characterName']; ?></td>
											<td><a href="https://classicdb.ch/?item=<?php echo $l['item_id']; ?>" class="quality-<?php echo $l['quality']; ?>" target="_BLANK"><?php echo $l['name']; ?></a></td>
											<td><?php echo $l['typeName']; ?></td>
											<td><?php echo $l['cost']; ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</article>
					<article>
						<div class="header">
							<h4><?php echo $player['username']; ?>'s Attendance</h4>
							<span>Total: <?php echo $player_scores['attendanceScore']; ?></span>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table class="profile-attendance-table">
									<thead>
										<tr>
											<th>Date</th>
											<th>Event</th>
											<th>Type</th>
											<th>Attendance</th>
											<th>Value</th>
										</tr>
									</thead>
									<tbody>
										<?php while ($a = mysqli_fetch_array($player_attendance)) { 
											$ad = new DateTime($a['date']);
										?>
										<tr>
											<td><?php echo $ad->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i'); ?></td>
											<td><?php echo $a['title']; ?></td>
											<td><?php echo $a['eventType']; ?></td>
											<td><?php echo $a['attendanceName']; ?></td>
											<td><?php if ($a['value'] > 0) echo "+"; else if ($a['value'] < 0) echo "-";  echo $a['value']; ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
	</body>
</html>
