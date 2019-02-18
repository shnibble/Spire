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
		<link rel="stylesheet" href="/src/css/style.css?v=51356zadf1"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="/src/js/timeout.js"></script>
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
										<td><b>Scores</b>:</td>
										<td>
											<div class="table-cell">
												<table class="user-scores-table">
													<tr>
														<th>Loot Cost</th>
														<th>Attendance</th>
														<th title="Attendance - Loot">Spread</th>
														<th title="Attendance at primary raids over the past 30 days.">30DAR</th>
													</tr>
													<tr>
														<td><?php echo "-" . $player_scores['loot_cost']; ?></td>
														<td><?php echo "+" . $player_scores['attendance_score']; ?></td>
														<td><?php echo ($player_scores['spread'] >= 0)?"+" . $player_scores['spread']:"" . $player_scores['spread']; ?></td>
														<td><?php echo round((float)$player_scores['30_day_attendance_rate'] * 100 ) . '%'; ?></td>
													</tr>
												</table>
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
							<span>Total Cost: <?php echo $player_scores['loot_cost']; ?></span>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<div class="ajax-table-results">
									<span><span class="results-count">0</span> results found</span>
								</div>
								<div class="ajax-table-pager">
									<input type="button" class="standard-button ajax-table-btn page-beginning" value="<<" disabled>
									<input type="button" class="standard-button ajax-table-btn page-back" value="<" disabled>
									<span>Page <span class="ajax-table-pager-page">1</span> of <span class="ajax-table-pager-pages"></span></span>
									<input type="button" class="standard-button ajax-table-btn page-forward" value=">" disabled>
									<input type="button" class="standard-button ajax-table-btn page-end" value=">>" disabled>
								</div>
								<table class="profile-loot-table ajax-table" data-src="/src/ajax-tables/player_loot.php" data-limit="20" data-page="1" data-pages="1" data-sort="date" data-order="DESC" data-filtertype="" data-filtervalue="" data-id="<?php echo $_GET['id']; ?>">
									<thead>
										<tr>
											<th class="ajax-table-header" data-sort="date">Date<span></span></th>
											<th class="ajax-table-header" data-sort="character">Character<span></span></th>
											<th class="ajax-table-header" data-sort="item">Item<span></span></th>
											<th class="ajax-table-header" data-sort="type">Type<span></span></th>
											<th class="ajax-table-header" data-sort="cost">Cost<span></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</article>
					<article>
						<div class="header">
							<h4><?php echo $player['username']; ?>'s Attendance</h4>
							<span>Total: <?php echo $player_scores['attendance_score']; ?></span>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<div class="ajax-table-results">
									<span><span class="results-count">0</span> results found</span>
								</div>
								<div class="ajax-table-pager">
									<input type="button" class="standard-button ajax-table-btn page-beginning" value="<<" disabled>
									<input type="button" class="standard-button ajax-table-btn page-back" value="<" disabled>
									<span>Page <span class="ajax-table-pager-page">1</span> of <span class="ajax-table-pager-pages"></span></span>
									<input type="button" class="standard-button ajax-table-btn page-forward" value=">" disabled>
									<input type="button" class="standard-button ajax-table-btn page-end" value=">>" disabled>
								</div>
								<table class="profile-attendance-table ajax-table" data-src="/src/ajax-tables/player_attendance.php" data-limit="20" data-page="1" data-pages="1" data-sort="date" data-order="DESC" data-filtertype="" data-filtervalue="" data-id="<?php echo $_GET['id']; ?>">
									<thead>
										<tr>
											<th class="ajax-table-header" data-sort="date">Date<span></span></th>
											<th class="ajax-table-header" data-sort="event">Event<span></span></th>
											<th class="ajax-table-header" data-sort="type">Type<span></span></th>
											<th class="ajax-table-header" data-sort="attendance">Attendance<span></span></th>
											<th class="ajax-table-header" data-sort="value">Value<span></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>
		<script src="/src/js/ajaxTables.js"></script>
	</body>
</html>
