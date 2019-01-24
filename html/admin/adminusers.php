<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/all_users.php";
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
							<h4>Users</h4>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table id="users-table">
									<thead>
										<tr>
											<th data-sort="string" class="sortable-table-header">Username</th>
											<th data-sort="string" class="sortable-table-header">Status</th>
											<th data-sort="string" class="sortable-table-header">Main Character</th>
											<th data-sort="string" class="sortable-table-header">Rank</th>
											<th>Joined</th>
											<th>Last Login</th>
											<th data-sort="int" class="sortable-table-header">Security</th>
											<th>Timezone</th>
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
											<td><a href="/admin/viewUser?id=<?php echo $u['id']; ?>"><?php echo $u['username']; ?></a></td>
											<td><?php echo ($u['active'])?"Active":"Deactivated"; ?></td>
											<td><?php echo $u['characterName']; ?></td>
											<td><?php echo $u['rankName']; ?></td>
											<td><?php echo $u_joined->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d'); ?></td>
											<td><?php 
												if ($u['last_login']) {
													echo $u_lastLogin->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d'); 
												} else {
													echo "Never";
												}
											?></td>
											<td><?php echo $u['security']; ?></td>
											<td><?php echo $u['timezoneName']; ?></td>
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
