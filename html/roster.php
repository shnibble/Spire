<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/users.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/badges.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style.css"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="/src/js/stupidtable.min.js"></script>
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
							<h4>Roster</h4>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table id="users-table">
									<thead>
										<tr>
											<th data-sort="string" class="sortable-table-header">Username</th>
											<th>Badges</th>
											<th data-sort="string" class="sortable-table-header">Main Character</th>
											<th data-sort="string" class="sortable-table-header">Rank</th>
											<th data-sort="float" class="sortable-table-header">Loot</th>
											<th data-sort="float" class="sortable-table-header">Attendance</th>
											<th data-sort="float" class="sortable-table-header" title="Attendance at primary raids over the past 30 days.">30DAR</th>
											<th>Joined</th>
											<?php if ($user['security'] >= 2) { ?>
											<th>Last Login</th>
											<th>Security</th>
											<th>Timezone</th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php while ($u = mysqli_fetch_array($users)) { 
											$u_joined = new DateTime($u['joined']);
											if ($u['last_login']) {
												$u_lastLogin = new DateTime($u['last_login']);
											}
										?>
										<tr>
											<td><a href="/viewUser?id=<?php echo $u['id']; ?>"><?php echo $u['username']; ?></a></td>
											<td>
												<div class="user-badges-container">
													<?php if ($u['badges']) {
													$_user_badges = explode(",", $u['badges']);
													foreach ($_user_badges as $b) { ?>
													<div class="user-badge" style="background-image: url('/src/img/badge_<?php echo $b; ?>.png');" title="<?php echo htmlspecialchars($badges_array[$b]['name']) . "\n" . htmlspecialchars($badges_array[$b]['description']); ?>"></div>
													<?php }
													} ?>
												</div>
											</td>
											<td><?php echo $u['characterName']; ?></td>
											<td><?php echo $u['rankName']; ?></td>
											<td><a href="/loot.php?user=<?php echo $u['id']; ?>"><?php echo "-" . $u['loot_cost']; ?></a></td>
											<td><?php echo "+" . $u['attendance_score']; ?></td>
											<td><?php echo round((float)$u['30_day_attendance_rate'] * 100 ) . '%'; ?></td>
											<td><?php echo $u_joined->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d'); ?></td>
											<?php if ($user['security'] >= 2) { ?>
											<td><?php 
												if ($u['last_login']) {
													echo $u_lastLogin->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d'); 
												} else {
													echo "Never";
												}
											?></td>
											<td><?php echo $u['security']; ?></td>
											<td><?php echo $u['timezoneName']; ?></td>
											<?php } ?>
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
		<script>
			// sortable table
			$('#users-table').stupidtable();
		</script>
	</body>
</html>
